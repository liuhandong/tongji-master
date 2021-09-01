<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\12 0012
 * Time: 9:31
 */

namespace app\admin\sql;


class yearrankingSql
{
     public function getDeclaredyCount($where){
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_year %s ',$where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 3 ',$where);
        return $sql;
    }

    public function getDeclaredyList($where,$sort,$order,$offset,$limit){
        //$sql = sprintf('SELECT zsry.*,zsrf.rf_title,zu.username,zu1.username AS username1 FROM zc_swj_report_year zsry LEFT JOIN zc_swj_report_form zsrf ON zsrf.id=zsry.rf_id LEFT JOIN zc_user zu ON zu.id=zsry.op_user_id LEFT JOIN zc_user zu1 ON zu1.id = zsry.chk_user_id  %s    ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 3 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
    public function getDeclaredExcelList($where){
      $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.declared_company_id,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s ',$where);
        return $sql;
    }
    public function getUploadList($fa_id)
    {
        $sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='y' and fa_id=%d",$fa_id);
        return $sql;
    }

    public function getLastYearDeclaredsList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_year` as zs left join zc_swj_report_year as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }
    public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }
    public function getObjRows(){	
        $sql = sprintf("SELECT id,rf_title FROM `zc_swj_report_form` where `rf_class` = 'y' and pid>0");
        return $sql;
    }

    //编辑查询数据
//    public function getDeclaredyRow($id)
//    {
//        //$sql = sprintf('SELECT zsry.*,zsrf.rf_title,zsrf.code,zsu.unit_name FROM zc_swj_report_year zsry LEFT JOIN zc_swj_report_form zsrf ON zsry.rf_id = zsrf.id LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.id = %d', $id);
//        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
//FROM zc_swj_report_year zsrm
//LEFT JOIN zc_year z ON zsrm.fa_id = z.id
//LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
//LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
//        return $sql;
//    }

    //编辑查询数据
    public function getDeclaredyRowNew($id)
    {
        $sql = sprintf('SELECT zsry.id,zsry.rf_id,zsry.item_val,
CASE zsry.chk_item_val WHEN 0 THEN zsry.item_val ELSE zsry.chk_item_val END chk_item_val ,
zsry.add_time,zsry.fa_id,zsry.state,zsry.declare_time,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_year  zsry
LEFT JOIN zc_swj_report_form zsrf ON zsry.rf_id = zsrf.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  WHERE zsry.fa_id = %d', $id);
        return $sql;
    }
    
    public function getDeclaredy($id){
        $sql = sprintf('SELECT z.*,c.company_park_name FROM `zc_year` z LEFT JOIN zc_company c ON z.declared_company_id = c.id where z.id=%d', $id);
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredyRow($id)
    {

        //$sql = sprintf('SELECT zsry.*,zsrf.name,zsrf.seqn,zsu.unit_name FROM zc_year_assess_report zsry LEFT JOIN zc_chk_report_form zsrf ON zsry.rf_id = zsrf.id LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.id = %d', $id);
        $sql = sprintf('SELECT zsrm.*,zy.mon,zsrf.pid,zsrf.name,zsrf.rf_note,zsrf.seqn,zsu.unit_name,zcc.company_park_name
FROM zc_year_assess_report zsrm
LEFT JOIN zc_chk_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_company zcc ON zsrf.their_garden = zcc.id
LEFT JOIN zc_year zy ON zy.id = zsrm.fa_id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = (select fa_id from zc_year_assess_report group by fa_id limit 1 )
ORDER BY zsrm.rf_id
', $id);
        return $sql;
    }
    public function getCheckMessage($id){
        $sql = sprintf("SELECT * FROM `zs_check_message` WHERE `type` = 'y' AND id='%d'", $id);
        return $sql;
    }
        //添加时 选择的描述
    public function getDeclaredyRows()
    {
        $sql = sprintf('SELECT zsrf.*,zsu.unit_name FROM zc_chk_report_form zsrf LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id  order by order_no asc');
        return $sql;
    }
}