<?php

namespace app\admin\controller\statistics;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\companySql;
use think\Session;
class Company extends Backend
{
    protected $sql= null;
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new companySql();
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
                'company_park_name'=>array('tab'=>'c','field'=>'company_park_name')
            ),
            'order'=>array(
                'id'=>array('tab'=>'z','field'=>'id')
            )
        );
        if ($this->request->isAjax()){

            //list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);

            $total = Db::getOne($this->sql->getCompanyCount($where));
            $list = Db::query($this->sql->getCompanyList($where,$sort,$order,$offset,$limit));
            
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
      /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delCompany($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }

 
}