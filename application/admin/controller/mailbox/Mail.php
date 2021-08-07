<?php

namespace app\admin\controller\mailbox;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\mailSql;
use think\Session;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:33
 */
class Mail extends Backend
{
    protected $sql= null;
    protected $searchFields = 'rf_title';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new mailSql();
    }

    /**
     * 查看
     */
    public function index(){
      
    }
    public function send(){
    	  $admin = Session::get('admin');
       if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            
            $adata['sender_id'] = $admin['id'];
            $adata['add_time'] = time();
            $adata['recipient_mail'] = $params['recipient_mail'];
            $adata['title'] = $params['title'];
            $adata['content'] = $params['content'];
            $adata['priority_level'] = $params['priority_level'];
            $adata['del_flg'] ="0";
            $isin = Db::table('zc_mail')->insert($adata);
            $max_id = Db::table('zc_mail')->max('id');
            if($params['thumb']!="")
            {
            	
            	$files_arr=explode(",",$params['thumb']);
              
            	foreach($files_arr as $key=>$val)
            	{
 
            		$files_data['file_path']=$val;
            		$files_data['mail_id']=$max_id;
            		
            		Db::execute($this->sql->insertfiles($files_data));
            	}
            	
            }
            unset($params['thumb']);
            
            if($isin){
                $this->success();
            }else{
                $this->error();
            }
        }
       return $this->view->fetch();
    }
    public function inbox(){
    		$admin = Session::get('admin');
    	  if ($this->request->isAjax()){
		  
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $total = Db::getOne($this->sql->getInboxCount($admin['email']));
            //echo $this->sql->getRecycleList($sort,$order,$offset,$limit);
            $list = Db::query($this->sql->getInboxList($admin['email'],$sort,$order,$offset,$limit));
           
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
       return $this->view->fetch();
    }
    public function outbox(){
    	  $admin = Session::get('admin');
    	  if ($this->request->isAjax()){
		  
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $total = Db::getOne($this->sql->getOutboxCount($admin['id']));
            //echo $this->sql->getRecycleList($sort,$order,$offset,$limit);
            $list = Db::query($this->sql->getOutboxList($admin['id'],$sort,$order,$offset,$limit));
           
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
       return $this->view->fetch();
    }
    public function recycle(){
    	  if ($this->request->isAjax()){
		  
            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $total = Db::getOne($this->sql->getRecycleCount());
            //echo $this->sql->getRecycleList($sort,$order,$offset,$limit);
            $list = Db::query($this->sql->getRecycleList($sort,$order,$offset,$limit));
           
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
       return $this->view->fetch();
    
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
       	$list[$key]['file_path_url']=$url_arr[0]."public".$val['file_path'];
       	$file_arr=explode("/",$val['file_path']);
       	$list[$key]['file_path']=end($file_arr);
       }
        $row = Db::getOne($this->sql->getMailEdit($ids));
        $this->view->assign("list", $list);
        $this->view->assign("row", $row);
        //$this->assign('admin', $admin);
        return $this->view->fetch();
    }

    /**
     * 放入回收站
     */
    public function del_recycle($ids = NULL){
		
		$bool = Db::execute($this->sql->delRecycleMail($ids));
		//echo $this->sql->delRecycleMail($ids);
		if($bool){
		  $this->success();
		}else{
		  $this->error();
		}
	
    }
    /**
     * 删除
     */
    public function del($ids = NULL){
		
		$bool = Db::execute($this->sql->delMail($ids));
		if($bool){
		  $this->success();
		}else{
		  $this->error();
		}
	
    }
}