<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class ipwhiteSql
{
    public function getIpCount(){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_ip');
        return $sql;
    }

    public function getIpList($sort,$order,$offset,$limit){

       $sql = sprintf('SELECT * FROM zc_ip z ORDER BY %s %s  LIMIT %d,%d', $sort, $order, $offset, $limit);
        return $sql;
    }
   

    public function updateIp($ip,$id)
    {
        	$sql = sprintf("UPDATE zc_ip SET ip='%s' WHERE id=%d",$ip,$id);
        	return $sql;
    
    }
     //编辑查询数据
    public function getIpRow($id)
    {
        $sql = sprintf('SELECT * FROM zc_ip WHERE id = %d', $id);
        return $sql;
    }
   
    
    public function delIp($ids){

       $sql = sprintf('delete FROM `zc_ip` where id=%d',$ids);
        return $sql;
    }

}