<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class yearSql
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
      $sql = sprintf('SELECT z.id,a.nickname,c.company_park_name,z.mon,z.add_time,z.is_key,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_year z
LEFT JOIN zc_admin a ON z.user_id = a.id
LEFT JOIN zc_company c ON a.company_id = c.id
%s ',$where);//AND z.is_key = 3
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
        $sql = sprintf("SELECT id,rf_title,CAST(code AS SIGNED INTEGER) AS SORT FROM `zc_swj_report_form` where `rf_class` = 'y' and pid>0 ORDER BY SORT");
        return $sql;
    }

    //编辑查询数据
    public function getDeclaredyRow($id)
    {
        //$sql = sprintf('SELECT zsry.*,zsrf.rf_title,zsrf.code,zsu.unit_name FROM zc_swj_report_year zsry LEFT JOIN zc_swj_report_form zsrf ON zsry.rf_id = zsrf.id LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.id = %d', $id);
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,zsrf.pid,zsrf.rf_note,zsrf.rf_title,zsrf.code,zsu.unit_name
FROM zc_swj_report_year zsrm
LEFT JOIN zc_year z ON zsrm.fa_id = z.id
LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }

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

    public function backward($ids,$chk_id){
        $sql = sprintf('UPDATE zc_year SET is_key=2,chk_user = %d,chk_time = %d WHERE id=%d',$chk_id,time(),$ids);
        return $sql;
    }
    //编辑查询数据
    public function getDeclaredyRowToMsg($id)
    {
        $sql = sprintf('SELECT zsrm.*,z.mon,z.declared_company_id,z.user_id,c.company_park_name,zsrf.rf_title,zsrf.pid,zsrf.rf_note,zsrf.code,zsu.unit_name
			FROM zc_swj_report_year zsrm
			LEFT JOIN zc_year z ON zsrm.fa_id = z.id
			LEFT JOIN zc_swj_report_form zsrf ON zsrm.rf_id = zsrf.id
			LEFT JOIN zc_company c ON z.declared_company_id = c.id
			LEFT JOIN zc_swj_unit zsu ON zsu.id = zsrf.unit_id WHERE zsrm.fa_id = %d', $id);
        return $sql;
    }
    

    public function getRowsCount($searchKey)
    {
        $sql = sprintf('SELECT -1 as id, \'\' AS declared_company_id, \'\' AS nickname, \'合计\' AS company_park_name,
                 \'\' AS mon, \'\' AS add_time, \'\' AS is_key, \'已完成\' AS is_key_name,
                SUM(report_year.121) AS \'121\',
                SUM(report_year.122) AS \'122\',
                SUM(report_year.123) AS \'123\',
                SUM(report_year.127) AS \'127\',
                SUM(report_year.129) AS \'129\',
                SUM(report_year.311) AS \'311\',
                SUM(report_year.130) AS \'130\',
                SUM(report_year.132) AS \'132\',
                SUM(report_year.135) AS \'135\',
                SUM(report_year.312) AS \'312\',
                SUM(report_year.137) AS \'137\',
                SUM(report_year.139) AS \'139\',
                SUM(report_year.143) AS \'143\',
                SUM(report_year.144) AS \'144\',
                SUM(report_year.146) AS \'146\',
                SUM(report_year.149) AS \'149\',
                SUM(report_year.150) AS \'150\',
                SUM(report_year.152) AS \'152\',
                SUM(report_year.153) AS \'153\',
                SUM(report_year.154) AS \'154\',
                SUM(report_year.157) AS \'157\',
                SUM(report_year.158) AS \'158\',
                SUM(report_year.160) AS \'160\',
                SUM(report_year.162) AS \'162\',
                SUM(report_year.164) AS \'164\',
                SUM(report_year.167) AS \'167\',
                SUM(report_year.169) AS \'169\',
                SUM(report_year.172) AS \'172\',
                SUM(report_year.174) AS \'174\',
                SUM(report_year.176) AS \'176\',
                SUM(report_year.179) AS \'179\',
                SUM(report_year.180) AS \'180\',
                SUM(report_year.182) AS \'182\',
                SUM(report_year.185) AS \'185\',
                SUM(report_year.187) AS \'187\',
                SUM(report_year.188) AS \'188\',
                SUM(report_year.190) AS \'190\',
                SUM(report_year.192) AS \'192\',
                SUM(report_year.194) AS \'194\',
                SUM(report_year.196) AS \'196\',
                SUM(report_year.197) AS \'197\',
                SUM(report_year.200) AS \'200\',
                SUM(report_year.201) AS \'201\',
                SUM(report_year.203) AS \'203\',
                SUM(report_year.313) AS \'313\',
                SUM(report_year.314) AS \'314\',
                SUM(report_year.315) AS \'315\',
                SUM(report_year.316) AS \'316\',
                SUM(report_year.205) AS \'205\',
                SUM(report_year.207) AS \'207\',
                SUM(report_year.209) AS \'209\',
                SUM(report_year.211) AS \'211\',
                SUM(report_year.213) AS \'213\',
                SUM(report_year.214) AS \'214\',
                SUM(report_year.215) AS \'215\',
                SUM(report_year.216) AS \'216\',
                SUM(report_year.218) AS \'218\',
                SUM(report_year.220) AS \'220\',
                SUM(report_year.221) AS \'221\',
                SUM(report_year.222) AS \'222\',
                SUM(report_year.223) AS \'223\',
                SUM(report_year.224) AS \'224\',
                SUM(report_year.227) AS \'227\',
                SUM(report_year.317) AS \'317\',
                SUM(report_year.229) AS \'229\',
                SUM(report_year.228) AS \'228\',
                SUM(report_year.230) AS \'230\',
                SUM(report_year.231) AS \'231\',
                SUM(report_year.226) AS \'226\',
                SUM(report_year.232) AS \'232\',
                SUM(report_year.318) AS \'318\',
                SUM(report_year.233) AS \'233\',
                SUM(report_year.234) AS \'234\',
                SUM(report_year.235) AS \'235\',
                SUM(report_year.236) AS \'236\',
                SUM(report_year.238) AS \'238\',
                SUM(report_year.239) AS \'239\',
                SUM(report_year.242) AS \'242\',
                SUM(report_year.319) AS \'319\',
                SUM(report_year.245) AS \'245\',
                SUM(report_year.246) AS \'246\',
                SUM(report_year.322) AS \'322\',
                SUM(report_year.247) AS \'247\',
                SUM(report_year.248) AS \'248\'
              FROM
                 (SELECT fa_id,
                        MAX(CASE WHEN rf_id = 121 THEN item_val END) AS \'121\',
                        MAX(CASE WHEN rf_id = 122 THEN item_val END) AS \'122\',
                        MAX(CASE WHEN rf_id = 123 THEN item_val END) AS \'123\',
                        MAX(CASE WHEN rf_id = 127 THEN item_val END) AS \'127\',
                        MAX(CASE WHEN rf_id = 129 THEN item_val END) AS \'129\',
                        MAX(CASE WHEN rf_id = 311 THEN item_val END) AS \'311\',
                        MAX(CASE WHEN rf_id = 130 THEN item_val END) AS \'130\',
                        MAX(CASE WHEN rf_id = 132 THEN item_val END) AS \'132\',
                        MAX(CASE WHEN rf_id = 135 THEN item_val END) AS \'135\',
                        MAX(CASE WHEN rf_id = 312 THEN item_val END) AS \'312\',
                        MAX(CASE WHEN rf_id = 137 THEN item_val END) AS \'137\',
                        MAX(CASE WHEN rf_id = 139 THEN item_val END) AS \'139\',
                        MAX(CASE WHEN rf_id = 143 THEN item_val END) AS \'143\',
                        MAX(CASE WHEN rf_id = 144 THEN item_val END) AS \'144\',
                        MAX(CASE WHEN rf_id = 146 THEN item_val END) AS \'146\',
                        MAX(CASE WHEN rf_id = 149 THEN item_val END) AS \'149\',
                        MAX(CASE WHEN rf_id = 150 THEN item_val END) AS \'150\',
                        MAX(CASE WHEN rf_id = 152 THEN item_val END) AS \'152\',
                        MAX(CASE WHEN rf_id = 153 THEN item_val END) AS \'153\',
                        MAX(CASE WHEN rf_id = 154 THEN item_val END) AS \'154\',
                        MAX(CASE WHEN rf_id = 157 THEN item_val END) AS \'157\',
                        MAX(CASE WHEN rf_id = 158 THEN item_val END) AS \'158\',
                        MAX(CASE WHEN rf_id = 160 THEN item_val END) AS \'160\',
                        MAX(CASE WHEN rf_id = 162 THEN item_val END) AS \'162\',
                        MAX(CASE WHEN rf_id = 164 THEN item_val END) AS \'164\',
                        MAX(CASE WHEN rf_id = 167 THEN item_val END) AS \'167\',
                        MAX(CASE WHEN rf_id = 169 THEN item_val END) AS \'169\',
                        MAX(CASE WHEN rf_id = 172 THEN item_val END) AS \'172\',
                        MAX(CASE WHEN rf_id = 174 THEN item_val END) AS \'174\',
                        MAX(CASE WHEN rf_id = 176 THEN item_val END) AS \'176\',
                        MAX(CASE WHEN rf_id = 179 THEN item_val END) AS \'179\',
                        MAX(CASE WHEN rf_id = 180 THEN item_val END) AS \'180\',
                        MAX(CASE WHEN rf_id = 182 THEN item_val END) AS \'182\',
                        MAX(CASE WHEN rf_id = 185 THEN item_val END) AS \'185\',
                        MAX(CASE WHEN rf_id = 187 THEN item_val END) AS \'187\',
                        MAX(CASE WHEN rf_id = 188 THEN item_val END) AS \'188\',
                        MAX(CASE WHEN rf_id = 190 THEN item_val END) AS \'190\',
                        MAX(CASE WHEN rf_id = 192 THEN item_val END) AS \'192\',
                        MAX(CASE WHEN rf_id = 194 THEN item_val END) AS \'194\',
                        MAX(CASE WHEN rf_id = 196 THEN item_val END) AS \'196\',
                        MAX(CASE WHEN rf_id = 197 THEN item_val END) AS \'197\',
                        MAX(CASE WHEN rf_id = 200 THEN item_val END) AS \'200\',
                        MAX(CASE WHEN rf_id = 201 THEN item_val END) AS \'201\',
                        MAX(CASE WHEN rf_id = 203 THEN item_val END) AS \'203\',
                        MAX(CASE WHEN rf_id = 313 THEN item_val END) AS \'313\',
                        MAX(CASE WHEN rf_id = 314 THEN item_val END) AS \'314\',
                        MAX(CASE WHEN rf_id = 315 THEN item_val END) AS \'315\',
                        MAX(CASE WHEN rf_id = 316 THEN item_val END) AS \'316\',
                        MAX(CASE WHEN rf_id = 205 THEN item_val END) AS \'205\',
                        MAX(CASE WHEN rf_id = 207 THEN item_val END) AS \'207\',
                        MAX(CASE WHEN rf_id = 209 THEN item_val END) AS \'209\',
                        MAX(CASE WHEN rf_id = 211 THEN item_val END) AS \'211\',
                        MAX(CASE WHEN rf_id = 213 THEN item_val END) AS \'213\',
                        MAX(CASE WHEN rf_id = 214 THEN item_val END) AS \'214\',
                        MAX(CASE WHEN rf_id = 215 THEN item_val END) AS \'215\',
                        MAX(CASE WHEN rf_id = 216 THEN item_val END) AS \'216\',
                        MAX(CASE WHEN rf_id = 218 THEN item_val END) AS \'218\',
                        MAX(CASE WHEN rf_id = 220 THEN item_val END) AS \'220\',
                        MAX(CASE WHEN rf_id = 221 THEN item_val END) AS \'221\',
                        MAX(CASE WHEN rf_id = 222 THEN item_val END) AS \'222\',
                        MAX(CASE WHEN rf_id = 223 THEN item_val END) AS \'223\',
                        MAX(CASE WHEN rf_id = 224 THEN item_val END) AS \'224\',
                        MAX(CASE WHEN rf_id = 227 THEN item_val END) AS \'227\',
                        MAX(CASE WHEN rf_id = 317 THEN item_val END) AS \'317\',
                        MAX(CASE WHEN rf_id = 229 THEN item_val END) AS \'229\',
                        MAX(CASE WHEN rf_id = 228 THEN item_val END) AS \'228\',
                        MAX(CASE WHEN rf_id = 230 THEN item_val END) AS \'230\',
                        MAX(CASE WHEN rf_id = 231 THEN item_val END) AS \'231\',
                        MAX(CASE WHEN rf_id = 226 THEN item_val END) AS \'226\',
                        MAX(CASE WHEN rf_id = 232 THEN item_val END) AS \'232\',
                        MAX(CASE WHEN rf_id = 318 THEN item_val END) AS \'318\',
                        MAX(CASE WHEN rf_id = 233 THEN item_val END) AS \'233\',
                        MAX(CASE WHEN rf_id = 234 THEN item_val END) AS \'234\',
                        MAX(CASE WHEN rf_id = 235 THEN item_val END) AS \'235\',
                        MAX(CASE WHEN rf_id = 236 THEN item_val END) AS \'236\',
                        MAX(CASE WHEN rf_id = 238 THEN item_val END) AS \'238\',
                        MAX(CASE WHEN rf_id = 239 THEN item_val END) AS \'239\',
                        MAX(CASE WHEN rf_id = 242 THEN item_val END) AS \'242\',
                        MAX(CASE WHEN rf_id = 319 THEN item_val END) AS \'319\',
                        MAX(CASE WHEN rf_id = 245 THEN item_val END) AS \'245\',
                        MAX(CASE WHEN rf_id = 246 THEN item_val END) AS \'246\',
                        MAX(CASE WHEN rf_id = 322 THEN item_val END) AS \'322\',
                        MAX(CASE WHEN rf_id = 247 THEN item_val END) AS \'247\',
                        MAX(CASE WHEN rf_id = 248 THEN item_val END) AS \'248\'
                   FROM zc_swj_report_year where fa_id IN %s GROUP BY fa_id) report_year',$searchKey);
                    return $sql;
        
        
    }

    public function getObjRows_execl(){	
        $sql = sprintf("SELECT t1.id,t1.rf_title,CAST(code AS SIGNED INTEGER) AS SORT,t2.unit_name FROM `zc_swj_report_form` t1
        LEFT JOIN `zc_swj_unit` t2 ON t1.unit_id = t2.id
        where t1.rf_class = 'y' and t1.pid>0 ORDER BY SORT");
        return $sql;
    }

}