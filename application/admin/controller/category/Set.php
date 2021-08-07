<?php

namespace app\admin\controller\category;
use app\common\controller\Backend;
use think\Db;
use app\admin\sql\setSql;

class Set extends  Backend
{
    protected $sql= null;
    protected $searchFields = 'unit_name';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new setSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('unit_name');
        if ($this->request->isAjax()){

            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);

            $total = Db::getOne($this->sql->getSetCount($where));
            $list = Db::query($this->sql->getSetList($where,$sort,$order,$offset,$limit));
            for ($i=0;$i<count($list);$i++){
                if($list[$i]['state'] == '0'){
                    $list[$i]['state'] = '禁用';
                }else if($list[$i]['state'] == '1'){
                    $list[$i]['state'] = '启用';
                }
                $list[$i]['add_time'] = date("Y-m-d H:i:s",$list[$i]['add_time']);
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
            $bool = Db::execute($this->sql->insertSet($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $state = config('set.state');
        $this->assign('state',$state);
        return $this->view->fetch();
    }
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $bool = Db::execute($this->sql->updateSet($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $row = Db::getOne($this->sql->getSetRow($ids));
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
        $bool = Db::execute($this->sql->delSet($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
}