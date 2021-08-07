<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class seasonSql
{
    public function getDeclaredsCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_season z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 3 ',$where);
        return $sql;
    }

    public function getDeclaredsList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_season z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 3 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
    public function getDeclaredExcelList($where){
        $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_season z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s AND z.is_key = 3',$where);
        return $sql;
    }

	 public function getLastYearDeclaredsList($mon,$company_id,$rf_id){
        $sql = sprintf("SELECT * FROM `zc_season` as zs left join zc_swj_report_season as zsr on zs.id=zsr.fa_id WHERE mon='%s' and declared_company_id='%d' and rf_id='%d'", $mon,$company_id,$rf_id);
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredsRow($id)
    {
        $sql = sprintf('SELECT zsrs.*,z.mon,z.declared_company_id,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_season zsrs LEFT JOIN zc_swj_report_form zsrf ON zsrs.rf_id = zsrf.id
LEFT JOIN zc_season z ON zsrs.fa_id = z.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrs.fa_id = %d', $id);
        return $sql;
    }
     public function getUploadList($fa_id)
    {
        $sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='s' and fa_id=%d",$fa_id);
        return $sql;
    }
    public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }
    public function getObjRows(){	
        $sql = sprintf("SELECT id,rf_title,CAST(code AS SIGNED INTEGER) AS SORT FROM `zc_swj_report_form` where `rf_class` = 's' and pid=0 ORDER BY SORT");
        return $sql;
    }

        //编辑查询数据
    public function getDeclaredsRowNew($id)
    {
        $sql = sprintf('SELECT zsrs.id,zsrs.rf_id,zsrs.item_val,
CASE zsrs.chk_item_val WHEN 0 THEN zsrs.item_val ELSE zsrs.chk_item_val END chk_item_val ,
zsrs.add_time,zsrs.fa_id,zsrs.state,zsrs.declare_time,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_season zsrs LEFT JOIN zc_swj_report_form zsrf ON zsrs.rf_id = zsrf.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrs.fa_id = %d', $id);
        return $sql;
    }

     public function backward($ids,$sb_id){
        $sql = sprintf('UPDATE zc_season SET is_key=2,sp_user = %d,sp_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }

    //编辑查询数据
    public function getDeclaredsRowToMsg($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,c.company_park_name,z.user_id,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
		FROM zc_swj_report_season zsrm
		LEFT JOIN zc_season z ON zsrm.fa_id = z.id
		LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
		LEFT JOIN zc_company c ON z.declared_company_id = c.id
		LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
		        return $sql;
    }
    
    public function getRowsCount($searchKey)
    {
        $sql = sprintf('SELECT -1 as id, \'\' AS declared_company_id, \'\' AS nickname, \'合计\' AS company_park_name,
                 \'\' AS mon, \'\' AS add_time, \'\' AS is_key, \'已完成\' AS is_key_name,
                SUM(report_season.124) AS \'124\',
                SUM(report_season.241) AS \'241\',
                SUM(report_season.243) AS \'243\',
                SUM(report_season.244) AS \'244\',
                SUM(report_season.288) AS \'288\',
                SUM(report_season.289) AS \'289\'
                from
                (SELECT
                    fa_id,
                    MAX(CASE WHEN rf_id = 124 THEN item_val END) AS \'124\',
                    MAX(CASE WHEN rf_id = 241 THEN item_val END) AS \'241\',
                    MAX(CASE WHEN rf_id = 243 THEN item_val END) AS \'243\',
                    MAX(CASE WHEN rf_id = 244 THEN item_val END) AS \'244\',
                    MAX(CASE WHEN rf_id = 288 THEN item_val END) AS \'288\',
                    MAX(CASE WHEN rf_id = 289 THEN item_val END) AS \'289\'
                FROM
                    zc_swj_report_season where fa_id IN %s GROUP BY fa_id) report_season',$searchKey);

                    return $sql;
    }

    public function getObjRows_execl(){	
        $sql = sprintf("SELECT t1.id,t1.rf_title,CAST(code AS SIGNED INTEGER) AS SORT,t2.unit_name FROM `zc_swj_report_form` t1
        LEFT JOIN `zc_swj_unit` t2 ON t1.unit_id = t2.id
        where t1.rf_class = 's' and t1.pid=0 ORDER BY SORT");
        return $sql;
    }
}