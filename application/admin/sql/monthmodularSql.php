<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:38
 */

namespace app\admin\sql;


class monthmodularSql
{
    public function getMonthmodularCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_form %s  AND rf_class=\'m\'',$where);
        return $sql;
    }

    public function getMonthmodularList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT zc_swj_report_form.*,zc_swj_unit.unit_name FROM zc_swj_report_form LEFT JOIN zc_swj_unit ON zc_swj_unit.id = zc_swj_report_form.unit_id %s AND zc_swj_report_form.rf_class=\'m\' ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        return $sql;
    }

    public function insertMonthmodular($data){
        $data['rf_class'] = 'm';
        $sql = sprintf('INSERT INTO zc_swj_report_form(rf_title,code,unit_id,css_class,rf_class,rf_year,rf_note)
VALUES(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')',$data['rf_title'],$data['code'],$data['unit_id'],$data['css_class'],$data['rf_class'],$data['rf_year'],$data['rf_note']);
        return $sql;
    }

    public function getMonthmodularRow($id){
        $sql = sprintf('SELECT zc_swj_report_form.*,zc_swj_unit.unit_name FROM zc_swj_report_form LEFT JOIN zc_swj_unit ON zc_swj_unit.id = zc_swj_report_form.unit_id WHERE zc_swj_report_form.id = %d ',$id);
        return $sql;
    }

    public function updateMonthmodular($data){
        $sql = sprintf('UPDATE zc_swj_report_form SET rf_title=\'%s\',code=\'%s\',unit_id=\'%s\',css_class=\'%s\',rf_class=\'%s\',rf_year=\'%s\',rf_note=\'%s\'
 WHERE id=%d',$data['rf_title'],$data['code'],$data['unit_id'],$data['css_class'],$data['rf_class'],$data['rf_year'],$data['rf_note'],$data['id']);
        return $sql;
    }

    public function delMonthmodular($ids){
        $sql = sprintf('DELETE FROM zc_swj_report_form WHERE id IN (%s)',$ids);
        return $sql;
    }
    public function getSet(){
        $sql = sprintf('SELECT * FROM zc_swj_unit');
        return $sql;
    }
}