<?php

namespace app\admin\controller\modular;

use app\common\controller\Backend;
use think\Db;
use app\admin\sql\monthmodularSql;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:33
 */
class Monthmodular extends Backend
{
    protected $sql= null;
    protected $searchFields = 'code';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new monthmodularSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('code','rf_title');
        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);

            $total = Db::getOne($this->sql->getMonthmodularCount($where));
            $list = Db::query($this->sql->getMonthmodularList($where,$sort,$order,$offset,$limit));
            for ($i=0;$i<count($list);$i++){
                if($list[$i]['rf_class'] == 'm'){
                    $list[$i]['rf_class'] = '月';
                }else if($list[$i]['rf_class'] == 'q'){
                    $list[$i]['rf_class'] = '季';
                }else if($list[$i]['rf_class'] == 'y'){
                    $list[$i]['rf_class'] = '年';
                }
            }
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
            $params['css_class'] = '';
            $bool = Db::execute($this->sql->insertMonthmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $unit_id = Db::query($this->sql->getSet());
        $number = config('company.number');
        $rf_class = config('company.rfClassM');
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('rf_class',$rf_class);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            $bool = Db::execute($this->sql->updateMonthmodular($params));

            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $row = Db::getOne($this->sql->getMonthmodularRow($ids));
        $unit_id = Db::query($this->sql->getSet());
        $this->view->assign("row", $row);
        $number = config('company.number');
        $rf_class = config('company.rfClassM');
        $this->assign('rf_class',$rf_class);
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delMonthmodular($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
}