<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\3\22 0022
 * Time: 9:38
 */

namespace app\admin\sql;


class groupingSql
{
    public function getGroupingCount($where){
        $sql = sprintf('SELECT COUNT(*) AS total FROM zc_company %s ',$where);
        return $sql;
    }

    public function getGroupingList($where,$sort,$order,$offset,$limit){
        $sql = sprintf('SELECT * FROM zc_company %s ORDER BY %s %s  LIMIT %d,%d',$where,$sort,$order,$offset,$limit);
        return $sql;
    }
	
    public function insertGrouping($data){
        $data['company_approval_time'] = strtotime($data['company_approval_time']);
        $data['add_time'] = time();
        $sql = sprintf('INSERT INTO zc_company (company_park_name,company_name,company_address,company_code,company_online_code,
company_postal_code,company_telephone,company_fax,company_mailbox,company_website,company_approval_time,company_approval_symbol,
company_approval_acreage,company_administration_acreage,
company_code_one,
company_code_two,
company_code_there,
company_num,
company_country_num,
company_town_num,
company_street_office_num,
company_neighborhood_committee_num,
company_person_in_charge,
add_time,
company_class_code,
company_level_code,
company_approval_authority,
company_09001_certification,company_14001_certification,company_administrative_level)
                        VALUES (\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',%d,\'%s\',\'%s\',\'%s\',\'%s\',%d,\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\',%d,%d,%d,%d,%d,\'%s\',%d,\'%s\',\'%s\',\'%s\',%d,%d,\'%s\')',
            $data['company_park_name'],$data['company_name'],$data['company_address'],
            $data['company_code'],$data['company_online_code'],$data['company_postal_code'],
            $data['company_telephone'],$data['company_fax'],$data['company_mailbox'],$data['company_website'],
            $data['company_approval_time'],$data['company_approval_symbol'],$data['company_approval_acreage'],
            $data['company_administration_acreage'],$data['company_code_one'],$data['company_code_two'],
            $data['company_code_there'],$data['company_num'],$data['company_country_num'],$data['company_town_num'],
            $data['company_street_office_num'],$data['company_neighborhood_committee_num'],$data['company_person_in_charge'],
            $data['add_time'],$data['company_class_code'],$data['company_level_code'],$data['company_approval_authority'],
            $data['company_09001_certification'],$data['company_14001_certification'],$data['company_administrative_level']);
        return $sql;
    }

    public function getGroupingRow($id){
        $sql = sprintf('SELECT * FROM zc_company WHERE id = %d ',$id);
        return $sql;
    }

    public function updateGrouping($data){
        $sql = sprintf('UPDATE zc_company SET company_park_name=\'%s\',company_name=\'%s\',company_address=\'%s\',company_code=\'%s\',company_online_code=\'%s\',
`company_postal_code`=\'%s\',company_telephone=\'%s\',company_fax=\'%s\',company_mailbox=\'%s\',company_website=\'%s\',company_approval_time=\'%s\'
,company_approval_symbol=\'%s\',company_approval_acreage=\'%s\',company_administration_acreage=\'%s\',company_code_one=\'%s\'
,company_code_two=\'%s\'
,company_code_there=\'%s\'
,company_num=\'%s\'
,company_country_num=\'%s\'
,company_town_num=\'%s\'
,company_street_office_num=\'%s\'
,company_neighborhood_committee_num=\'%s\'
,company_person_in_charge=\'%s\'
 WHERE id=%d',$data['company_park_name'],$data['company_name'],$data['company_address'],$data['company_code'],$data['company_online_code'],
            $data['company_postal_code'],$data['company_telephone'],$data['company_fax'],$data['company_mailbox'],$data['company_website']
            ,$data['company_approval_time'],$data['company_approval_symbol'],$data['company_approval_acreage'],$data['company_administration_acreage']
            ,$data['company_code_one']
            ,$data['company_code_two']
            ,$data['company_code_there']
            ,$data['company_num']
            ,$data['company_country_num']
            ,$data['company_town_num']
            ,$data['company_street_office_num']
            ,$data['company_neighborhood_committee_num']
            ,$data['company_person_in_charge']
            ,$data['id']);
        return $sql;
    }

    public function delGrouping($ids){
        $sql = sprintf('DELETE FROM zc_company WHERE id IN (%s)',$ids);
        return $sql;
    }
}