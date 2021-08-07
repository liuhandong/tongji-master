<?php

namespace app\admin\controller\statistics;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\monthSql;
use think\Session;
class Month extends Backend
{
    protected $sql= null;
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new monthSql();
    }
    
    /**
     * 查看
     */
    public function index(){
    	   $mondata = [];
    	   $mon_now=date("m");
    	   $year_now=date("Y");
    	   for($i=$year_now-1;$i<=$year_now+1;$i++)
    	   {
    	   	if($i<$year_now)
    	   	{
    	   		for($w=1;$w<=12;$w++)
    	   		{
    	   			if($w<10)
    	   			{
    	   				$mondata[$i.'-'."0".$w] =$i.'-'."0".$w;
    	   			}
    	   			else
    	   			{
    	   				$mondata[$i.'-'.$w] = $i.'-'.$w;
    	   			}
    	   		}
    	   	}
    	   	elseif($i==$year_now)
    	   	{
    	   		for($w=1;$w<=12;$w++)
    	   		{
    	   			if($w<10)
    	   			{
    	   				$mondata[$i.'-'."0".$w] =$i.'-'."0".$w;
    	   			}
    	   			else
    	   			{
    	   				$mondata[$i.'-'.$w] = $i.'-'.$w;
    	   			}
    	   		}
    	   	}
    	   	else
    	   	{
    	   		for($w=1;$w<=$mon_now;$w++)
    	   		{
    	   			if($w<10)
    	   			{
    	   				$mondata[$i.'-'."0".$w] =$i.'-'."0".$w;
    	   			}
    	   			else
    	   			{
    	   				$mondata[$i.'-'.$w] = $i.'-'.$w;
    	   			}
    	   		}
    	   	}
    	   }
    	 
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('c.company_park_name');
        $searchFormArr = array(
            'seach'=>array(
                'mon'=>array('tab'=>'z','field'=>'mon'),
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            ),
            'order'=>array(
                'id'=>array('tab'=>'z','field'=>'id')
            )
        );
        if ($this->request->isAjax()){
        	 
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);
             //echo $this->sql->getDeclaredmList($where,$sort,$order,$offset,$limit);
             //echo $where;
            $mondata_re = $this->request->get("mondata");
            $mondata_arr=explode(",",$mondata_re);
            $mondata="";
            foreach($mondata_arr as $key=>$val)
            {
            	$mondata.="'".$val."',";
            }
            $mondata=rtrim($mondata,",");
            $where.=" and z.mon in(".$mondata.")";
            $companydata = $this->request->get("companydata");
            if(!strstr($companydata, '999')){ 
            	$where.=" and z.declared_company_id in(".$companydata.")";
            }
            
            //echo $this->sql->getDeclaredmList($where,$sort,$order,$offset,$limit);
            //exit;
            $total = Db::getOne($this->sql->getDeclaredmCount($where));
            
            $list = Db::query($this->sql->getDeclaredmList($where,$sort,$order,$offset,$limit));
            
			$searchType = $this->request->get("searchType");

			if($searchType==1)
			{
				$searchKey='';
				if(!empty($list)){
					$searchKey='(';
				}

				foreach($list as $key=>$val)
				{
					$row = Db::query($this->sql->getDeclaredmRow($val['id']));

					$searchKey.=$val['id'];
					$searchKey.=',';

					foreach($row as $_k=>$_v)
					{
						$list[$key][$_v['rf_id']]=$_v['item_val'];
					}
				}

				if(!empty($list)){
					$searchKey=rtrim($searchKey, ',');
					$searchKey.=')';
					$countRow = Db::query($this->sql->getRowsCount($searchKey));
					array_push($list,$countRow[0]);
				}
				$result = array("total" => $total['total']+1, "rows" => $list);
             }
             elseif($searchType==2)
             {

				foreach($list as $key=>$val)
				{
					$row = Db::query($this->sql->getDeclaredmRow($val['id']));
					
					foreach($row as $k=>$v)
					{
						$mon=$v['mon'];

						$rf_id=$v['rf_id'];
						$rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
						$totalSum=0;
						foreach($rows as $totalKey=>$totalVal)
						{
							$totalSum+=$totalVal['item_val'];
						}
						$list[$key][$v['rf_id']]=$totalSum;
						
					}
					$arr = explode("-",$val['mon']);
					$list[$key]['mon']=$arr[0]."-01到".$val['mon'];
				}
				$result = array("total" => $total['total'], "rows" => $list);
             }
             elseif($searchType==3)
             {

				foreach($list as $key=>$val)
				{
					$row = Db::query($this->sql->getDeclaredmRow($val['id']));
					
					foreach($row as $k=>$v)
					{
						$mon=$v['mon'];

						$rf_id=$v['rf_id'];
						$rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
						$totalSum=0;
						foreach($rows as $totalKey=>$totalVal)
						{
							$totalSum+=$totalVal['item_val'];
						}
						$list[$key][$v['rf_id']]=$totalSum;
						
					}
					$arr = explode("-",$val['mon']);
					$list[$key]['mon']=$arr[0]."-01到".$val['mon'];
				}

				$monTitle=array("id", "declared_company_id", "nickname", "company_park_name", "mon", "add_time", "is_key", "is_key_name", "131", "133", "134", "136", "138", "140", "142", "145", "147", "155", "163", "165", "170", "177", "178", "181", "183", "184", "189", "191", "193", "204", "206", "208", "210", "273", "274", "275", "276", "277", "278", "279", "280", "281", "282", "283", "284", "285", "286", "287", "296", "297", "298", "299", "300", "301", "303", "304", "306", "307", "308", "309", "310");
				$monLoop=array("131", "133", "134", "136", "138", "140", "142", "145", "147", "155", "163", "165", "170", "177", "178", "181", "183", "184", "189", "191", "193", "204", "206", "208", "210", "273", "274", "275", "276", "277", "278", "279", "280", "281", "282", "283", "284", "285", "286", "287", "296", "297", "298", "299", "300", "301", "303", "304", "306", "307", "308", "309", "310");

				$mondata = array(-1, -1, "合计", "合计", "", 0, 3, "已完成");

				foreach ($monLoop as $value)
				{
					$monSum=0;
					foreach($list as $kmon=>$vmon)
					{
						$monSum = $monSum + $vmon[$value];
					}
					array_push($mondata,$monSum);
				}
				$out[] = array_combine($monTitle, $mondata);
				
				array_push($list,$out[0]);
				$result = array("total" => $total['total']+1, "rows" => $list);
             }
             else
             {

				foreach($list as $key=>$val)
				{
					$row = Db::query($this->sql->getDeclaredmRow($val['id']));
					foreach($row as $_k=>$_v)
					{
						$list[$key][$_v['rf_id']]=$_v['item_val'];
					}

				}
				$result = array("total" => $total['total'], "rows" => $list);
             }
             
            return json($result);
        }
        $companydata = [];
        $companydata[999] = '全选';
        $objdata = [];
        $objdata[999] = '全选';
        $rows = Db::query($this->sql->getCompanyRows());
        foreach ($rows as $k => $v)
        {
            $companydata[$v['id']] = $v['company_park_name'];
        }
        $rows = Db::query($this->sql->getObjRows());
        
        foreach ($rows as $k => $v)
        {
            //$objdata[$v['id']] =  $this->break_string(str_replace("&nbsp;","",$v['rf_title']),8);
            $num=intval(substr($v['rf_title'],0,3));
            if($num=="0")
            {
            	$objdata[$v['id']] =  str_replace("&nbsp;","",$v['rf_title']);
            }
            else
            {
            	$title=explode($num,$v['rf_title']);
            	$objdata[$v['id']] =  str_replace("&nbsp;","",$title[1]);
            }
        }
        
        $admin = Session::get('admin');
        $this->view->assign('mondata', $mondata);
        $this->view->assign('companydata', $companydata);
        $this->view->assign('objdata', $objdata);
        $this->view->assign("admin_company_id", $admin['company_id']);
        return $this->view->fetch();
    }

    public function getObjData(){
    	   $objdata="";
        $rows = Db::query($this->sql->getObjRows());
        foreach ($rows as $k => $v)
        {
            //$objdata[$v['id']] =  $this->break_string(str_replace("&nbsp;","",$v['rf_title']),8);
            $num=intval(substr($v['rf_title'],0,3));
            if($num=="0")
            {
            	$objdata[$v['id']] =  str_replace("&nbsp;","",$v['rf_title']);
            }
            else
            {
            	$title=explode($num,$v['rf_title']);
            	$objdata[$v['id']] =  str_replace("&nbsp;","",$title[1]);
            }
        }
        return $objdata;
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
       $list = Db::query($this->sql->getUploadList($ids));
       $url=$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	  $url_arr=explode("public",$url);
	  
       foreach($list as $key=>$val)
       {
			$file_arr=explode("/",$val['file_path']);
			$list[$key]['file_path']=end($file_arr);
			
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

			$list[$key]['file_path_url']=$url_arr[0]."public".$val['file_path'];
       }
            
        $row = Db::query($this->sql->getDeclaredmRow($ids));
	   foreach($row as $k=>$v)
	   {
	        $mon=$v['mon'];
	        
	        $rf_id=$v['rf_id'];
	        $rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
	        $total=0;
		   foreach($rows as $key=>$val)
		   {
		   	$total+=$val['item_val'];
		   }
		    if($v['code']=="8" or $v['code']=="25" or $v['code']=="26" or $v['code']=="32" or $v['code']=="33" or $v['code']=="37" or $v['code']=="38" or $v['code']=="40" or $v['code']=="41" or $v['code']=="43" or $v['code']=="45")
		   {
		   	$Supplementrows = Db::getOne($this->sql->getMonthSupplement($ids));
		   	$row[$k]['total']=$Supplementrows[$rf_id];
		   }
		   else
		   {
		   	$row[$k]['total']=$total;	
		   }


	        $lastyaer_mon=date("Y-m",strtotime("$mon -1 year"));
	        $company_id = $v['declared_company_id'];
	        $lastyearrow = Db::getOne($this->sql->getLastYearDeclaredsList($lastyaer_mon,$company_id,$rf_id));

			if(!empty($lastyearrow['item_val']))
			{
				$row[$k]['lastyear']=$lastyearrow['item_val'];
				
				if ($lastyearrow['item_val'] == 0)
				{
					if(empty($v['item_val']))
					{
						$row[$k]['growth']="--";
					}
					else
					{
						$row[$k]['growth']="100%";
					}
				}
				else
				{
					if(empty($v['item_val']))
					{
						if($v['item_val'] == 0)
						{
							$growth=(($v['item_val']-$row[$k]['lastyear'])/abs($row[$k]['lastyear']))*100;
							$row[$k]['growth']=substr(sprintf("%.3f",$growth),0,-2)."%";
						}
						else
						{
							$row[$k]['growth']="--";
						}
					}
					else
					{
						if($v['item_val']==$row[$k]['lastyear'])
						{
							$row[$k]['growth']="0%";
						}
						else
						{
							$growth=(($v['item_val']-$row[$k]['lastyear'])/abs($row[$k]['lastyear']))*100;
							$row[$k]['growth']=substr(sprintf("%.3f",$growth),0,-2)."%";
						}
					}
				}
			}
			else
			{
				$row[$k]['lastyear']="0";
				if(empty($v['item_val']))
				{
					$row[$k]['growth']="--";
				}
				else
				{
					$row[$k]['growth']="100%";
				}
			}

	        if($v['item_val']!="")
	        {
		        if($v['chk_item_val']!="")
		        {
		        	if($v['chk_item_val']==$v['item_val'])
		        	{
		        		$deviation=0;
		        	}
		        	else
		        	{
		        		$deviation=(($v['chk_item_val']-$v['item_val'])/$v['item_val'])*100;
		        	}
		        		$row[$k]['deviation']=substr(sprintf("%.3f",$deviation),0,-2)."%";
		        }
		        else
		        {
		        		$row[$k]['deviation']="";
		        }
	        }
	        else
	        {
	        	$row[$k]['deviation']="";
	        }
	        
	   }
	   $admin = Session::get('admin');
	   if ($this->request->isPost()){

			$chk_user = $admin['id'];
			$bool = Db::execute($this->sql->backward($ids,$chk_user));
			$list=Db::getOne($this->sql->getDeclaredmRowToMsg($ids));
			$adata['sender_id'] = $admin['id'];
			$adata['content'] = $list['company_park_name'].$list['mon']."月报驳回对完毕，请进行审批!";
			$adata['add_time'] = time();
			$adata['recipient_id'] = $list['user_id'];
			$adata['read_flg'] ="0";
			$isin = Db::table('zc_message')->insert($adata);

			$this->success();
		}
        $this->view->assign("row", $row);
        $this->view->assign("list", $list);
        $this->view->assign("ids", $ids);
        $this->view->assign("current_company_id", $admin['company_id']);
        $this->view->assign("current_user_name", $admin['username']);
        //print_r($row);exit;
        return $this->view->fetch();
    }

    public function getObjData_excel(){
		$objdata="";
		$rows = Db::query($this->sql->getObjRows_execl());
		foreach ($rows as $k => $v)
		{
			$num=intval(substr($v['rf_title'],0,3));
			if($num=="0")
			{
				$objdata[$v['id']] =  str_replace("&nbsp;","",$v['rf_title']).' ('.$v['unit_name'].')';
			}
			else
			{
				$title=explode($num,$v['rf_title']);
				$objdata[$v['id']] =  str_replace("&nbsp;","",$title[1]).' ('.$v['unit_name'].')';
			}
		}
		return $objdata;
 	}

	/**
	* 打印
	*/
    public function print_excel($ids = NULL){
		$getObjDataRows=$this->getObjData_excel();
		$objdata = $this->request->get("objdata");
		$objdata_arr=explode(",",$objdata);
		$filename = "月报统计".time();
		vendor('PHPExcel.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		//设置保存版本格式
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);

		//设置打印页面
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);      //字体大小
		$objPHPExcel->getDefaultStyle()->getFont()->setName('仿宋');//字体

		//设置默认行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
		//$objPHPExcel->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex(0))->setAutoSize(true);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','报表月份');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('B1','申报单位');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$where="where 1=1";
		$mondata_re = $this->request->get("mondata");
		$mondata_arr=explode(",",$mondata_re);
		$mondata="";
		foreach($mondata_arr as $key=>$val)
		{
			$mondata.="'".$val."',";
		}
		$mondata=rtrim($mondata,",");
		$where.=" and z.mon in(".$mondata.")";
		$companydata = $this->request->get("companydata");
		if(!strstr($companydata, '999')){ 
			$where.=" and z.declared_company_id in(".$companydata.")";
		}
		
		$list = Db::query($this->sql->getDeclaredExcelList($where));
		
		$searchType = $this->request->get("searchType");

		if($searchType==1)
		{

			$searchKey='';
			if(!empty($list)){
				$searchKey='(';
			}

			foreach($list as $key=>$val)
			{
				$row = Db::query($this->sql->getDeclaredmRow($val['id']));

				$searchKey.=$val['id'];
				$searchKey.=',';

				foreach($row as $_k=>$_v)
				{
					$list[$key][$_v['rf_id']]=$_v['item_val'];
				}
			}

			if(!empty($list)){
				$searchKey=rtrim($searchKey, ',');
				$searchKey.=')';
				$countRow = Db::query($this->sql->getRowsCount($searchKey));
				array_push($list,$countRow[0]);
			}
		}
		elseif($searchType==2)
		{
			foreach($list as $key=>$val)
			{
				$row = Db::query($this->sql->getDeclaredmRow($val['id']));
				
				foreach($row as $k=>$v)
				{
					$mon=$v['mon'];

					$rf_id=$v['rf_id'];
					$rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
					$totalSum=0;
					foreach($rows as $totalKey=>$totalVal)
					{
						$totalSum+=$totalVal['item_val'];
					}
					$list[$key][$v['rf_id']]=$totalSum;
					
				}
				$arr = explode("-",$val['mon']);
				$list[$key]['mon']=$arr[0]."-01到".$val['mon'];
			}
		}
		elseif($searchType==3)
		{
			foreach($list as $key=>$val)
			{
				$row = Db::query($this->sql->getDeclaredmRow($val['id']));
				
				foreach($row as $k=>$v)
				{
					$mon=$v['mon'];

					$rf_id=$v['rf_id'];
					$rows = Db::query($this->sql->getTotalList($mon,$v['declared_company_id'],$rf_id));
					$totalSum=0;
					foreach($rows as $totalKey=>$totalVal)
					{
						$totalSum+=$totalVal['item_val'];
					}
					$list[$key][$v['rf_id']]=$totalSum;
					
				}
				$arr = explode("-",$val['mon']);
				$list[$key]['mon']=$arr[0]."-01到".$val['mon'];
			}

			$monTitle=array("id", "declared_company_id", "nickname", "company_park_name", "mon", "add_time", "is_key", "is_key_name", "131", "133", "134", "136", "138", "140", "142", "145", "147", "155", "163", "165", "170", "177", "178", "181", "183", "184", "189", "191", "193", "204", "206", "208", "210", "273", "274", "275", "276", "277", "278", "279", "280", "281", "282", "283", "284", "285", "286", "287", "296", "297", "298", "299", "300", "301", "303", "304", "306", "307", "308", "309", "310");
			$monLoop=array("131", "133", "134", "136", "138", "140", "142", "145", "147", "155", "163", "165", "170", "177", "178", "181", "183", "184", "189", "191", "193", "204", "206", "208", "210", "273", "274", "275", "276", "277", "278", "279", "280", "281", "282", "283", "284", "285", "286", "287", "296", "297", "298", "299", "300", "301", "303", "304", "306", "307", "308", "309", "310");

			$mondata = array(-1, -1, "合计", "合计", "", 0, 3, "已完成");

			foreach ($monLoop as $value)
			{
				$monSum=0;
				foreach($list as $kmon=>$vmon)
				{
					$monSum = $monSum + $vmon[$value];
				}
				array_push($mondata,$monSum);
			}
			$out[] = array_combine($monTitle, $mondata);
			
			array_push($list,$out[0]);
         }
		else
		{
			foreach($list as $key=>$val)
			{
				$row = Db::query($this->sql->getDeclaredmRow($val['id']));
				foreach($row as $_k=>$_v)
				{
					$list[$key][$_v['rf_id']]=$_v['item_val'];
				}

			}
		}
		for($i=0;$i<count($list);$i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2),$list[$i]['mon']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2),$list[$i]['company_park_name']);
			if(!strstr($objdata, '999')){ 
				for($w=0;$w<count($objdata_arr);$w++)
				{
					
					if(isset($list[$i][$objdata_arr[$w]]))
					{
						$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),$list[$i][$objdata_arr[$w]]);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),"");
					}
				}
				$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($objdata_arr)+2).($i+2),$list[$i]['is_key_name']);
			}
			else
			{
				$w=0;
				foreach ($getObjDataRows as $key => $value)
				{
					
					if(isset($list[$i][$key]))
					{
						$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),$list[$i][$key]);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),"");
					}
					$w++;
				}
				$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($getObjDataRows)+2).($i+2),$list[$i]['is_key_name']);
			}
			
		}
		if(!strstr($objdata, '999')){ 
			for($i=0;$i<count($objdata_arr);$i++)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($i+2).'1',$getObjDataRows[$objdata_arr[$i]]);
				$strlen=strlen($getObjDataRows[$objdata_arr[$i]]);
				//echo $getObjDataRows[$objdata_arr[$i]];
				//echo $strlen;
				$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr($i+2))->setWidth($strlen);
			}
			$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($objdata_arr)+2).'1','状态');
			$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr(count($objdata_arr)))->setWidth(10);
		}
		else
		{
			$i=0;
			foreach ($getObjDataRows as $key => $value)
			{
				
				$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($i+2).'1',$value);
				$strlen=strlen($value);
				//echo $getObjDataRows[$objdata_arr[$i]];
				//echo $strlen;
				$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr($i+2))->setWidth($strlen);
				$i++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($getObjDataRows)+2).'1','状态');
			$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr(count($getObjDataRows)))->setWidth(10);
		}
		
		
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'allborders' => array( //设置全部边框
					'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
				),
			
			),
		);
    	   if(!strstr($objdata, '999')){ 
    	   	$end=$this->inttochr(count($objdata_arr)+2).(count($list)+1);
	   }
	   else
	   {
	   	$end=$this->inttochr(count($getObjDataRows)+2).(count($list)+1);
	   }
    	  $objPHPExcel->getActiveSheet()->getStyle( 'A1:'.$end)->applyFromArray($styleThinBlackBorderOutline);
    	  //$objPHPExcel->getActiveSheet()->getStyle( 'A1:F5')->applyFromArray($styleThinBlackBorderOutline);

        //设置单元格宽度
        
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
   	public function  inttochr($index, $start = 65) {
		$str = '';
		if (floor($index / 26) > 0) {
		   $str .= $this->inttochr(floor($index / 26)-1);
		}
		return $str . chr($index % 26 + $start);
	}
    public function lastMonth($month){
        $month = date("Y-m",strtotime("-1 month",strtotime($month)));
        return $month;
    }
    public function break_string($str,$num){
	    preg_match_all("/./u", $str, $arr);//将所有字符转成单个数组
		
		//print_r($arr);
		
	    $strstr = '';
	    $width = 0;
	    $arr = $arr[0];
	    foreach($arr as $key=>$string){
	        $strlen = strlen($string);//计算当前字符的长度，一个字母的长度为1，一个汉字的长度为3
			//echo $strlen;
			
	        if($strlen == 3){
				
	            $width += 1;
				
	        }else{
				
	            $width += 0.5;
				
	        }
			
	        $strstr .= $string;
			
			//计算当前字符的下一个
	        if(array_key_exists($key+1, $arr)){
	            $_strlen = strlen($arr[$key+1]);
				 if($_strlen == 3){
	                $_width = 1;
	            }else{
	                $_width = 0.5;
	            }
	            if($width+$_width > $num){
	                $width = 0;
	                $strstr .= "\t";
	            }
	        }
	 
	    }
	    return $strstr;
	}
 
}