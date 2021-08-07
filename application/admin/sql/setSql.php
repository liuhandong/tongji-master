<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:38
 */

namespace app\admin\sql;


class setSql
{
    public function getSetCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_unit %s ',$where);
        return $sql;
    }

    public function getSetList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT * FROM zc_swj_unit %s  ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        return $sql;
    }

    public function insertSet($data){
        $date = time();
        $sql = sprintf('INSERT INTO zc_swj_unit(unit_name,add_time,state)VALUES(\'%s\',%d,%d)',$data['unit_name'],$date,$data['state']);
        return $sql;
    }

    public function getSetRow($id){
        $sql = sprintf('SELECT * FROM zc_swj_unit WHERE id = %d',$id);
        return $sql;
    }

    public function updateSet($data){
        $date = time();
        $sql = sprintf('UPDATE zc_swj_unit SET unit_name=\'%s\',add_time=%d,state=%d WHERE id=%d',$data['unit_name'],$date,$data['state'],$data['id']);
        return $sql;
    }
    public function delSet($ids){
        $sql = sprintf('DELETE FROM zc_swj_unit WHERE id IN (%s)',$ids);
        return $sql;
    }

}