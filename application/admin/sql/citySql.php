<?php

namespace app\admin\sql;

/**
 * 城市SQL

 */
class citySql
{
    public function getCityCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_city %s ',$where);
        return $sql;
    }

    public function getCityList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT * FROM zc_city %s ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        return $sql;
    }

    public function insertCity($data){
        $sql = sprintf('INSERT INTO zc_city(city_name,city_code)VALUES(\'%s\',\'%s\')',$data['city_name'],$data['city_code']);
        return $sql;
    }

    public function getCityRow($id){
        $sql = sprintf('SELECT * FROM zc_city WHERE id = %d',$id);
        return $sql;
    }

    public function updateCode($data){
        $sql = sprintf('UPDATE zc_city SET city_name=\'%s\',city_code=\'%s\' WHERE id=%d',$data['city_name'],$data['city_code'],$data['id']);
        return $sql;
    }

    public function delCity($ids){
        $sql = sprintf('DELETE FROM zc_city WHERE id IN (%s)',$ids);
        return $sql;
    }



}
