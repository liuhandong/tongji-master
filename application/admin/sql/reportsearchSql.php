<?php


namespace app\admin\sql;


class reportsearchSql
{
     public function getReportCount($where)
    {
        //$sql = sprintf('SELECT COUNT(*) AS total FROM zc_swj_report_month %s ', $where);
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_report_all %s',$where);
        return $sql;
    }

    public function getReportList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT z.id,c.company_park_name,z.mon,z.add_time,z.is_key,z.rf_class,
CASE z.is_key WHEN 0 THEN \'待申报\' WHEN 1 THEN \'待校对\' WHEN 2 THEN \'待审批\' WHEN 3 THEN \'已完成\' ELSE \'已完成\' END is_key_name
FROM zc_report_all z
LEFT JOIN zc_company c ON z.declared_company_id = c.id
%s  ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
    public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }
   

}