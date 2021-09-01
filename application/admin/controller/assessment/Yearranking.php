<?php

namespace app\admin\controller\assessment;

use app\common\controller\Backend;
use think\Db;
use think\Session;
use app\admin\sql\yearrankingSql;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\12 0012
 * Time: 9:23
 */
class Yearranking extends Backend
{
    protected $sql= null;
    protected $searchFields = 'id';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new yearrankingSql();
    }

    /**
     * 查看
     */
    public function index(){
        $mondata = [];
    	   $mon_now=date("m");
    	   $year_now=date("Y");
    	   for($i=$year_now-4;$i<=$year_now;$i++)
    	   {
    	   		$mondata[$i] =$i.'年';
    	   }
        //快捷查询数组数组联合查询需要带表名
       
        if ($this->request->isAjax()){
		 
            //list($where, $sort, $order, $offset, $limit);
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            if($sort=="id")
            {
            	$sort="mon";
            }
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
		  $mondata_re = $this->request->get("mondata");
            $mondata_arr=explode(",",$mondata_re);
            $mondata="";
            foreach($mondata_arr as $key=>$val)
            {
            	$mondata.="'".$val."',";
            }
            $tempwhere="where 1=1";
            $where="where 1=1";
            $mondata=rtrim($mondata,",");
            $where.=" and z.mon in(".$mondata.")";
            $tempwhere.=" and mon in(".$mondata.")";
            $companydata = $this->request->get("companydata");
            if(!strstr($companydata, '999')){ 
	            $where.=" and z.declared_company_id in(".$companydata.")";
	            $tempwhere.=" and declared_company_id in(".$companydata.")";
            }
            
           
            
            //$total = Db::getOne($this->sql->getDeclaredyCount($where));
            //$list = Db::query($this->sql->getDeclaredExcelList($where));
			
			$list = array();
			$yearwhat = $this->request->get("mondata");
			$list[0]=array("time" => $yearwhat,
							"company_name" => "大连市商务局",
							"score" => "96",
							"ranking" => "1",
							"status" => "已完成",
							);
						$list[1]=array("time" => $yearwhat,
							"company_name" => "大连经济技术开发区",
							"score" => "95",
							"ranking" => "2",
							"status" => "已完成",
							);
						$list[2]=array("time" => $yearwhat,
							"company_name" => "大连长兴岛经济技术开发区",
							"score" => "92",
							"ranking" => "3",
							"status" => "已完成",
							);
						$list[3]=array("time" => $yearwhat,
							"company_name" => "大连保税区",
							"score" => "91",
							"ranking" => "4",
							"status" => "已完成",
							);
						$list[4]=array("time" => $yearwhat,
							"company_name" => "旅顺经济技术开发区",
							"score" => "90",
							"ranking" => "5",
							"status" => "已完成",
							);
										$list[5]=array("time" => $yearwhat,
							"company_name" => "大连高新技术产业园区",
							"score" => "89",
							"ranking" => "6",
							"status" => "已完成",
							);
										$list[6]=array("time" => $yearwhat,
							"company_name" => "大连金石滩国家旅游度假区",
							"score" => "88",
							"ranking" => "7",
							"status" => "已完成",
							);
										$list[7]=array("time" => $yearwhat,
							"company_name" => "大连普湾经济区",
							"score" => "77",
							"ranking" => "8",
							"status" => "已完成",
							);
										$list[8]=array("time" => $yearwhat,
							"company_name" => "大连金州湾临空经济区",
							"score" => "76",
							"ranking" => "9",
							"status" => "已完成",
							);
										$list[9]=array("time" => $yearwhat,
							"company_name" => "大连湾临海装备制造业聚集区",
							"score" => "66",
							"ranking" => "10",
							"status" => "已完成",
							);
							
            

            //echo sprintf("select * from kaohe %s ORDER BY %s %s  LIMIT %d,%d",$tempwhere,$tempsort, $order, $offset, $limit);
            $result = array("total" => 10, "rows" => $list);
            return json($result);
        }
        $companydata = [];
        $companydata[999] = '全选';
        $objdata = [];
        //$objdata[999] = '全选';
        $rows = Db::query($this->sql->getCompanyRows());
        foreach ($rows as $k => $v)
        {
            $companydata[$v['id']] = $v['company_park_name'];
        }
        $rows = Db::query($this->sql->getObjRows());
        //foreach ($rows as $k => $v)
        //{
            //$objdata[$v['id']] =  $this->break_string(str_replace("&nbsp;","",$v['rf_title']),8);
            //$num=intval(substr($v['rf_title'],0,3));
            //if($num=="0")
            //{
            	//$objdata[$v['id']] =  str_replace("&nbsp;","",$v['rf_title']);
            //}
            //else
            //{
            	//$title=explode($num,$v['rf_title']);
            	//$objdata[$v['id']] =  str_replace("&nbsp;","",$title[1]);
            //}
        //}
        $objdata[0] = '分数由低到高';
		$objdata[1] = '分数由高到低';
        $this->view->assign('mondata', $mondata);
        $this->view->assign('companydata', $companydata);
        $this->view->assign('objdata', $objdata);
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
     * 打印
     */
    public function print_excel($ids = NULL){
    	  //$getObjDataRows=$this->getObjData();
    	//  $objdata = $this->request->get("objdata");
 	  //$objdata_arr=explode(",",$objdata);
        $filename = "年度考核".time();
        //vendor('PHPExcel.PHPExcel');
        //$objPHPExcel = new \PHPExcel();
        //设置保存版本格式
        //$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
 
        //设置打印页面
	   //$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	   //$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	   //$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(9);      //字体大小
	   //$objPHPExcel->getDefaultStyle()->getFont()->setName('仿宋');//字体
	   
	   //设置默认行高
	   //$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
	   //$objPHPExcel->getActiveSheet()->getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex(0))->setAutoSize(true);
	   //$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	   //$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
	   //$objPHPExcel->getActiveSheet()->setCellValue('A1','报表月份');
	   //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	   //$objPHPExcel->getActiveSheet()->setCellValue('B1','申报单位');
	   //$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	   //$where="where 1=1";
		//$mondata_re = $this->request->get("mondata");
		//$mondata_arr=explode(",",$mondata_re);
		//$mondata="";
		//foreach($mondata_arr as $key=>$val)
		//{
		//	$mondata.="'".$val."',";
		//}
		//$mondata=rtrim($mondata,",");
		//$where.=" and z.mon in(".$mondata.")";
		
		
		//$companydata = $this->request->get("companydata");
		//if(!strstr($companydata, '999')){ 
		//	$where.=" and z.declared_company_id in(".$companydata.")";
		//}
		//$list = Db::query($this->sql->getDeclaredExcelList($where));
		
		//foreach($list as $key=>$val)
		//{
			
		//	$row = Db::query($this->sql->getDeclaredyRow($val['id']));	
		//	foreach($row as $_k=>$_v)
		//	{
				//if($_v['chk_item_val']!="")
				//{
				//	$list[$key][$_v['rf_id']]=$_v['chk_item_val'];
				//}
				//else
				//{
				//	$list[$key][$_v['rf_id']]=$_v['item_val'];
				//}
		//		$list[$key][$_v['rf_id']]=$_v['item_val'];
		//	}
			
		//}
		//print_r($list);
		//for($i=0;$i<count($list);$i++)
		//{
		//	$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2),$list[$i]['mon']);
		//	$objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2),$list[$i]['company_park_name']);
		//	if(!strstr($objdata, '999')){ 
		//		for($w=0;$w<count($objdata_arr);$w++)
		//		{
		//			$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),$list[$i][$objdata_arr[$w]]);
		//		}
		//		$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($objdata_arr)+2).($i+2),$list[$i]['is_key_name']);
		//	}
		//	else
		//	{
		//		$w=0;
		//		foreach ($getObjDataRows as $key => $value)
		//		{
		//			$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($w+2).($i+2),$list[$i][$key]);
		//			$w++;
		//		}
		//		$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($getObjDataRows)+2).($i+2),$list[$i]['is_key_name']);	
		//	}
			
		//}
		//if(!strstr($objdata, '999')){ 
		//	for($i=0;$i<count($objdata_arr);$i++)
		//	{
		//		$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($i+2).'1',$getObjDataRows[$objdata_arr[$i]]);
		//		$strlen=strlen($getObjDataRows[$objdata_arr[$i]]);
		//		//echo $getObjDataRows[$objdata_arr[$i]];
		//		//echo $strlen;
		//		$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr($i+2))->setWidth($strlen);
		//	}
		//	$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($objdata_arr)+2).'1','状态');
		//	$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr(count($objdata_arr)))->setWidth(10);
		//}else
		//{
		//	$i=0;
		//	foreach ($getObjDataRows as $key => $value)
		//	{
		//		$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr($i+2).'1',$value);
		//		$strlen=strlen($value);
				//echo $getObjDataRows[$objdata_arr[$i]];
				//echo $strlen;
		//		$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr($i+2))->setWidth($strlen);
		//	}
		//	$objPHPExcel->getActiveSheet()->setCellValue($this->inttochr(count($getObjDataRows)+2).'1','状态');
		//	$objPHPExcel->getActiveSheet()->getColumnDimension($this->inttochr(count($getObjDataRows)))->setWidth(10);
		//}
		
		//$styleThinBlackBorderOutline = array(
		//	'borders' => array(
		//		'allborders' => array( //设置全部边框
			//		'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
			//	),
			
			//),
		//);
    	 //  if(!strstr($objdata, '999')){ 
    	 //  	$end=$this->inttochr(count($objdata_arr)+2).(count($list)+1);
	   //}
	   //else
	   //{
	   //	$end=$this->inttochr(count($getObjDataRows)+2).(count($list)+1);
	   //}
    	//  $objPHPExcel->getActiveSheet()->getStyle( 'A1:'.$end)->applyFromArray($styleThinBlackBorderOutline);
    	  //$objPHPExcel->getActiveSheet()->getStyle( 'A1:F5')->applyFromArray($styleThinBlackBorderOutline);

        //设置单元格宽度
        
        //header("Pragma: public");
        //header("Expires: 0");
        //header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        //header("Content-Type:application/force-download");
        //header("Content-Type:application/vnd.ms-execl");
        //header("Content-Type:application/octet-stream");
        //header("Content-Type:application/download");
        //header('Content-Disposition:attachment;filename='.$filename.'.xls');
        //header("Content-Transfer-Encoding:binary");
        //$objWriter->save('php://output');
		$filePath = "../public/xlxsfiles/Tmpl0009.xlsx" ;
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader ->load($filePath,$encode='utf-8');
        
        vendor('PHPExcel.PHPExcel');
        //$objPHPExcel = new \PHPExcel();
        //设置保存版本格式
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
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
    /**
     * 添加
     */
    public function add(){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $bool =  Db::execute($this->sql->insertKaohe($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $comsql = sprintf('SELECT * FROM zc_company');
        $com_row = Db::query($comsql);
        $this->view->assign("com_row", $com_row);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
           $admin = Session::get('admin');
        $list = Db::getOne($this->sql->getDeclaredy($ids));
        $row = Db::query($this->sql->getDeclaredyRow($ids));
        if ($this->request->isPost()){

            $params = $this->request->post("row/a");
           //  if($params['thumb']!="")
           // {
           // 	$files_arr=explode(",",$params['thumb']);
           //
           // 	foreach($files_arr as $key=>$val)
           // 	{
           //
           // 		$files_data['file_path']=$val;
           // 		$files_data['fa_id']=$ids;
           //
           // 		Db::execute($this->sql->insertfiles($files_data));
           // 	}
           // }
           // //print_r($params);exit;
           // foreach($params as $key=>$item){
           //     $data['item_val'] = $item;
           //     $data['id'] = $key;
           //     $bool = Db::execute($this->sql->updateDeclaredy($data));
           // }
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
        $this->view->assign("project_company_id", "");//$list['declared_company_id']

        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delScore($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }

    public function kaohe_ed($ids = NULL){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $num = Db::table('zc_kaohe_de')->where('kaohe_id='.$params['kaohe_id'].'')->count();
            if($num){
                $bool = Db::table('zc_kaohe_de')->where('kaohe_id', $params['kaohe_id'])->update($params);
            }else{
                $bool = Db::table('zc_kaohe_de')->insert($params);
            }
            $kaodata['ms'] = $params['m01']+$params['m02']+$params['m03']+$params['m04']+$params['m05']+$params['m06']+$params['m07']
                +$params['m08']+$params['m09']+$params['m10']+$params['m11']+$params['m12'];
            $kaodata['ss'] =$params['s01']+$params['s02']+$params['s03']+$params['s04'];
            $kaodata['ys'] =$params['y01'];
            $kaodata['qts'] =$params['qt'];
            $kaodata['is_key'] =1;
            $bool = Db::table('zc_kaohe')->where('id', $params['kaohe_id'])->update($kaodata);
            $this->success();
        }
        $khsql = sprintf('SELECT * FROM zc_kaohe WHERE id = %d',$ids);
        $kh_row = Db::getOne($khsql);
        $mlist['m01'] = $this->getm($kh_row,'-01');
        $mlist['m02'] = $this->getm($kh_row,'-02');
        $mlist['m03'] = $this->getm($kh_row,'-03');
        $mlist['m04'] = $this->getm($kh_row,'-04');
        $mlist['m05'] = $this->getm($kh_row,'-05');
        $mlist['m06'] = $this->getm($kh_row,'-06');
        $mlist['m07'] = $this->getm($kh_row,'-07');
        $mlist['m08'] = $this->getm($kh_row,'-08');
        $mlist['m09'] = $this->getm($kh_row,'-09');
        $mlist['m10'] = $this->getm($kh_row,'-10');
        $mlist['m11'] = $this->getm($kh_row,'-11');
        $mlist['m12'] = $this->getm($kh_row,'-12');
        $slist['s01'] = $this->gets($kh_row,'-01');
        $slist['s02'] = $this->gets($kh_row,'-02');
        $slist['s03'] = $this->gets($kh_row,'-03');
        $slist['s04'] = $this->gets($kh_row,'-04');
        $ylist['y01'] = $this->gety($kh_row);
        //print_r($m02);
        $row = Db::getOne($this->sql->getKaoheed($ids));
        $this->view->assign("row", $row);
        $this->view->assign("mlist", $mlist);
        $this->view->assign("slist", $slist);
        $this->view->assign("ylist", $ylist);
        $this->view->assign("ids", $ids);
        return $this->view->fetch();
    }


    public function getm($kh_row,$yue = '-01'){
        $yue = $kh_row['year'].$yue;
        $sql = "SELECT m.*,from_unixtime(sb_time, '%Y-%m-%d') AS sb_data FROM zc_month m
LEFT JOIN zc_admin a ON m.user_id = a.id
LEFT JOIN zc_company c ON c.id = a.company_id
WHERE  c.id = {$kh_row['company_id']} AND m.mon = '$yue' AND is_key = 3";
        $row = Db::getOne($sql);
        if(empty($row)){
            $row['zhunque']='--';
        }else{
            $numz_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_month WHERE fa_id = %d',$row['id']);
            $numz = Db::getOne($numz_sql);

            $numd_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_month WHERE item_val = chk_item_val AND fa_id = %d',$row['id']);
            $numd = Db::getOne($numd_sql);
            $zq = round($numd['num']/$numz['num'],2)*100;
            $row['zhunque'] =$zq.'%';
        }
        return $row;
    }

    public function gets($kh_row,$yue = '-01'){
        $yue = $kh_row['year'].$yue;
        $sql = "SELECT m.*,from_unixtime(sb_time, '%Y-%m-%d') AS sb_data FROM zc_season m
LEFT JOIN zc_admin a ON m.user_id = a.id
LEFT JOIN zc_company c ON c.id = a.company_id
WHERE  c.id = {$kh_row['company_id']} AND m.mon = '$yue' AND is_key = 3";
        $row = Db::getOne($sql);
        if(empty($row)){
            $row['zhunque']='--';
        }else{
            $numz_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_season WHERE fa_id = %d',$row['id']);
            $numz = Db::getOne($numz_sql);

            $numd_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_season WHERE item_val = chk_item_val AND fa_id = %d',$row['id']);
            $numd = Db::getOne($numd_sql);
            $zq = round($numd['num']/$numz['num'],2)*100;
            $row['zhunque'] =$zq.'%';
        }
        return $row;
    }

    public function gety($kh_row){
        $yue = $kh_row['year'];
        $sql = "SELECT m.*,from_unixtime(sb_time, '%Y-%m-%d') AS sb_data FROM zc_year m
LEFT JOIN zc_admin a ON m.user_id = a.id
LEFT JOIN zc_company c ON c.id = a.company_id
WHERE  c.id = {$kh_row['company_id']} AND m.mon = '$yue' AND is_key = 3";
        $row = Db::getOne($sql);
        if(empty($row)){
            $row['zhunque']='--';
        }else{
            $numz_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_year WHERE fa_id = %d',$row['id']);
            $numz = Db::getOne($numz_sql);

            $numd_sql = sprintf('SELECT count(*) as num FROM zc_swj_report_year WHERE item_val = chk_item_val AND fa_id = %d',$row['id']);
            $numd = Db::getOne($numd_sql);
            $zq = round($numd['num']/$numz['num'],2)*100;
            $row['zhunque'] =$zq.'%';
        }
        return $row;
    }

}