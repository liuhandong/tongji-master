<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\13 0013
 * Time: 9:36
 */

namespace app\admin\sql;


class proofreadingySql
{
    public function getDeclaredyCount($where)
    {
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_month %s ', $where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_admin sb ON z.sb_user = sb.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 1 ',$where);
        return $sql;
    }

    public function getDeclaredyList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT z.id,a.nickname,sb.nickname AS sb_user_name,c.company_park_name,z.mon,z.add_time,z.is_key,sb_time,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_admin sb ON z.sb_user = sb.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 1 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
	public function getUploadList($fa_id)
    {
        $sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='y' and fa_id=%d",$fa_id);
        return $sql;
    }
	public function getUploadListCount($fa_id)
    {

        $sql = sprintf("SELECT COUNT(*) AS total FROM zc_swj_upload_files z where  rf_class='y' and fa_id=%d",$fa_id);
        return $sql;
    }
    public function getLastYearDeclaredsList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_year` as zs left join zc_swj_report_year as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }
     //添加时 选择的描述
    public function getDeclaredRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_swj_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  WHERE rf_class=\'y\' order by order_no asc');
        return $sql;
    }
    public function getDeclaredy($id){
        $sql = sprintf('SELECT z.*,c.company_park_name FROM `zc_year` z LEFT JOIN zc_company c ON z.declared_company_id = c.id where z.id=%d', $id);
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredyRow($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,z.user_id,c.company_park_name,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
FROM zc_swj_report_year zsrm
LEFT JOIN zc_year z ON zsrm.fa_id = z.id
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }

    public function updateDeclaredy($data)
    {
        $sql = sprintf('UPDATE zc_swj_report_year SET chk_item_val=\'%s\' WHERE id=%d',$data['chk_item_val'], $data['id']);
        return $sql;
    }
     public function m_jiaodui($ids,$chk_id){
        $sql = sprintf('UPDATE zc_year SET is_key=2,chk_user = %d,chk_time = %d WHERE id=%d',$chk_id,time(),$ids);
        return $sql;
    }
    public function backward($ids,$chk_id){
        $sql = sprintf('UPDATE zc_year SET is_key=0,chk_user = %d,chk_time = %d WHERE id=%d',$chk_id,time(),$ids);
        return $sql;
    }
    public function getCheckMessage($id){
        $sql = sprintf("SELECT * FROM `zs_check_message` WHERE `type` = 'y' AND id='%d'", $id);
        return $sql;
    }
    public function updateCheckMessage($data)
    {
        $sql = sprintf("UPDATE zs_check_message SET content='%s' WHERE `type` = 'y' AND id='%d'",$data['content'], $data['id']);
        return $sql;
    }
}