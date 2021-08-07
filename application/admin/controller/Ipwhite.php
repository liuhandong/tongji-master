<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\Session;
use app\admin\sql\ipwhiteSql;

/**
 * 站内消息
 *
 * @icon fa fa-dashboard
 * @remark 
 */
class Ipwhite extends Backend
{
	public function _initialize()
    {
        parent::_initialize();
        $this->sql = new ipwhiteSql();
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

            $offset = $this->request->get("offset");
            $limit = $this->request->get("limit");
            $total = Db::getOne($this->sql->getIpCount());
            
            $list = Db::query($this->sql->getIpList($sort,$order,$offset,$limit));
            
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }

        return $this->view->fetch();
    }
     public function add()
    {
    	   $admin = Session::get('admin');
        if ($this->request->isAjax()){
		  $adata['ip'] = $this->request->post("ip");
		  $adata['add_time'] = time();
		  $isin = Db::table('zc_ip')->insert($adata);
            if($isin){
                $this->success();
            }else{
                $this->error();
            }
        }

        return $this->view->fetch();
    }

        /**
     * 编辑
     */
    public function edit($ids = NULL){
        //$admin = Session::get('admin');
        if ($this->request->isPost()){
        	
            $ip = $this->request->post("ip");
            
            $bool = Db::execute($this->sql->updateIp($ip,$ids));
            
           $this->success();
        }
        $row = Db::query($this->sql->getIpRow($ids));
       
        $this->view->assign("row", $row);
        $this->view->assign("ids", $ids);
        return $this->view->fetch();
    }

     /**
     * 删除
     */
    public function del($ids = NULL){
		$admin = Session::get('admin');
		
		$bool = Db::execute($this->sql->delIp($ids));
		if($bool){
		  $this->success();
		}else{
		  $this->error();
		}
	    
    }

}
