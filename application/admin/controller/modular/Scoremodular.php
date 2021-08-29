<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 11:05
 */

namespace app\admin\controller\modular;
use app\common\controller\Backend;
use think\Db;
use app\admin\sql\scoremodularSql;
use think\Log;
class Scoremodular extends Backend
{
    protected $sql= null;
    protected $searchFields = 'code';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new scoremodularSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('rf_year','their_garden');
        
        $year_now=date("Y");
        if ($this->request->isAjax()){
            //list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
            $rf_year = $this->request->get("rf_year");
            $their_garden = $this->request->get("their_garden");
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $where="where 1=1 ";
            //if($rf_year!="")
            //{
            //	$where.=" and rf_year = '".$rf_year."'";
            //}
            //if($their_garden!="")
            //{
            //	$where.=" and their_garden = '".$their_garden."'";	
            //}
               
            $total = Db::getOne($this->sql->getYearassessmodularCount($where));
            $list = Db::query($this->sql->getYearassessmodularList($where,$sort,$order,$offset,$limit));
            $result = array("total" => $total['total'], "rows" => $list);
            Log::write("----------------------------------1-------------------------------------");
            Log::record($where,'info',true);
            Log::write("----------------------------------1-------------------------------------");
            return json($result);
        }

        $mondata=array("2021"=>'2021',"2020"=>'2020',"2019"=>'2019',"2018"=>'2018');     
        $this->view->assign('mondata', $mondata);
        $this->view->assign('date', $year_now);
        
        $compdata = Db::query($this->sql->getCopSet());
        $this->view->assign('compdata', $compdata);   
       
        return $this->view->fetch();
    }

    
     public function add(){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            if($params['rf_class'] == '年'){
                $params['rf_class'] = 'y';
            }
            $bool = Db::execute($this->sql->insertYearassessmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $num_flag = array("1"=>"是","0"=>"否");
        $unit_id = Db::query($this->sql->getSet());
        $number = config('company.number');
        $rf_class = config('company.rfClassY');
        $their_garden = Db::query($this->sql->getCopSet());
        $list1 = Db::query($this->sql->getSet());
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('rf_class',$rf_class);
        $this->assign('num_flag',$num_flag);
        $this->assign('their_garden',$their_garden);
        $this->assign('list1',$rf_class);
        return $this->view->fetch();
    }
    
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        if ($this->request->isPost()){
            dump("------------------------------");
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            if($params['rf_class'] == '年'){
                $params['rf_class'] = 'y';
            }
            $bool = Db::execute($this->sql->updateYearassessmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $num_flag = array("1"=>"是","0"=>"否");
        $row = Db::getOne($this->sql->getYearassessmodularRow($ids));
        $unit_id = Db::query($this->sql->getSet());
        $this->view->assign("row", $row);
        $number = config('company.number');
        $their_garden = Db::query($this->sql->getCopSet());
        $list1 = Db::query($this->sql->getSet());
        $rf_class = config('company.rfClassY');
        $this->assign('rf_class',$rf_class);
        $this->assign('num_flag',$num_flag);
        $this->assign('their_garden',$their_garden);
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('list1',$rf_class);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delYearassessmodular($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
    
    /**
     * 打印
     */
    public function print_excel($param = NULL){
    	  

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
}