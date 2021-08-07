<?php

namespace app\admin\controller\grouping;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\groupingSql;
use think\Session;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\22 0022
 * Time: 9:35
 */
class Grouping extends Backend
{
    protected $sql= null;
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new groupingSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('unit_name');
        if ($this->request->isAjax()){
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
            $total = Db::getOne($this->sql->getGroupingCount($where));
            $list = Db::query($this->sql->getGroupingList($where,$sort,$order,$offset,$limit));
            $result = array("total" => $total['total'], "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add(){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $bool = Db::execute($this->sql->insertGrouping($params));
            if($bool){
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
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            //$bool = Db::execute($this->sql->updateGrouping($params));
            $params['company_approval_time'] = strtotime($params['company_approval_time']);
            //print_r($params);exit;
            $bool = Db::table('zc_company')->where('id', $params['id'])->update($params);
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $row = Db::getOne($this->sql->getGroupingRow($ids));
        $this->view->assign("row", $row);
        $state = config('set.state');
        $this->assign('state',$state);
        $this->assign('row',$row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delGrouping($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
}