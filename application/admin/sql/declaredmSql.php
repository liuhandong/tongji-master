<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class declaredmSql
{
    public function getDeclaredmCount($where)
    {
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_month %s ', $where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 0 ',$where);
        return $sql;
    }

    public function getDeclaredmList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 0 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
     public function getUploadListCount($fa_id)
    {

        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_swj_upload_files z where  rf_class='m' and fa_id=%d",$fa_id);
        return $sql;
    }

    public function getUploadList($fa_id)
    {
        $sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='m' and  fa_id=%d",$fa_id);
        return $sql;
    }
    public function getFile($id)
    {
        $sql = sprintf('SELECT * FROM zc_swj_upload_files z where id=%d',$id);
        return $sql;
    }
    public function getDeclaredm($id){
        $sql = sprintf('SELECT z.*,c.company_park_name FROM `zc_month` z LEFT JOIN zc_company c ON z.declared_company_id = c.id where z.id=%d', $id);
        return $sql;
    }
    
	public function getLastYearDeclaredsList($mon,$company_id){
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d'", $mon,$company_id);
        return $sql;
    }
    public function getLastYearList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }
    public function getTotalList($mon,$company_id,$rf_id){
    	   $tem_arr=explode("-",$mon);
    	   $s_month=$tem_arr['0']."-01";
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon>='%s' and mon<'%s'and declared_company_id='%d' and rf_id='%d'", $s_month,$mon,$company_id,$rf_id);
        return $sql;
    }
    public function getMonthSupplement($fa_id){

        $sql = sprintf("SELECT * FROM `zc_form_month_supplement` as zs where fa_id='%d'", $fa_id);
        return $sql;
    }
     public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }
    public function getCompanyRow($id){	
        $sql = sprintf("SELECT id,company_park_name,company_code,company_approval_symbol FROM `zc_company` where id='%d'",$id);
        return $sql;
    }
    public function getL3User(){	
        $sql = sprintf("SELECT uid FROM `zc_auth_group_access` where group_id='17'");
        return $sql;
    }
    public function insertDeclaredm($data)
    {
        $add_time = time();
        $fa_id = $data['fa_id'];
        $admin_id = $data['admin_id'];
        unset($data['set_time']);
        unset($data['admin_id']);
        unset($data['fa_id']);
        unset($data['set_year']);
        unset($data['set_mon']);
        unset($data['validity_year']);
        unset($data['validity_mon']);
        unset($data['company_id']);
        unset($data['thumb']);
        $sqlArr = [];
        foreach ($data as $key => $value) {
            $sql = sprintf('INSERT INTO zc_swj_report_month(rf_id,item_val,add_time,fa_id)VALUES(%d,\'%s\',%d,%d)', $key, $value, $add_time,$fa_id);
            array_push($sqlArr,$sql);
        }
        return $sqlArr;
    }
    public function insertMonthSupplement($data)
    {
        $fa_id = $data['fa_id'];
        $sql = sprintf('INSERT INTO `zc_form_month_supplement` ( `fa_id`, `275`, `276`, `277`, `281`, `282`, `286`, `287`, `296`, `297`, `299`, `301`) VALUES (%d, \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\')', $fa_id,$data['275'],$data['276'],$data['277'],$data['281'],$data['282'],$data['286'],$data['287'],$data['296'],$data['297'],$data['299'],$data['301']);
         
        return $sql;
    }
    public function insertfiles($data)
    {
        $add_time = time();
        $fa_id = $data['fa_id'];
        $file_path = $data['file_path'];
        $rf_class ="m";
        $sql = sprintf('INSERT INTO zc_swj_upload_files(file_path,rf_class,add_time,fa_id)VALUES(\'%s\',\'%s\',%d,%d)', $file_path, $rf_class, $add_time,$fa_id);

        return $sql;
    }
    //添加时 选择的描述
    public function getDeclaredmRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_swj_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  WHERE rf_class=\'m\' order by order_no asc');
        return $sql;
    }
     //编辑查询数据
    public function getDeclaredmRow($id)
    {
        $sql = sprintf('SELECT zsrm.*,zy.mon,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_month zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d order by order_no asc', $id);
        return $sql;
    }
     //编辑查询数据
    public function getDeclaredmRowExcel($id)
    {
        $sql = sprintf('SELECT zsrm.*,zy.mon,zy.declared_company_id,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_month zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrf.pid is not null and zsrm.fa_id = %d order by order_no asc', $id);
        return $sql;
    }
    public function getCompanyAndMon($id)
    {
        $sql = sprintf('SELECT zy.*,zc.company_park_name from zc_month zy
LEFT JOIN zc_company zc ON zy.declared_company_id = zc.id WHERE zy.id = %d', $id);
        return $sql;
    }
   	public function getDeclaredmRowExcelComment($id)
    {
        $sql = sprintf('SELECT zsrm.*,zy.mon,zy.declared_company_id,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_month zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrf.pid is null and zsrm.fa_id = %d order by order_no asc', $id);
        return $sql;
    }
   

    public function updateDeclaredm($item_val, $id)
    {
        	$sql = sprintf('UPDATE zc_swj_report_month SET item_val=\'%s\' WHERE id=%d',$item_val, $id);
        	return $sql;
    
    }
    public function updateSupplement($data, $fa_id)
    {
        	$sql = sprintf('UPDATE `zc_form_month_supplement` SET `275` = \'%s\', `276` = \'%s\', `277` = \'%s\', `281` = \'%s\', `282` = \'%s\', `286` = \'%s\', `287` = \'%s\', `296` = \'%s\', `297` = \'%s\', `299` = \'%s\', `301` = \'%s\' WHERE `fa_id` = %d',$data['275'],$data['276'],$data['277'],$data['281'],$data['282'],$data['286'],$data['287'],$data['296'],$data['297'],$data['299'],$data['301'], $fa_id);
        	return $sql;
    
    }

    public function checkData($mon,$company_id)
    {

        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_month z where  mon='%s' and declared_company_id=%d",$mon,$company_id);
        return $sql;
    }
    
    public function delFile($ids)
    {
        $sql = sprintf('DELETE FROM zc_swj_upload_files WHERE id IN (%s)', $ids);
        return $sql;
    }
    public function delDeclaredm($ids)
    {
        $sql = sprintf('DELETE FROM zc_month WHERE id IN (%s)', $ids);
        return $sql;
    }

    public function m_tibao($ids,$sb_id){
        $sql = sprintf('UPDATE zc_month SET is_key=1,sb_user = %d,sb_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
    public function getCheckMessage($id){
        $sql = sprintf("SELECT * FROM `zs_check_message` WHERE `type` = 'm' AND id='%d'", $id);
        return $sql;
    }
}