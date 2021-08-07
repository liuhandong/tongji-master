<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\Session;
use app\admin\sql\reportsearchSql;

/**
 * 填报检索
 *
 * @icon fa fa-dashboard
 * @remark 
 */
class ReportSearch extends Backend
{
	public function _initialize()
    {
        parent::_initialize();
        $this->sql = new reportsearchSql();
    }
    /**
     * 查看
     */
    public function index()
    {
    	           $mondata = [];
    	   $mon_now=date("m");
    	   $year_now=date("Y");
    	   for($i=$year_now-2;$i<=$year_now+2;$i++)
    	   {
    	   		$mondata[$i] =$i.'年';
    	   }
        //快捷查询数组数组联合查询需要带表名
       
        if ($this->request->isAjax()){
		 
            //list($where, $sort, $order, $offset, $limit);
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
		  $mondata = $this->request->get("mondata");
            $objdata= $this->request->get("objdata");
            $companydata= $this->request->get("companydata");
            $where="where 1=1 ";
            if($companydata!="")
            {
            	if(!strstr($companydata, '999')){ 
            		$where.=" and declared_company_id in(".$companydata.")";	
            	}
            }
            if($objdata=="1")
            {
            	$where.=" and mon like '%".$mondata."%'";	
            }
            if($objdata=="2")
            {
            	$monthdata = $this->request->get("monthdata");
            	$monwhere= $mondata."-".$monthdata;
            	$where.=" and mon='".$monwhere."' and rf_class='m'";
            }
            elseif($objdata=="3")
            {
            	$seasondata = $this->request->get("seasondata");
            	$monwhere= $mondata."-".$seasondata;
            	$where.=" and mon='".$monwhere."' and rf_class='s'";
            }
            elseif($objdata=="4")
            {
            	$where.=" and mon ='".$mondata."' and rf_class='y'";
            }
           
 
            $total = Db::getOne($this->sql->getReportCount($where));
            //echo $this->sql->getReportList($where,$sort,$order,$offset,$limit);
            $list = Db::query($this->sql->getReportList($where,$sort,$order,$offset,$limit));
            foreach ($list as $k => $v)
	        {
	        	if($v['rf_class']=="m")
	        	{
	            	$list[$k]['rf_class_name'] ="月报" ;
	        	}
	        	elseif($v['rf_class']=="s")
	        	{
	        		$list[$k]['rf_class_name'] ="季报" ;
	        	}
	        	else
	        	{
	        		$list[$k]['rf_class_name'] = "年报";
	        	}
	        }
            
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
        $companydata = [];
        
        
        $rows = Db::query($this->sql->getCompanyRows());
        $companydata[999] = '全选';
        foreach ($rows as $k => $v)
        {
            $companydata[$v['id']] = $v['company_park_name'];
        }
       
        $objdata=array("1"=>'全部',"2"=>'月报',"3"=>'季报',"4"=>'年报');
        $monthdata=array("01"=>'01',"02"=>'02',"03"=>'03',"04"=>'04',"05"=>'05',"06"=>'06',"07"=>'07',"08"=>'08',"09"=>'09',"10"=>'10',"11"=>'11',"12"=>'12');
        $seasondata=array("01"=>'01',"02"=>'02',"03"=>'03',"04"=>'04');
        $this->view->assign('monthdata', $monthdata);
        $this->view->assign('seasondata', $seasondata);
        $this->view->assign('mondata', $mondata);
        $this->view->assign('companydata', $companydata);
        $this->view->assign('objdata', $objdata);
        $this->view->assign('date', $year_now);
        return $this->view->fetch();
    }
     public function add()
    {
    	   $admin = Session::get('admin');
        if ($this->request->isAjax()){
		  $list = Db::query($this->sql->getAllUser());
		  foreach($list as $key=>$val)
		  {
	            $adata['sender_id'] = $admin['id'];
	            $adata['content'] = $this->request->post("content");
	            $adata['add_time'] = time();
	            $adata['recipient_id'] = $val['id'];
	            $adata['read_flg'] ="0";
	            $isin = Db::table('zc_message')->insert($adata);
            
		  }
            if($isin){
                $this->success();
            }else{
                $this->error();
            }
        }

        return $this->view->fetch();
    }
     /**
     * new
     */
    public function news()
    {
    	   $admin = Session::get('admin');
        if ($this->request->isAjax()){

            
            $recipient_id=$admin['id'];
            $list = Db::query($this->sql->getMessageNew($recipient_id));
            $result = array("new" => count($list), "rows" => $list);
            return json($result);
        }
    }

}
