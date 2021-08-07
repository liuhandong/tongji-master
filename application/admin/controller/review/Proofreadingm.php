<?php

namespace app\admin\controller\review;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\proofreadingmSql;
use think\Session;
use fast\Tree;
use think\Log;

class Proofreadingm extends Backend
{
    protected $sql= null;
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new proofreadingmSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('c.company_park_name');
        $searchFormArr = array(
            'seach'=>array(
                'mon'=>array('tab'=>'z','field'=>'mon'),
                'id'=>array('tab'=>'z','field'=>'id'),
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            ),
            'order'=>array(
                'id'=>array('tab'=>'z','field'=>'id'),
                'mon'=>array('tab'=>'z','field'=>'mon'),
                'nickname'=>array('tab'=>'a','field'=>'nickname'),
                'add_time'=>array('tab'=>'z','field'=>'add_time'),
                'sb_time'=>array('tab'=>'z','field'=>'sb_time'),
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            )
        );
        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);
               $admin = Session::get('admin');
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
     * 查询1-本月
     */
    public function gettotaldata(){
        $mon=$this->request->get("mon");
        $admin = Session::get('admin');
        $rf_id=$this->request->get("rf_id");
        $list = Db::getOne($this->sql->getDeclaredmRow($this->request->get("id")));
        $company_id=$list['declared_company_id'];
        $mon=$list['mon'];
        $rows = Db::query($this->sql->getTotalList2($mon,$company_id,$rf_id));
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
    	   $admin = Session::get('admin');

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

	        if($v['chk_item_val']!="")
	        {
	        		if($v['item_val']=="")
	        		{
	        			$row[$k]['deviation']="";
	        		}
	        		else
	        		{
		        		if($v['chk_item_val']==$v['item_val'])
			        	{
			        		$deviation=0;
			        	}
		        		else
			        	{
			        		if($v['item_val']==0)
			        		{
			        			$deviation=0;
			        		}
			        		else
			        		{
			        			$deviation=(($v['chk_item_val']-$v['item_val'])/$v['item_val'])*100;
			        		}
			        	}
	        			//$deviation=(($v['chk_item_val']-$v['item_val'])/$v['item_val'])*100;
	        			$row[$k]['deviation']=substr(sprintf("%.3f",$deviation),0,-2)."%";
	        		}
	        }
	        else
	        {
	        		$row[$k]['deviation']="";
	        }
	        
	   }

	   if ($this->request->isPost()){

            $params = $this->request->post("row/a");

			$ckIdea =$params['checkIdea'];
			$ides = Db::query($this->sql->getCheckMessage($ids));
			if(empty($ides))
			{
				$ideadata['id'] = $ids;
				$ideadata['type'] = "m";
				$ideadata['content'] = $ckIdea;
				$ideadata['add_time'] = time();
				$isin = Db::table('zs_check_message')->insert($ideadata);
			}
			else
			{
				$udata['id'] = $ids;
				$udata['content'] = $ckIdea;
				$bool = Db::execute($this->sql->updateCheckMessage($udata));
			}

            if(isset($params['is_check'])){
                $chk_user = $admin['id'];
                $bool = Db::execute($this->sql->m_jiaodui($ids,$chk_user));
                $list=Db::getOne($this->sql->getDeclaredmRow($ids));
	        	  $adata['sender_id'] = $admin['id'];
	            $adata['content'] = $list['company_park_name'].$list['mon']."月报校对完毕，请进行审批!";
	            $adata['add_time'] = time();
	            $adata['recipient_id'] = $list['user_id'];
	            $adata['read_flg'] ="0";
	            $isin = Db::table('zc_message')->insert($adata);
            }
            foreach($params as $key=>$item){
                $data['chk_item_val'] = $item;
                $data['id'] = $key;
                $bool = Db::execute($this->sql->updateDeclaredm($data));
            }
            $this->success();
        }
        elseif ($this->request->isAjax()){//isAjax
        	  $Declaredlist = Db::getOne($this->sql->getDeclaredm($ids));
		  $title="校对(".$Declaredlist['mon']." ".$Declaredlist['company_park_name'].")";
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
        $this->view->assign("list", $list);
        $this->view->assign("ids", $ids);
        $this->view->assign("checkIdea", $checkIdea);
        //print_r($row);exit;
        return $this->view->fetch();
    }
        /**
     * 编辑
     */
    public function view($ids = NULL){
    	   $admin = Session::get('admin');
    	   
       
        if ($this->request->isPost()){

            $params = $this->request->post("row/a");

            if(isset($params['is_check'])){
                
                $chk_user = $admin['id'];
                $bool = Db::execute($this->sql->m_jiaodui($ids,$chk_user));
                $list=Db::getOne($this->sql->getDeclaredmRow($ids));
	        	  $adata['sender_id'] = $admin['id'];
	            $adata['content'] = $list['company_park_name'].$list['mon']."月报校对完毕，请进行审批!";
	            $adata['add_time'] = time();
	            $adata['recipient_id'] = $list['user_id'];
	            $adata['read_flg'] ="0";
	            $isin = Db::table('zc_message')->insert($adata);
            }
            foreach($params as $key=>$item){
                $data['chk_item_val'] = $item;
                $data['id'] = $key;
                $bool = Db::execute($this->sql->updateDeclaredm($data));
            }
            $this->success();
        }
        elseif ($this->request->isAjax()){//isAjax
        	  $Declaredlist = Db::getOne($this->sql->getDeclaredm($ids));
		  $title="查看(".$Declaredlist['mon']." ".$Declaredlist['company_park_name'].")";
            $result = array("title"=>$title);
            return json($result);
        }
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

	        if($v['chk_item_val']!="")
	        {
	        		if($v['item_val']=="")
	        		{
	        			$row[$k]['deviation']="";
	        		}
	        		else
	        		{
		        		if($v['chk_item_val']==$v['item_val'])
			        	{
			        		$deviation=0;
			        	}
		        		else
			        	{
			        		if($v['item_val']==0)
			        		{
			        			$deviation=0;
			        		}
			        		else
			        		{
			        			$deviation=(($v['chk_item_val']-$v['item_val'])/$v['item_val'])*100;
			        		}
			        	}
	        			//$deviation=(($v['chk_item_val']-$v['item_val'])/$v['item_val'])*100;
	        			$row[$k]['deviation']=substr(sprintf("%.3f",$deviation),0,-2)."%";
	        		}
	        }
	        else
	        {
	        		$row[$k]['deviation']="";
	        }
	        
	   }
	   
	   $checkIdea="";
	   
	   $ides = Db::query($this->sql->getCheckMessage($ids));
	   if(!empty($ides))
	   {
			$checkIdea=$ides[0]['content'];
	   }
	   
        $this->view->assign("row", $row);
        $this->view->assign("list", $list);
        $this->view->assign("ids", $ids);
        $this->view->assign("checkIdea", $checkIdea);
        //print_r($row);exit;
        return $this->view->fetch();
    }
    public function backward($ids=Null){
        $admin = Session::get('admin');
        $sb_user = $admin['id'];
        //Db::execute("update zc_swj_report_month SET chk_item_val='' where fa_id='".$ids."'");
        $bool = Db::execute($this->sql->backward($ids,$sb_user));
        if($bool){
        	  $list=Db::getOne($this->sql->getDeclaredmRow($ids));
        	  $adata['sender_id'] = $admin['id'];
            $adata['content'] = "您提交的".$list['mon']."月报已经被驳回，请确认!";
            $adata['add_time'] = time();
            $adata['recipient_id'] = $list['user_id'];
            $adata['read_flg'] ="0";
            $isin = Db::table('zc_message')->insert($adata);
            $this->success('操作成功！');
        }else{
            $this->error('系统错误！');
        }
    }

}