<?php

namespace app\admin\controller\declared;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\declaredmSql;
use think\Session;
use think\Log;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:33
 */
class Declaredm extends Backend
{
    protected $sql= null;
    protected $searchFields = 'rf_title';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new declaredmSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('c.company_park_name','z.id','a.nickname','z.mon');
        $searchFormArr = array(
            'seach'=>array(
                'mon'=>array('tab'=>'z','field'=>'mon'),
                'id'=>array('tab'=>'z','field'=>'id'),
                'nickname'=>array('tab'=>'a','field'=>'nickname'),
                'add_time'=>array('tab'=>'z','field'=>'add_time'),
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            ),
            'order'=>array(
                'id'=>array('tab'=>'z','field'=>'id'),
                'mon'=>array('tab'=>'z','field'=>'mon'),
                'nickname'=>array('tab'=>'a','field'=>'nickname'),
                'add_time'=>array('tab'=>'z','field'=>'add_time'),
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            )
        );
        
        if ($this->request->isAjax()){
	      
            //list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
            $admin = Session::get('admin');
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);
            $groups = $this->auth->getGroups();
			$rules=$groups[0]['name'];
			if($rules=="level2")
			{
				$where.=" and declared_company_id=".$admin['company_id'];
			}
            $total = Db::getOne($this->sql->getDeclaredmCount($where));
            
            $list = Db::query($this->sql->getDeclaredmList($where,$sort,$order,$offset,$limit));
            //echo $this->sql->getDeclaredmList($where,$sort,$order,$offset,$limit);
            foreach($list as $key=>$val)
	       {
	       	$upload_total = Db::getOne($this->sql->getUploadListCount($val['id']));
	       	if($upload_total['total']>0)
	       	{
	       		$list[$key]['file']='fa fa-paperclip';
	       	}
	       	else
	       	{
	       		$list[$key]['file']='';
	       	}
	       	
	       }           
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
/**
     * 查看上传列表
     */
    public function uploadlist($ids = NULL){

        if ($this->request->isAjax()){//isAjax
        	  if($ids==NULL)
        	  {
        	  	$fa_id = $this->request->get("fa_id");
        	  }
        	  else
        	  {
		  	$fa_id=$ids;
        	  }
		  //echo $this->sql->getUploadListCount($fa_id);
            $total = Db::getOne($this->sql->getUploadListCount($fa_id));
            $list = Db::query($this->sql->getUploadList($fa_id));
            $url=$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		  $url_arr=explode("public",$url);
		  //$file = $url_arr[0]."public/".$rows['file_path'];
            foreach($list as $key=>$val)
            {
                $file_name=explode("/",$val['file_path']);

            	$list[$key]['file_name']=$file_name['4'];

            	$special_val=$val['file_path'];

            	if(strstr($special_val,"规上4上名单.xlsx")=="规上4上名单.xlsx")
            	{
            		$special_val=str_replace("规上4上名单.xlsx","Sep_enterprise_list_bonded_area.xlsx",$special_val);
            		$val['file_path']=$special_val;
            	}
            	elseif(strstr($special_val,"金石滩园区规模以下企业名录.xls")=="金石滩园区规模以下企业名录.xls")
            	{
            		$special_val=str_replace("金石滩园区规模以下企业名录.xls","Sep_enterprise_list_gold_beach.xls",$special_val);
            		$val['file_path']=$special_val;
            	}

            	$list[$key]['file_path']=$url_arr[0]."public".$val['file_path'];
            }

            $row=Db::getOne($this->sql->getCompanyAndMon($fa_id));
           
            $title=$row['company_park_name'].' '.$row['mon'].' 月报表 查看附件';
            $result = array("total" => $total['total'], "rows" => $list,"title"=>$title);
            return json($result);
        }
        $this->assign('ids',$ids);
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add(){
        $admin = Session::get('admin');
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $params2 = $this->request->post("row2/a");
            
            $adata['user_id'] = $admin['id'];
            $adata['mon'] = $params['set_year']."-".$params['set_mon'];
            $adata['add_time'] = time();
            $adata['declared_company_id'] = $admin['company_id'];
            $result=Db::getOne($this->sql->checkData($adata['mon'],$adata['declared_company_id']));
            if($result['total']>0)
            {
            	$this->error("该月份已经填报,请核实后重新填报");
            }
            elseif($params['308']=="")
            {
            	$this->error("请填写填报人");
            }
            else
            {
	            $isin = Db::table('zc_month')->insert($adata);
	            $max_id = Db::table('zc_month')->max('id');
	            if($params['thumb']!="")
	            {
	            	$files_arr=explode(",",$params['thumb']);
	    
	            	foreach($files_arr as $key=>$val)
	            	{
	 
	            		$files_data['file_path']=$val;
	            		$files_data['fa_id']=$max_id;
	 
	            		Db::execute($this->sql->insertfiles($files_data));
	            	}
	            	
	            }
	            unset($params['thumb']);
	            $params['fa_id'] = $max_id;
	            $params['admin_id'] = $admin['id'];
	            $params['306']=$params['validity_year']."-".$params['validity_mon'];
	            $params2['fa_id'] = $max_id;
	            $bool = $this->sql->insertDeclaredm($params);
	            
	            Db::execute($this->sql->insertMonthSupplement($params2));
	            //print_r($bool);exit;
	            for($i=0;$i<count($bool);$i++){
	                Db::execute($bool[$i]);
	            }
	            if($bool){
	                $this->success();
	            }else{
	                $this->error();
	            }
            }
        }
        $rows = Db::query($this->sql->getDeclaredmRows());
        $this->assign('rows',$rows);
        $this->assign('admin', $admin);
        return $this->view->fetch();
    }
     /**
     * 获取数据列
     */
    public function getdata(){
    	   
        $rows = Db::query($this->sql->getDeclaredmRows());
        return $rows;
    }
     /**
     * 获取公司数据列
     */
    public function getcompanydata(){
    	   $admin = Session::get('admin');
        $rows = Db::query($this->sql->getCompanyRows());
        foreach($rows as $key=>$val)
	   {
	   	if($val['id']==$admin['company_id'])
	   	{
	   		$rows[$key]['flg']="1";
	   	}
	   	else
	   	{
	   		$rows[$key]['flg']="0";
	   	}
	   }
        return $rows;
    }
    
    public function download($ids = NULL){
		// 获取目录信息
		
		Log::write("222222222222222222222222222222222");
		$path_parts = pathinfo(__FILE__);
		$temp=explode("application",$path_parts['dirname']);
		$rows = Db::getOne($this->sql->getFile($ids));
    		$file = $temp[0]."/public/".$rows['file_path'];
		if(file_exists($file)){
			header("Content-type:application/octet-stream");
			$filename = basename($file);
			header("Content-Disposition:attachment;filename = ".$filename);
			header("Accept-ranges:bytes");
			header("Accept-length:".filesize($file));
			readfile($file);
			
		}
		return $this->view->fetch();
    }
    public function view($ids = NULL){
		
		$url=$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$url_arr=explode("public",$url);
		$rows = Db::getOne($this->sql->getFile($ids));
		$file = $url_arr[0]."public/".$rows['file_path'];
		$this->assign('file', $file);
    		return $this->view->fetch();
    }
     /**
     * 查询去年同期
     */
    public function getlastyeardata(){
        $mon=$this->request->get("mon");
        $lastyaer_mon=date("Y-m",strtotime("$mon -1 year"));
        //if($this->request->get("company_id")=="")
        //{
        	//$list = Db::query($this->sql->getDeclaredm($this->request->get("id")));
        	//$company_id=$list[0]['declared_company_id'];
        //	$admin = Session::get('admin');
        //	$company_id=$admin['company_id'];
        //}
        //else
        //{
        //	$company_id = $this->request->get("company_id");
        //}
    	$admin = Session::get('admin');
    	$company_id=$admin['company_id'];
        $rows = Db::query($this->sql->getLastYearDeclaredsList($lastyaer_mon,$company_id));

        return $rows;
    }
      /**
     * 查询1-本月
     */
    public function gettotaldata(){
        $mon=$this->request->get("mon");
        $admin = Session::get('admin');
        $rf_id=$this->request->get("rf_id");
        //if($this->request->get("company_id")=="")
        //{
        //	$list = Db::query($this->sql->getDeclaredm($this->request->get("id")));
        //	$company_id=$list[0]['declared_company_id'];
        //}
        //else
        //{
        	//$company_id = $this->request->get("company_id");
        //	$company_id=$admin['company_id'];
        //}
        $company_id=$admin['company_id'];
        $rows = Db::query($this->sql->getTotalList($mon,$company_id,$rf_id));
        $total=0;
	   foreach($rows as $key=>$val)
	   {
	   	$total+=$val['item_val'];
	   }
        return $total;
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        //$admin = Session::get('admin');
        $row = Db::query($this->sql->getDeclaredmRow($ids));
        $admin = Session::get('admin');
	   foreach($row as $k=>$v)
	   {
	        $mon=$v['mon'];
	        
	        $rf_id=$v['rf_id'];
	        $rows = Db::query($this->sql->getTotalList($mon,$admin['company_id'],$rf_id));
	        $Supplementrows = Db::getOne($this->sql->getMonthSupplement($ids));
	        $total=0+intval($v['item_val']);
		   foreach($rows as $key=>$val)
		   {
		   	$total+=$val['item_val'];
		   }
		   $row[$k]['total']=$total;
		   foreach($Supplementrows as $k2=>$v2)
		   {
		   	if($v['rf_id']==$k2)
		   	{
		   		$row[$k][$k2]=$v2;	
		   	}
		   }
	   }
	   $list = Db::getOne($this->sql->getDeclaredm($ids));
        if ($this->request->isPost()){
        	
            $params = $this->request->post("row/a");
            $params2 = $this->request->post("row2/a");
            if($params['thumb']!="")
            {
            	$files_arr=explode(",",$params['thumb']);
    
            	foreach($files_arr as $key=>$val)
            	{
 
            		$files_data['file_path']=$val;
            		$files_data['fa_id']=$ids;
 
            		Db::execute($this->sql->insertfiles($files_data));
            	}
            }
            //$params['306']=$params['validity_year']."-".$params['validity_mon'];
          
            foreach($params as $key=>$item){
                $item = $item;
                $id = $key;
                $bool = Db::execute($this->sql->updateDeclaredm($item,$id));
            }
           Db::execute($this->sql->updateSupplement($params2,$ids));
           $this->success();
        }
        elseif ($this->request->isAjax()){//isAjax
        	
		  $title="查看(".$list['mon']." ".$list['company_park_name'].")";
            $result = array("title"=>$title);
            return json($result);
        }
        
	   $checkIdea="";

	   $ides = Db::query($this->sql->getCheckMessage($ids));
	   if(!empty($ides))
	   {
			$checkIdea=$ides[0]['content'];
	   }

        //print_r($row);
        $this->view->assign("row", $row);
        $this->view->assign("ids", $ids);
        $this->view->assign("current_company_id", $admin['company_id']);
        $this->view->assign("project_company_id", $list['declared_company_id']);
        $this->view->assign("checkIdea", $checkIdea);
        //$this->assign('admin', $admin);
        return $this->view->fetch();
    }



	  /**
     * 打印
     */
    public function print_excel($ids = NULL){
    	  

 	   $row = Db::getOne($this->sql->getDeclaredm($ids));

 	   $CompanyRow=Db::getOne($this->sql->getCompanyRow($row['declared_company_id']));

 	   $ExcelCommentrow = Db::query($this->sql->getDeclaredmRowExcelComment($ids));
 	   foreach($ExcelCommentrow as $key=>$val)
 	   {
 	   	$ExcelCommentarr[$val['rf_title']]=$val['item_val'];
 	   }
 	 
        $filename = $CompanyRow['company_park_name']."(".date("Y年m月",strtotime($row['mon'])).")月报";
        vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        //设置保存版本格式
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
 
        //设置打印页面
	   //$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	   $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	   $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);      //字体大小
	   $objPHPExcel->getDefaultStyle()->getFont()->setName('仿宋');//字体
	   
	   //设置默认行高
	   $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        // 合并
        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
        $objPHPExcel->getActiveSheet()->setCellValue('A1','（三）综合定期报表');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);      
        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValue('A2','大连市开发区（园区）统计月报表');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);    
        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  

	   $objPHPExcel->getActiveSheet()->setCellValue('D3','表    号：');
	    // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
	   $objPHPExcel->getActiveSheet()->setCellValue('E3','大开统2表');
	   // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

         $objPHPExcel->getActiveSheet()->setCellValue('D4','制定机关：');
	    // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         
	   $objPHPExcel->getActiveSheet()->setCellValue('E4','大连市商务局');
	   // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	   $objPHPExcel->getActiveSheet()->setCellValue('A5','开发区（园区）代码：'.$CompanyRow['company_code']);
	   $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	   $objPHPExcel->getActiveSheet()->setCellValue('A6','开发区（园区）名称：'.$CompanyRow['company_park_name']);
	   $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	   $objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
        $objPHPExcel->getActiveSheet()->setCellValue('B6',date("Y年m月",strtotime($row['mon'])));
        $objPHPExcel->getActiveSheet()->setCellValue('D5','批准文号：');
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	   $objPHPExcel->getActiveSheet()->setCellValue('E5',$CompanyRow['company_approval_symbol']);
        $objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $objPHPExcel->getActiveSheet()->setCellValue('D6','有效期至：');
        $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        
        $objPHPExcel->getActiveSheet()->setCellValue('E6',date("Y年m月",strtotime($ExcelCommentarr['有限期至'])));
        $objPHPExcel->getActiveSheet()->getStyle('E6')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->setCellValue('A8','指    标');
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       
       
	   

        $objPHPExcel->getActiveSheet()->setCellValue('B8','代码');
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       

        $objPHPExcel->getActiveSheet()->setCellValue('C8','单位');
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('C8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('C8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('C8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       
        
         
        $objPHPExcel->getActiveSheet()->setCellValue('D8','本 月');
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

         $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       

        
        $objPHPExcel->getActiveSheet()->setCellValue('E8','1-本月');
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       
	  
        
        $objPHPExcel->getActiveSheet()->setCellValue('F8','上年同期');
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('F8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('F8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       
        $row = Db::query($this->sql->getDeclaredmRowExcel($ids));
        //print_r($row);
        $admin = Session::get('admin');
	   foreach($row as $k=>$v)
	   {
	        $mon=$v['mon'];
	        
	        $rf_id=$v['rf_id'];
	        //echo $this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id);
	        
	        $rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
	        $Supplementrows = Db::getOne($this->sql->getMonthSupplement($ids));
	        $total=$v['item_val'];
		   foreach($rows as $key=>$val)
		   {
			$total+=$val['item_val'];
		   }
		   foreach($Supplementrows as $k2=>$v2)
		   {
		   	if($v['rf_id']==$k2)
		   	{
		   		$row[$k][$k2]=$v2;	
		   	}
		   }
		   $row[$k]['total']=$total;
	   }
	   //print_r($row);
		//exit;
	   $count=count($row);
	   for($i=0;$i<$count;$i++)
	   {
	   	   if($row[$i]['unit_name']!="无")
	   	   {
	   	   	   $mon=$row[$i]['mon'];
	   	   	   $lastyaer_mon=date("Y-m",strtotime("$mon -1 year"));
	        	   $company_id = $row[$i]['declared_company_id'];//
	        	   $lastyearrow = Db::getOne($this->sql->getLastYearList($lastyaer_mon,$company_id,$row[$i]['rf_id']));

	   	   	    $rf_title=$row[$i]['rf_title'];
		        $num=intval(substr($rf_title,0,3));
		        $title=explode($num,$rf_title);	
		        $title_space="";
			   if($num==0)
		       	{
		       		$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+9),str_replace('&nbsp;',' ',$row[$i]['rf_title']));
		       	}
		        	else
		        	{
		        		for($w=0;$w<$num;$w++)
		        		{
		        			$title_space.=" ";
		        		}
		        		$title_space=$title_space.$title[1];
		        		$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+9),$title_space); 
		        	}
		        	
	
		        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			   $objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		        if($row[$i]['pid']=="0")
		        {
		        		$objPHPExcel->getActiveSheet()->getStyle('A'.($i+9))->getFont()->setBold(true);      //第一行是否加粗
		        }
		       
			   
		
		        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+9),$row[$i]['code']);
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
			    if($row[$i]['pid']=="0")
		        {
		        	$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+9),"");
		        }
		        else
		        {
		        	 $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+9),$row[$i]['unit_name']);
		        }
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
		        
		        $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+9),$row[$i]['item_val']);
		        $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		         $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
		
		         if($row[$i]['pid']=="0")
		        {
		        	
		        	$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),"");
		        }
		        else
		        {
		        	if($row[$i]['code']=="8" or $row[$i]['code']=="25" or $row[$i]['code']=="26" or $row[$i]['code']=="32" or $row[$i]['code']=="33" or $row[$i]['code']=="37" or $row[$i]['code']=="38" or $row[$i]['code']=="40" or $row[$i]['code']=="41" or $row[$i]['code']=="43" or $row[$i]['code']=="45")
		        	{
		        		$rf_id=$row[$i]['rf_id'];
		        		$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),$row[$i][$rf_id]);
		        	}
		        	else
		        	{
			        	if($row[$i]['item_val']=="")
			        	{
			        		if($row[$i]['total']!="")
			        		{
			        			$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),$row[$i]['total']);
			        		}
			        		else
			        		{
			        			$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),"");
			        		}
			        	}
			        	else
			        	{
			        		$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),$row[$i]['total']);
			        	}
		        	}
		        	
		        }
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
			  
		        if(isset($lastyearrow['item_val']))
		        {
		        	$objPHPExcel->getActiveSheet()->setCellValue('F'.($i+9),$lastyearrow['item_val']);
		        }
		        else
		        {
		        	$objPHPExcel->getActiveSheet()->setCellValue('F'.($i+9),"");
		        }
		        $objPHPExcel->getActiveSheet()->getStyle('F'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('F'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('F'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('F'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('F'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   	   }
	   }
	    $objPHPExcel->getActiveSheet()->mergeCells('A'.($count+10).':F'.($count+10));
	    $objPHPExcel->getActiveSheet()->setCellValue('A'.($count+10),"单位负责人：".$ExcelCommentarr['单位负责人']."                           填表人：".$ExcelCommentarr['填报人']."                   报出日期：".$ExcelCommentarr['报出日期']);

	    $objPHPExcel->getActiveSheet()->mergeCells('A'.($count+11).':F'.($count+11));
	    $objPHPExcel->getActiveSheet()->setCellValue('A'.($count+11),"说明：".$ExcelCommentarr['说明']);
	   

        // 设置页面边距为0.5厘米 (1英寸 = 2.54厘米)
		//$margin = 1.78 / 2.54;   //phpexcel 中是按英寸来计算的,所以这里换算了一下
		//$marginright = 1 / 2.54;   //phpexcel 中是按英寸来计算的,所以这里换算了一下
		//$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft($margin);      //左
		//$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(marginright);    //右
        
        //设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$filename.'.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
    /**
     * 删除
     */
    public function del($ids = NULL){
	    	$admin = Session::get('admin');
	    	$list = Db::getOne($this->sql->getDeclaredm($ids));
	    	
	    	if($admin['company_id']==$list['declared_company_id'] or $admin['username']=="admin")
	    	{
	    	   $list = Db::query($this->sql->getUploadList($ids));
			$path_parts = pathinfo(__FILE__);
			$temp=explode("application",$path_parts['dirname']);
	       foreach($list as $key=>$val)
	       {
	       	unlink($temp[0]."/public/".$val['file_path']);
	       }
	        $bool = Db::execute($this->sql->delDeclaredm($ids));
	        if($bool){
	            $this->success();
	        }else{
	            $this->error();
	        }
	    	}
	    	else
	    	{
	    		//$this->error($admin['company_id']);
	    		$this->error("权限不足,不能删除该条填报。");
	    	}
    }
    public function filedel($ids = NULL){
        $bool = Db::execute($this->sql->delFile($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
    public function tibao($ids=Null){
		$admin = Session::get('admin');
		$list = Db::getOne($this->sql->getDeclaredm($ids));
		if($admin['company_id']==$list['declared_company_id'])
		{
			$sb_user = $admin['id'];
			$bool = Db::execute($this->sql->m_tibao($ids,$sb_user));
			if($bool){
			  $adata['sender_id'] = $admin['id'];
	            $adata['content'] = $list['company_park_name'].$list['mon']."月报已经提交，请进行校对!";
	            $adata['add_time'] = time();
	            $adata['recipient_id'] = "1";
	            $adata['read_flg'] ="0";
	            $isin = Db::table('zc_message')->insert($adata);

	            $row=Db::query($this->sql->getL3User());
	            
	            foreach($row as $key=>$val)
	            {
	            	  $adata['sender_id'] = $admin['id'];
		            $adata['content'] = $list['company_park_name'].$list['mon']."月报已经提交，请进行校对!";
		            $adata['add_time'] = time();
		            $adata['recipient_id'] = $val['uid'];
		            $adata['read_flg'] ="0";
		            $isin = Db::table('zc_message')->insert($adata);
	            }
			  $this->success('操作成功！');
			}else{
			  $this->error('系统错误！');
			}
		}
		else
	    	{
	    		$this->error("权限不足,不能提交该条填报。");
	    	}
    }
    public function test()
    {
    		//$list = Db::query("SELECT * FROM `zc_month` where is_key=0");
    		 foreach($list as $key=>$val)
            {
            	//$list = Db::query("update zc_swj_report_month SET chk_item_val='' where fa_id='".$val['id']."'");
            	//echo "update zc_swj_report_month SET chk_item_val='' where fa_id='".$val['id']."'"."<br>";
            }
    		//print_r($list);
          echo 555;
          exit;
    		return "111";
    		//update zc_swj_report_month SET chk_item_val='' where fa_id='29'	
    }

}