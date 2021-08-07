<?php

namespace app\admin\controller\declared;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\companySql;
use think\Session;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:33
 */
class Company extends Backend
{
    protected $sql= null;
    protected $searchFields = 'rf_title';
    protected $noNeedRight=['*'];
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new companySql();
    }

    /**
     * 查看
     */
    public function index(){
         if ($this->request->isPost()){
        	
            $params = $this->request->post("row/a");
		  $url_arr=explode("/",ROOT_PATH);
		  $a=count($url_arr);
		  $key=$a-2;
		  $url=$url_arr[$key];
		
		  $params['file_path']="/".$url."/public".$params['file_path'];
        
           
            $rows = Db::query($this->sql->insertCompany($params));
            $result = array("rows" => $rows);
             $this->success("填报完成");
         }
        return $this->view->fetch();
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
    

}