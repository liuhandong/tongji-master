<?php

namespace app\admin\controller\declared;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\declaredsSql;
use think\Session;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:33
 */
class Declareds extends Backend
{
    protected $sql= null;
    protected $searchFields = 'id';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new declaredsSql();
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
		  $admin = Session::get('admin');
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);
			$groups = $this->auth->getGroups();
			$rules=$groups[0]['name'];
			if($rules=="level2")
			{
				$where.=" and declared_company_id=".$admin['company_id'];
			}
            $total = Db::getOne($this->sql->getDeclaredsCount($where));
            $list = Db::query($this->sql->getDeclaredsList($where,$sort,$order,$offset,$limit));
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
     * 获取数据列
     */
    public function getdata(){
    	   
        $rows = Db::query($this->sql->getDeclaredmRows());
        return $rows;
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
            $total = Db::getOne($this->sql->getUploadListCount($fa_id));
            $list = Db::query($this->sql->getUploadList($fa_id));
            $url=$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		  $url_arr=explode("public",$url);
		  //$file = $url_arr[0]."public/".$rows['file_path'];
            foreach($list as $key=>$val)
            {
            	$list[$key]['file_path']=$url_arr[0]."public/".$val['file_path'];
            	$file_name=explode("/",$val['file_path']);
            	$list[$key]['file_name']=$file_name['4'];
            }
            
             $row=Db::getOne($this->sql->getCompanyAndMon($fa_id));
           
            $title=$row['company_park_name'].' '.$row['mon'].' 季报表 查看附件';
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
            $adata['user_id'] = $admin['id'];
            $adata['mon'] = $params['set_time'];
            $adata['add_time'] = time();
            $adata['declared_company_id'] = $admin['company_id'];
            $result=Db::getOne($this->sql->checkData($adata['mon'],$adata['declared_company_id']));
            if($result['total']>0)
            {
            	$this->error("该季度已经填报,请核实后重新填报");
            }
            elseif($params['293']=="")
            {
            	$this->error("请填写填报人");
            }
            else
            {
	            $isin = Db::table('zc_season')->insert($adata);
	            $max_id = Db::table('zc_season')->max('id');
	            $params['fa_id'] = $max_id;
	            $params['admin_id'] = $admin['id'];
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
	            $bool = $this->sql->insertDeclareds($params);
	            
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
        $rows = Db::query($this->sql->getDeclaredsRows());
        $this->assign('rows',$rows);
        $this->assign('admin', $admin);
        return $this->view->fetch();
    }
    
     /**
     * 查询去年同期
     */
    public function getlastyeardata(){
        $mon=$this->request->get("mon");
        $lastyaer_mon=date("Y-m",strtotime("$mon -1 year"));
        if($this->request->get("company_id")=="")
        {
        	//$list = Db::query($this->sql->getDeclareds($this->request->get("id")));
        	//$company_id=$list[0]['declared_company_id'];
        	$admin = Session::get('admin');
        	$company_id=$admin['company_id'];
        }
        else
        {
        	$company_id = $this->request->get("company_id");
        }
        $rows = Db::query($this->sql->getLastYearDeclaredsList($lastyaer_mon,$company_id));

        return $rows;
    }
    
    /**
     * 编辑
     */
    public function edit($ids = NULL){
    	   $admin = Session::get('admin');
	   $list = Db::getOne($this->sql->getDeclareds($ids));
        $row = Db::query($this->sql->getDeclaredsRow($ids));
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
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
            foreach($params as $key=>$item){
                $data['item_val'] = $item;
                $data['id'] = $key;
                $bool = Db::execute($this->sql->updateDeclareds($data));
            }
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
        $this->view->assign("row", $row);
        $this->view->assign("ids", $ids);
        $this->view->assign("checkIdea", $checkIdea);
         $this->view->assign("current_company_id", $admin['company_id']);
        $this->view->assign("project_company_id", $list['declared_company_id']);
        /*$rows = Db::query($this->sql->getDeclaredsRows());
        $this->assign('rows',$rows);
        $rowss = Db::query($this->sql->getDeclaredsRowss());
        $this->assign('rowss',$rowss);*/
        return $this->view->fetch();
    }
	
	  /**
     * 打印
     */
    public function print_excel($ids = NULL){
    	  

 	   $row = Db::getOne($this->sql->getDeclared($ids));

 	   $CompanyRow=Db::getOne($this->sql->getCompanyRow($row['declared_company_id']));

 	   $ExcelCommentrow = Db::query($this->sql->getDeclaredmRowExcelComment($ids));
 	   foreach($ExcelCommentrow as $key=>$val)
 	   {
 	   	$ExcelCommentarr[$val['rf_title']]=$val['item_val'];
 	   }
 	  $mon_arr=explode("-",$row['mon']);
        $filename = $CompanyRow['company_park_name']."(".$mon_arr[0]."年第".($mon_arr[1]+0)."季度)季报";
       
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
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->setCellValue('A1','（三）综合定期报表');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);      
        // 设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValue('A2','大连市开发区（园区）统计地区生产总值季报表');
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
       
        
         
        $objPHPExcel->getActiveSheet()->setCellValue('D8','1-本季');
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

         $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('D8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       

        
        $objPHPExcel->getActiveSheet()->setCellValue('E8','上年同期');
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   $objPHPExcel->getActiveSheet()->getStyle('E8')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
       
	  
       
        $row = Db::query($this->sql->getDeclaredmRowExcel($ids));
       
	   $count=count($row);
	  
	   for($i=0;$i<$count;$i++)
	   {
	   	   if($row[$i]['unit_name']!="无")
	   	   {
	   	   	   $mon=$row[$i]['mon'];
	   	   	   $lastyaer_mon=date("Y-m",strtotime("$mon -1 year"));
	        	   $company_id = $row[$i]['declared_company_id'];//
	        	   $lastyearrow = Db::getOne($this->sql->getLastYearDeclaredsList($lastyaer_mon,$company_id,$row[$i]['rf_id']));

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
		       
		        $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+9),$row[$i]['code']);
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		        $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('B'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
			   $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+9),$row[$i]['unit_name']);
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('C'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
		        
		         
		        if($row[$i]['chk_item_val']!="")
		        	{
		        		$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+9),$row[$i]['chk_item_val']);
		        	}
		        	else
		        	{
		        		$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+9),$row[$i]['item_val']);
		        	}
		        $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		         $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('D'.($i+9))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		       
		
		      
		       
			  
		        if(isset($lastyearrow['item_val']))
		        {
		        	$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),$lastyearrow['item_val']);
		        }
		        else
		        {
		        	$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+9),"");
		        }
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			   $objPHPExcel->getActiveSheet()->getStyle('E'.($i+9))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
	   	   }
	   }
	    $objPHPExcel->getActiveSheet()->mergeCells('A'.($count+10).':E'.($count+10));
	    $objPHPExcel->getActiveSheet()->setCellValue('A'.($count+10),"单位负责人：".$ExcelCommentarr['单位负责人']."                           填表人：".$ExcelCommentarr['填报人']."                   报出日期：".$ExcelCommentarr['报出日期']);

	    $objPHPExcel->getActiveSheet()->mergeCells('A'.($count+11).':E'.($count+11));
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        
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
		$list = Db::getOne($this->sql->getDeclareds($ids));
		if($admin['company_id']==$list['declared_company_id'])
	    	{
			$list = Db::query($this->sql->getUploadList($ids));
			$path_parts = pathinfo(__FILE__);
			$temp=explode("application",$path_parts['dirname']);
			foreach($list as $key=>$val)
			{
				unlink($temp[0]."/public/".$val['file_path']);
			}
			$bool = Db::execute($this->sql->delDeclareds($ids));
			if($bool){
			  $this->success();
			}else{
			  $this->error();
			}
		}
	    	else
	    	{
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
		$list = Db::getOne($this->sql->getDeclareds($ids));
		if($admin['company_id']==$list['declared_company_id'])
		{
	        $sb_user = $admin['id'];
	        $bool = Db::execute($this->sql->m_tibao($ids,$sb_user));
	        if($bool){
	        	  $adata['sender_id'] = $admin['id'];
	            $adata['content'] = $list['company_park_name'].$list['mon']."季报已经提交，请进行校对!";
	            $adata['add_time'] = time();
	            $adata['recipient_id'] = "1";
	            $adata['read_flg'] ="0";
	            $isin = Db::table('zc_message')->insert($adata);

	            $row=Db::query($this->sql->getL3User());
	            
	            foreach($row as $key=>$val)
	            {
	            	  $adata['sender_id'] = $admin['id'];
		            $adata['content'] = $list['company_park_name'].$list['mon']."季报已经提交，请进行校对!";
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

}