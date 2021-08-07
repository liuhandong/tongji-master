<?php

namespace app\admin\controller\basicset;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\citySql;

/**
 * 管理员管理
 *
 * @icon fa fa-users
 * @remark 一个管理员可以有多个角色组,左侧的菜单根据管理员所拥有的权限进行生成
 */
class City extends Backend
{
    protected $sql= null;
    protected $searchFields = 'city_name';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new citySql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('city_code');
        //demo联合查询时检索需要设置表名数组
        /*$searchFormArr = array(
            'seach'=>array(
                'id'=>array('tab'=>'zc_city','field'=>'id'),
                'city_name'=>array('tab'=>'zc_city','field'=>'city_name'),
                'city_code'=>array('tab'=>'zc_city','field'=>'city_code')
            ),
            'order'=>array(
                'id'=>array('tab'=>'zc_city','field'=>'id')
            )
        );*/
        if ($this->request->isAjax()){
            //list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr,true,$searchFormArr);
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
            //dump($where);exit;
            //dump($this->sql->getCityCount($where));exit;

            $total = Db::getOne($this->sql->getCityCount($where));
            $list = Db::query($this->sql->getCityList($where,$sort,$order,$offset,$limit));
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
                $bool = Db::execute($this->sql->insertCity($params));
                if($bool){
                    $this->success();
                }else{
                    $this->error();
                }
        }
        $city_code = config('sysvar.city_code');
        //print_r($city_code);
        $this->assign('city_code',$city_code);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $bool = Db::execute($this->sql->updateCode($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $row = Db::getOne($this->sql->getCityRow($ids));
        $this->view->assign("row", $row);
        $city_code = config('sysvar.city_code');
        $this->assign('city_code',$city_code);
        return $this->view->fetch();
    }
    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delCity($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }




}
