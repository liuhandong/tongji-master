<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class approvalmSql
{
    public function getDeclaredmCount($where)
    {
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_month %s ', $where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 2 ',$where);
        return $sql;
    }

    public function getDeclaredmList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 2 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
     public function getDeclaredm($id){
        $sql = sprintf('SELECT z.*,c.company_park_name FROM `zc_month` z LEFT JOIN zc_company c ON z.declared_company_id = c.id where z.id=%d', $id);
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
    public function getLastYearDeclaredsList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }
 	public function getTotalList($mon,$company_id,$rf_id){
    	   $tem_arr=explode("-",$mon);
    	   $s_month=$tem_arr['0']."-01";
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon>='%s' and mon<='%s'and declared_company_id='%d' and rf_id='%d'", $s_month,$mon,$company_id,$rf_id);
        return $sql;
    }
	public function getTotalList2($mon,$company_id,$rf_id){
    	   $tem_arr=explode("-",$mon);
    	   $s_month=$tem_arr['0']."-01";
        $sql = sprintf("SELECT * FROM `zc_month` as zs left join zc_swj_report_month as zsr on zs.id=zsr.fa_id WHERE mon>='%s' and mon<'%s'and declared_company_id='%d' and rf_id='%d'", $s_month,$mon,$company_id,$rf_id);
        return $sql;
    }
    //添加时 选择的描述
    public function getDeclaredmRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_swj_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  WHERE rf_class=\'m\'');
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredmRow($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.user_id,z.declared_company_id,c.company_park_name,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_month z ON zsrm.fa_id = z.id
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }

    public function getMonthSupplement($fa_id){

        $sql = sprintf("SELECT * FROM `zc_form_month_supplement` as zs where fa_id='%d'", $fa_id);
        return $sql;
    }
    
     public function getL3User(){	
        $sql = sprintf("SELECT uid FROM `zc_auth_group_access` where group_id='17'");
        return $sql;
    }
    public function m_sp($ids,$sb_id){
        $sql = sprintf('UPDATE zc_month SET is_key=3,sp_user = %d,sp_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
    public function m_nosp($ids,$sb_id){
        $sql = sprintf('UPDATE zc_month SET is_key=1,sp_user = %d,sp_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
    public function getCheckMessage($id){
        $sql = sprintf("SELECT * FROM `zs_check_message` WHERE `type` = 'm' AND id='%d'", $id);
        return $sql;
    }
}