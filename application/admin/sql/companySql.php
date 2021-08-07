<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\7 0007
 * Time: 16:57
 */

namespace app\admin\sql;


class companySql
{
	public function getCompanyCount($where)
    {
        $sql = sprintf('SELECT COUNT(*) AS total FROM `zc_company_upload` zu LEFT JOIN zc_company z ON zu.`company_id`=z.id',$where);
        return $sql;
    }

    public function getCompanyList($where, $sort, $order, $offset, $limit)
    {
        $sql = sprintf('SELECT zu.*,z.company_park_name FROM `zc_company_upload` zu LEFT JOIN zc_company z ON zu.`company_id`=z.id %s ORDER BY %s %s  LIMIT %d,%d',$where, $sort, $order, $offset, $limit);
        return $sql;
    }
    public function insertCompany($data)
    {
        $add_time = time();
        $company_id = $data['company_id'];
        $name = $data['name'];
        $file_path = $data['file_path'];
        
        $sql = sprintf('INSERT INTO zc_company_upload(company_id,name,add_time,file_path)VALUES(%d,\'%s\',%d,\'%s\')', $company_id, $name, $add_time,$file_path);
        return $sql;
    }

	public function getCompanyRows(){	
        $sql = sprintf("SELECT id,company_park_name FROM `zc_company`");
        return $sql;
    }

      public function delCompany($ids)
    {
        $sql = sprintf('DELETE FROM zc_company_upload WHERE id IN (%s)', $ids);
        return $sql;
    }

   
}