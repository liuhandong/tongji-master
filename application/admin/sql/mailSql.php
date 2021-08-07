<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\6 0006
 * Time: 15:38
 */

namespace app\admin\sql;


class mailSql
{
    public function getInboxCount($recipient_mail){
        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_mail  where del_flg=0 and recipient_mail='%s'",$recipient_mail);
        return $sql;
    }

    public function getInboxList($recipient_mail,$sort,$order,$offset,$limit){
        $sql = sprintf("SELECT m.id,m.priority_level,CASE m.priority_level  WHEN 0 THEN '一般' WHEN 1 THEN '紧急' WHEN 2 THEN '加急' ELSE '一般' END priority_level_name,m.title,m.content,m.add_time FROM zc_mail m  where del_flg=0 and recipient_mail='%s' ORDER BY %s %s  LIMIT %d,%d",$recipient_mail,$sort,$order,$offset,$limit);
        return $sql;
    }
    public function getOutboxCount($sender_id){
        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_mail  where del_flg=0 and sender_id='%s'",$sender_id);
        return $sql;
    }

    public function getOutboxList($sender_id,$sort,$order,$offset,$limit){
        $sql = sprintf("SELECT  m.id,m.priority_level,CASE m.priority_level  WHEN 0 THEN '一般' WHEN 1 THEN '紧急' WHEN 2 THEN '加急' ELSE '一般' END priority_level_name,m.title,m.content,m.add_time  FROM zc_mail m where del_flg=0 and sender_id='%s' ORDER BY %s %s  LIMIT %d,%d",$sender_id,$sort,$order,$offset,$limit);
        return $sql;
    }

    public function getRecycleCount(){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_mail  where del_flg=1');
        return $sql;
    }

    public function getRecycleList($sort,$order,$offset,$limit){
        $sql = sprintf('SELECT  m.id,m.priority_level,CASE m.priority_level  WHEN 0 THEN \'一般\' WHEN 1 THEN \'紧急\' WHEN 2 THEN \'加急\' ELSE \'一般\' END priority_level_name,m.title,m.content,m.add_time  FROM zc_mail m where del_flg=1 ORDER BY %s %s  LIMIT %d,%d',$sort,$order,$offset,$limit);
        return $sql;
    }

     //编辑查询数据
    public function getMailEdit($id)
    {
       $sql = sprintf("SELECT  za.username as sender_name,m.sender_id,m.id,m.priority_level,CASE m.priority_level  WHEN 0 THEN '一般' WHEN 1 THEN '紧急' WHEN 2 THEN '加急' ELSE '一般' END priority_level_name,m.title,m.content,m.add_time,m.recipient_mail FROM zc_mail m 
       left join zc_admin za on za.id=m.sender_id
       where m.id='%d'",$id);
        return $sql;
    }
     public function getUploadList($id)
    {
       $sql = sprintf("SELECT * from zc_mail_files
       where mail_id='%d'",$id);
        return $sql;
    }
    
    public function insertfiles($data)
    {
    	   $add_time = time();
        $mail_id = $data['mail_id'];
        $file_path = $data['file_path'];
        $sql = sprintf('INSERT INTO zc_mail_files(file_path,add_time,mail_id)VALUES(\'%s\',%d,%d)', $file_path, $add_time,$mail_id);
        return $sql;
    }
    public function delMail($ids){
        $sql = sprintf('DELETE FROM zc_mail WHERE id IN (%s)',$ids);
        return $sql;
    }
    public function delRecycleMail($ids){
        $sql = sprintf('update zc_mail set del_flg=1 WHERE id IN (%s)',$ids);
        return $sql;
    }
    public function getSet(){
        $sql = sprintf('SELECT * FROM zc_swj_unit');
        return $sql;
    }
}