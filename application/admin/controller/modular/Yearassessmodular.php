<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 11:05
 */

namespace app\admin\controller\modular;
use app\common\controller\Backend;
use think\Db;
use app\admin\sql\yearassessmodularSql;
use think\Log;
class Yearassessmodular extends Backend
{
    protected $sql= null;
    protected $searchFields = 'code';
    public function _initialize()
    {
        parent::_initialize();
        $this->sql = new yearassessmodularSql();
    }

    /**
     * 查看
     */
    public function index(){
        //快捷查询数组数组联合查询需要带表名
        $searchArr = array('compdata','mondata[]');
        $year_now=date("Y");
        if ($this->request->isAjax()){
            list($where, $sort, $order, $offset, $limit) = $this->sql_buildparams($searchArr);
        
            $total = Db::getOne($this->sql->getYearassessmodularCount($where));
            $list = Db::query($this->sql->getYearassessmodularList($where,$sort,$order,$offset,$limit));
            $result = array("total" => $total['total'], "rows" => $list);
            Log::write("----------------------------------1-------------------------------------");
            Log::record($result,'info',true);
            Log::write("----------------------------------1-------------------------------------");
            return json($result);
        }

        $mondata=array("2021"=>'2021',"2020"=>'2020',"2019"=>'2019');     
        $this->view->assign('mondata', $mondata);
        $this->view->assign('date', $year_now);
        
        $compdata = Db::query($this->sql->getCopSet());
        $this->view->assign('compdata', $compdata);   
       
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    /** 
    public function add(){
        var_dump("666666666666666666666666666666666666666666666666666666");
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            if($params['rf_class'] == '年'){
                $params['rf_class'] = 'y';
            }
            $bool = Db::execute($this->sql->insertYearmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $unit_id = Db::query($this->sql->getSet());
        $number = config('company.number');
        $rf_class = config('company.rfClassY');
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('rf_class',$rf_class);
        return $this->view->fetch();
    }
   
    */
    
     public function add(){
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            if($params['rf_class'] == '年'){
                $params['rf_class'] = 'y';
            }
            $bool = Db::execute($this->sql->insertYearassessmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $num_flag = array("1"=>"是","0"=>"否");
        $unit_id = Db::query($this->sql->getSet());
        $number = config('company.number');
        $rf_class = config('company.rfClassY');
        $their_garden = Db::query($this->sql->getCopSet());
        $list1 = Db::query($this->sql->getSet());
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('rf_class',$rf_class);
        $this->assign('num_flag',$num_flag);
        $this->assign('their_garden',$their_garden);
        $this->assign('list1',$rf_class);
        return $this->view->fetch();
    }
    
    /**
     * 编辑
     */
    public function edit($ids = NULL){
        if ($this->request->isPost()){
            dump("------------------------------");
            $params = $this->request->post("row/a");
            $params['css_class'] = '';
            if($params['rf_class'] == '年'){
                $params['rf_class'] = 'y';
            }
            $bool = Db::execute($this->sql->updateYearassessmodular($params));
            if($bool){
                $this->success();
            }else{
                $this->error();
            }
        }
        $num_flag = array("1"=>"是","0"=>"否");
        $row = Db::getOne($this->sql->getYearassessmodularRow($ids));
        $unit_id = Db::query($this->sql->getSet());
        $this->view->assign("row", $row);
        $number = config('company.number');
        $their_garden = Db::query($this->sql->getCopSet());
        $list1 = Db::query($this->sql->getSet());
        $rf_class = config('company.rfClassY');
        $this->assign('rf_class',$rf_class);
        $this->assign('num_flag',$num_flag);
        $this->assign('their_garden',$their_garden);
        $this->assign('unit_id',$unit_id);
        $this->assign('number',$number);
        $this->assign('list1',$rf_class);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = NULL){
        $bool = Db::execute($this->sql->delYearassessmodular($ids));
        if($bool){
            $this->success();
        }else{
            $this->error();
        }
    }
}