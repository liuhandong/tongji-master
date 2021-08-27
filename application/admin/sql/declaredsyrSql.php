<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class declaredsyrSql
{
    public function getDeclaredyCount($where){
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_year_assess_report %s ',$where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 0 ',$where);
        return $sql;
    }

    public function getDeclaredyList($where,$sort,$order,$offset,$limit){
        //$sql = sprintf('SELECT zsry.*,zsrf.name,zu.username,zu1.username AS username1 FROM zc_year_assess_report zsry LEFT JOIN zc_chk_report_form zsrf ON zsrf.id=zsry.rf_id LEFT JOIN zc_user zu ON zu.id=zsry.op_user_id LEFT JOIN zc_user zu1 ON zu1.id = zsry.chk_user_id  %s    ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 0 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
     //添加时 选择的描述
    public function getDeclaredmRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_chk_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  WHERE rf_class=\'r\' order by order_no asc');
        return $sql;
    }
     public function getCompanyAndMon($id)
    {
        $sql = sprintf('SELECT zy.*,zc.company_park_name from zc_year zy
LEFT JOIN zc_company zc ON zy.declared_company_id = zc.id WHERE zy.id = %d', $id);
        return $sql;
    }
     public function getUploadListCount($fa_id)
    {

        //$sql = sprintf("SELECT COUNT(*) AS total FROM zc_swj_upload_files z where  rf_class='r' and fa_id=%d",$fa_id);
        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_swj_assess_upload_files z where  rf_class='r' and fa_id=%d",$fa_id);
        return $sql;
    }
	
    public function getUploadList($fa_id)
    {
        //$sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='r' and  fa_id=%d",$fa_id);
        $sql = sprintf("SELECT * FROM zc_swj_assess_upload_files z where  rf_class='r' and  fa_id=%d",$fa_id);
        return $sql;
    }
    public function getDeclaredy($id){
        $sql = sprintf('SELECT z.*,c.company_park_name FROM `zc_year` z LEFT JOIN zc_company c ON z.declared_company_id = c.id where z.id=%d', $id);
        return $sql;
    }
    
    public function getLastYearDeclaredsList($mon,$company_id){
        $sql = sprintf("SELECT * FROM `zc_year` as zs left join zc_year_assess_report as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d'", $mon,$company_id);
        return $sql;
    }
    public function getLastYearList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_year` as zs left join zc_year_assess_report as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }

    public function insertDeclaredsyr($data)
    {
        $add_time = time();
        $fa_id = $data['fa_id'];
        $admin_id = $data['admin_id'];
        unset($data['set_time']);
        unset($data['admin_id']);
        unset($data['fa_id']);
        unset($data['company_id']);
        //print_r($data);exit;
        $sqlArr = [];
        foreach ($data as $key => $value) {
            $sql = sprintf('INSERT INTO zc_year_assess_report(rf_id,item_val,add_time,fa_id)VALUES(%d,\'%s\',%d,%d)', $key, $value, $add_time,$fa_id);
            array_push($sqlArr,$sql);
        }
        return $sqlArr;
    }
    public function insertfiles($data)
    {
        $add_time = time();
        $fa_id = $data['fa_id'];
        $file_path = $data['file_path'];
        $rf_class ="y";
        $sql = sprintf('INSERT INTO zc_swj_upload_files(file_path,rf_class,add_time,fa_id)VALUES(\'%s\',\'%s\',%d,%d)', $file_path, $rf_class, $add_time,$fa_id);

        return $sql;
    }
    //添加时 选择的描述
    public function getDeclaredyRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_chk_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  order by order_no asc');
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredyRow($id)
    {

        //$sql = sprintf('SELECT zsry.*,zsrf.name,zsrf.seqn,zsu.unit_name FROM zc_year_assess_report zsry LEFT JOIN zc_chk_report_form zsrf ON zsry.rf_id = zsrf.id LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.id = %d', $id);
        $sql = sprintf('SELECT zsrm.*,zy.mon,zsrf.pid,zsrf.name,zsrf.rf_note,zsrf.seqn,zsu.unit_name
FROM zc_year_assess_report zsrm
LEFT JOIN zc_chk_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_year zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }
     //编辑查询数据
    public function getDeclaredmRowExcel($id)
    {
        $sql = sprintf('SELECT zsrm.*,zy.mon,zy.declared_company_id,zsrf.pid,zsrf.name,zsrf.rf_note,zsrf.seqn,zsu.unit_name
FROM zc_year_assess_report zsrm
LEFT JOIN zc_chk_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_year zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE  zsrf.pid is not null and zsrm.fa_id = %d', $id);
        return $sql;
    }
   	public function getDeclaredmRowExcelComment($id)
    {
        $sql = sprintf('SELECT zsrm.*,zy.mon,zy.declared_company_id,zsrf.pid,zsrf.name,zsrf.rf_note,zsrf.seqn,zsu.unit_name
FROM zc_year_assess_report zsrm
LEFT JOIN zc_chk_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_year zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE  zsrf.pid is null and zsrm.fa_id = %d', $id);
        return $sql;
    }
     public function getCompanyRow($id){	
        $sql = sprintf("SELECT id,company_park_name,company_code,company_approval_symbol FROM `zc_company` where id='%d'",$id);
        return $sql;
    }
    public function updateDeclaredy($data){
        $sql = sprintf('UPDATE zc_year_assess_report SET item_val=\'%s\' WHERE id=%d',$data['item_val'], $data['id']);
        return $sql;
    }
	 public function delFile($ids)
    {
        $sql = sprintf('DELETE FROM zc_swj_upload_files WHERE id IN (%s)', $ids);
        return $sql;
    }
    public function getL3User(){	
        $sql = sprintf("SELECT uid FROM `zc_auth_group_access` where group_id='17'");
        return $sql;
    }

    public function checkData($mon,$company_id)
    {

        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_year z where  mon='%s' and declared_company_id=%d",$mon,$company_id);
        return $sql;
    }
    public function delDeclaredy($ids){
        $sql = sprintf('DELETE FROM zc_year WHERE id IN (%s)',$ids);
        return $sql;
    }
    public function m_tibao($ids,$sb_id){
        $sql = sprintf('UPDATE zc_year SET is_key=1,sb_user = %d,sb_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
     public function backward($ids,$sb_id){
        $sql = sprintf('UPDATE zc_year SET is_key=0,sp_user = %d,sp_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
    public function getCheckMessage($id){
        $sql = sprintf("SELECT * FROM `zs_check_message` WHERE `type` = 'y' AND id='%d'", $id);
        return $sql;
    }
}