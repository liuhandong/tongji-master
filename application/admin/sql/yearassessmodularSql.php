<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:38
 */
//        $sql = sprintf('SELECT zc_chk_report_form.*,zc_swj_unit.unit_name FROM zc_chk_report_form LEFT JOIN zc_swj_unit ON zc_swj_unit.id = zc_chk_report_form.unit_id %s AND rf_class=\'y\' ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
//        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_chk_report_form %s AND rf_class=\'y\' ',$where);

namespace app\admin\sql;


class yearassessmodularSql
{
    public function getYearassessmodularCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_chk_report_form %s',$where);
        return $sql;
    }

    public function getYearassessmodularList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT zc_chk_report_form.*,zc_swj_unit.unit_name,zcc.company_park_name as company_pn FROM zc_chk_report_form left join zc_company zcc on zcc.id=zc_chk_report_form.their_garden LEFT JOIN zc_swj_unit ON zc_swj_unit.id = zc_chk_report_form.unit_id %s ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        return $sql;
    }

    public function insertYearassessmodular($data){
        $sql = sprintf('INSERT INTO zc_chk_report_form(name,seqn,unit_id,topic,rf_year,order_no,num_flag,their_garden,rf_note)
VALUES(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')',$data['name'],$data['seqn'],$data['unit_id'],$data['topic'],$data['rf_year'],$data['order_no'],$data['num_flag'],$data['their_garden'],$data['rf_note']);
        return $sql;
    }

    public function getYearassessmodularRow($id){
        $sql = sprintf('SELECT zc_chk_report_form.*,zc_swj_unit.unit_name FROM zc_chk_report_form LEFT JOIN zc_swj_unit ON zc_swj_unit.id = zc_chk_report_form.unit_id WHERE zc_chk_report_form.id = %d',$id);
        return $sql;
    }

    public function updateYearassessmodular($data){
        $sql = sprintf('UPDATE zc_chk_report_form SET name=\'%s\',seqn=\'%s\',unit_id=\'%s\',topic=\'%s\',rf_year=\'%s\',order_no=\'%s\',num_flag=\'%s\',their_garden=\'%s\',rf_note=\'%s\'
 WHERE id=%d',$data['name'],$data['seqn'],$data['unit_id'],$data['topic'],$data['rf_year'],$data['order_no'],$data['num_flag'],$data['their_garden'],$data['rf_note'],$data['id']);
        return $sql;
    }

    public function delYearassessmodular($ids){
        $sql = sprintf('DELETE FROM zc_chk_report_form WHERE id IN (%s)',$ids);
        return $sql;
    }
    public function getSet(){
        $sql = sprintf('SELECT * FROM zc_swj_unit');
        return $sql;
    }
    public function getCopSet(){
        $sql = sprintf('SELECT * FROM zc_company');
        return $sql;
    }
}