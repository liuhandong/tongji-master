<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\Session;
use app\admin\sql\messageSql;

/**
 * 站内消息
 *
 * @icon fa fa-dashboard
 * @remark 
 */
class Message extends Backend
{
	public function _initialize()
    {
        parent::_initialize();
        $this->sql = new messageSql();
    }
    /**
     * 查看
     */
    public function index()
    {
    	   $admin = Session::get('admin');
        if ($this->request->isAjax()){

            $sort = $this->request->get("sort");
            $order = $this->request->get("order");
            $recipient_id=$admin['id'];
            
            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $total = Db::getOne($this->sql->getMessageCount($recipient_id));
            
            $list = Db::query($this->sql->getMessageList($recipient_id,$sort,$order,$offset,$limit));
            Db::query($this->sql->updateMessage($recipient_id));
            
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }

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
