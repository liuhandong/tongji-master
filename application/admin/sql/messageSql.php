<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class messageSql
{
    public function getMessageCount($recipient_id){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_message where recipient_id=%d',$recipient_id);
        return $sql;
    }

    public function getMessageList($recipient_id,$sort,$order,$offset,$limit){

       $sql = sprintf('SELECT a.nickname as recipient,a2.nickname as sender,z.* FROM zc_message z
       LEFT JOIN zc_admin a ON z.recipient_id = a.id
       LEFT JOIN zc_admin a2 ON z.sender_id = a2.id where recipient_id=%d ORDER BY %s %s  LIMIT %d,%d',$recipient_id, $sort, $order, $offset, $limit);
        return $sql;
    }
    public function getMessageNew($recipient_id){

       $sql = sprintf('SELECT content FROM zc_message z where recipient_id=%d and read_flg=0',$recipient_id);
        return $sql;
    }

    public function updateMessage($recipient_id)
    {
        	$sql = sprintf('UPDATE zc_message SET read_flg=1 WHERE recipient_id=%d',$recipient_id);
        	return $sql;
    
    }
   
     public function getAllUser(){

       $sql = sprintf('SELECT id FROM `zc_admin`');
        return $sql;
    }

}