<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class monthSql
{
    public function getDeclaredmCount($where)
    {
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_month %s ', $where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key = 3 ',$where);
        return $sql;
    }

    public function getDeclaredmList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT z.id,z.declared_company_id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key =3 ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
        public function getDeclaredExcelList($where)
    {
        $sql = sprintf('SELECT z.id,z.declared_company_id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_month z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s AND z.is_key =3 order by z.id desc',$where);
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
     public function getUploadList($fa_id)
    {
        $sql = sprintf("SELECT * FROM zc_swj_upload_files z where  rf_class='m' and  fa_id=%d",$fa_id);
        return $sql;
    }
	public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }
    public function getObjRows(){	
        $sql = sprintf("SELECT id,rf_title,CAST(code AS SIGNED INTEGER) AS SORT FROM `zc_swj_report_form` where `rf_class` = 'm' and pid>0 ORDER BY SORT");
        return $sql;
    }
	public function getMonthSupplement($fa_id){

        $sql = sprintf("SELECT * FROM `zc_form_month_supplement` as zs where fa_id='%d'", $fa_id);
        return $sql;
    }

    //编辑查询数据
    public function getDeclaredmRow($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_month z ON zsrm.fa_id = z.id
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }
    
        //编辑查询数据
    public function getDeclaredmRowNew($id)
    {
        $sql = sprintf('SELECT zsrm.id,zsrm.rf_id,zsrm.item_val,
CASE zsrm.chk_item_val WHEN 0 THEN zsrm.item_val ELSE zsrm.chk_item_val END chk_item_val ,
zsrm.add_time,zsrm.fa_id,zsrm.state,zsrm.declare_time,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_month zsrm
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }

    public function backward($ids,$sb_id){
        $sql = sprintf('UPDATE zc_month SET is_key=2,sp_user = %d,sp_time = %d WHERE id=%d',$sb_id,time(),$ids);
        return $sql;
    }
    
        //编辑查询数据
    public function getDeclaredmRowToMsg($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,c.company_park_name,z.user_id,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
				FROM zc_swj_report_month zsrm
				LEFT JOIN zc_month z ON zsrm.fa_id = z.id
				LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
				LEFT JOIN zc_company c ON z.declared_company_id = c.id
				LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }
    
    public function getRowsCount($searchKey)
    {
        $sql = sprintf('SELECT -1 as id, \'\' AS declared_company_id, \'\' AS nickname, \'合计\' AS company_park_name,
                 \'\' AS mon, \'\' AS add_time, \'\' AS is_key, \'已完成\' AS is_key_name,
                SUM(report_month.131) AS \'131\',
                SUM(report_month.133) AS \'133\',
                SUM(report_month.134) AS \'134\',
                SUM(report_month.136) AS \'136\',
                SUM(report_month.138) AS \'138\',
                SUM(report_month.140) AS \'140\',
                SUM(report_month.142) AS \'142\',
                SUM(report_month.145) AS \'145\',
                SUM(report_month.147) AS \'147\',
                SUM(report_month.148) AS \'148\',
                SUM(report_month.151) AS \'151\',
                SUM(report_month.155) AS \'155\',
                SUM(report_month.156) AS \'156\',
                SUM(report_month.159) AS \'159\',
                SUM(report_month.163) AS \'163\',
                SUM(report_month.165) AS \'165\',
                SUM(report_month.166) AS \'166\',
                SUM(report_month.168) AS \'168\',
                SUM(report_month.170) AS \'170\',
                SUM(report_month.171) AS \'171\',
                SUM(report_month.173) AS \'173\',
                SUM(report_month.177) AS \'177\',
                SUM(report_month.178) AS \'178\',
                SUM(report_month.181) AS \'181\',
                SUM(report_month.183) AS \'183\',
                SUM(report_month.184) AS \'184\',
                SUM(report_month.189) AS \'189\',
                SUM(report_month.191) AS \'191\',
                SUM(report_month.193) AS \'193\',
                SUM(report_month.204) AS \'204\',
                SUM(report_month.206) AS \'206\',
                SUM(report_month.208) AS \'208\',
                SUM(report_month.210) AS \'210\',
                SUM(report_month.273) AS \'273\',
                SUM(report_month.274) AS \'274\',
                SUM(report_month.275) AS \'275\',
                SUM(report_month.276) AS \'276\',
                SUM(report_month.277) AS \'277\',
                SUM(report_month.278) AS \'278\',
                SUM(report_month.279) AS \'279\',
                SUM(report_month.280) AS \'280\',
                SUM(report_month.281) AS \'281\',
                SUM(report_month.282) AS \'282\',
                SUM(report_month.283) AS \'283\',
                SUM(report_month.284) AS \'284\',
                SUM(report_month.285) AS \'285\',
                SUM(report_month.286) AS \'286\',
                SUM(report_month.287) AS \'287\',
                SUM(report_month.296) AS \'296\',
                SUM(report_month.297) AS \'297\',
                SUM(report_month.298) AS \'298\',
                SUM(report_month.299) AS \'299\',
                SUM(report_month.300) AS \'300\',
                SUM(report_month.301) AS \'301\',
                SUM(report_month.303) AS \'303\',
                SUM(report_month.304) AS \'304\'
              FROM
                 (SELECT fa_id,
                        MAX(CASE WHEN rf_id = 131 THEN item_val END) AS \'131\',
                        MAX(CASE WHEN rf_id = 133 THEN item_val END) AS \'133\',
                        MAX(CASE WHEN rf_id = 134 THEN item_val END) AS \'134\',
                        MAX(CASE WHEN rf_id = 136 THEN item_val END) AS \'136\',
                        MAX(CASE WHEN rf_id = 138 THEN item_val END) AS \'138\',
                        MAX(CASE WHEN rf_id = 140 THEN item_val END) AS \'140\',
                        MAX(CASE WHEN rf_id = 142 THEN item_val END) AS \'142\',
                        MAX(CASE WHEN rf_id = 145 THEN item_val END) AS \'145\',
                        MAX(CASE WHEN rf_id = 147 THEN item_val END) AS \'147\',
                        MAX(CASE WHEN rf_id = 148 THEN item_val END) AS \'148\',
                        MAX(CASE WHEN rf_id = 151 THEN item_val END) AS \'151\',
                        MAX(CASE WHEN rf_id = 155 THEN item_val END) AS \'155\',
                        MAX(CASE WHEN rf_id = 156 THEN item_val END) AS \'156\',
                        MAX(CASE WHEN rf_id = 159 THEN item_val END) AS \'159\',
                        MAX(CASE WHEN rf_id = 163 THEN item_val END) AS \'163\',
                        MAX(CASE WHEN rf_id = 165 THEN item_val END) AS \'165\',
                        MAX(CASE WHEN rf_id = 166 THEN item_val END) AS \'166\',
                        MAX(CASE WHEN rf_id = 168 THEN item_val END) AS \'168\',
                        MAX(CASE WHEN rf_id = 170 THEN item_val END) AS \'170\',
                        MAX(CASE WHEN rf_id = 171 THEN item_val END) AS \'171\',
                        MAX(CASE WHEN rf_id = 173 THEN item_val END) AS \'173\',
                        MAX(CASE WHEN rf_id = 177 THEN item_val END) AS \'177\',
                        MAX(CASE WHEN rf_id = 178 THEN item_val END) AS \'178\',
                        MAX(CASE WHEN rf_id = 181 THEN item_val END) AS \'181\',
                        MAX(CASE WHEN rf_id = 183 THEN item_val END) AS \'183\',
                        MAX(CASE WHEN rf_id = 184 THEN item_val END) AS \'184\',
                        MAX(CASE WHEN rf_id = 189 THEN item_val END) AS \'189\',
                        MAX(CASE WHEN rf_id = 191 THEN item_val END) AS \'191\',
                        MAX(CASE WHEN rf_id = 193 THEN item_val END) AS \'193\',
                        MAX(CASE WHEN rf_id = 204 THEN item_val END) AS \'204\',
                        MAX(CASE WHEN rf_id = 206 THEN item_val END) AS \'206\',
                        MAX(CASE WHEN rf_id = 208 THEN item_val END) AS \'208\',
                        MAX(CASE WHEN rf_id = 210 THEN item_val END) AS \'210\',
                        MAX(CASE WHEN rf_id = 273 THEN item_val END) AS \'273\',
                        MAX(CASE WHEN rf_id = 274 THEN item_val END) AS \'274\',
                        MAX(CASE WHEN rf_id = 275 THEN item_val END) AS \'275\',
                        MAX(CASE WHEN rf_id = 276 THEN item_val END) AS \'276\',
                        MAX(CASE WHEN rf_id = 277 THEN item_val END) AS \'277\',
                        MAX(CASE WHEN rf_id = 278 THEN item_val END) AS \'278\',
                        MAX(CASE WHEN rf_id = 279 THEN item_val END) AS \'279\',
                        MAX(CASE WHEN rf_id = 280 THEN item_val END) AS \'280\',
                        MAX(CASE WHEN rf_id = 281 THEN item_val END) AS \'281\',
                        MAX(CASE WHEN rf_id = 282 THEN item_val END) AS \'282\',
                        MAX(CASE WHEN rf_id = 283 THEN item_val END) AS \'283\',
                        MAX(CASE WHEN rf_id = 284 THEN item_val END) AS \'284\',
                        MAX(CASE WHEN rf_id = 285 THEN item_val END) AS \'285\',
                        MAX(CASE WHEN rf_id = 286 THEN item_val END) AS \'286\',
                        MAX(CASE WHEN rf_id = 287 THEN item_val END) AS \'287\',
                        MAX(CASE WHEN rf_id = 296 THEN item_val END) AS \'296\',
                        MAX(CASE WHEN rf_id = 297 THEN item_val END) AS \'297\',
                        MAX(CASE WHEN rf_id = 298 THEN item_val END) AS \'298\',
                        MAX(CASE WHEN rf_id = 299 THEN item_val END) AS \'299\',
                        MAX(CASE WHEN rf_id = 300 THEN item_val END) AS \'300\',
                        MAX(CASE WHEN rf_id = 301 THEN item_val END) AS \'301\',
                        MAX(CASE WHEN rf_id = 303 THEN item_val END) AS \'303\',
                        MAX(CASE WHEN rf_id = 304 THEN item_val END) AS \'304\'
                   FROM zc_swj_report_month where fa_id IN %s GROUP BY fa_id) report_month',$searchKey);

                    return $sql;
    }

    public function getObjRows_execl(){	
        $sql = sprintf("SELECT t1.id,t1.rf_title,CAST(code AS SIGNED INTEGER) AS SORT,t2.unit_name FROM `zc_swj_report_form` t1
        LEFT JOIN `zc_swj_unit` t2 ON t1.unit_id = t2.id
        where t1.rf_class = 'm' and t1.pid>0 ORDER BY SORT");
        return $sql;
    }

}