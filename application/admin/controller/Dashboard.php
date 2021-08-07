<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\Session;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
    	   $admin = Session::get('admin');
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');

        $titaluser=Db::getOne("SELECT COUNT(*) AS total FROM `zc_admin`");
        $totalmonth=Db::getOne("SELECT COUNT(*) AS total FROM `zc_month`");
        $totalseason=Db::getOne("SELECT COUNT(*) AS total FROM `zc_season`");
        $totalyear=Db::getOne("SELECT COUNT(*) AS total FROM `zc_year`");
        $time=strtotime(date("Y-m-d"));
        $todayuserlogin=Db::getOne("SELECT COUNT(*) AS total from `zc_admin_log` WHERE `title` LIKE '登录' and createtime>'".$time."'");//
	   $declaredm=Db::getOne("SELECT COUNT(*) AS total FROM `zc_month` where sb_time>'".$time."'");
	   $declareds=Db::getOne("SELECT COUNT(*) AS total FROM `zc_season` where sb_time>'".$time."'");
	   $declaredy=Db::getOne("SELECT COUNT(*) AS total FROM `zc_year` where sb_time>'".$time."'");
        $declared_total=$declaredm['total']+$declareds['total']+$declaredy['total'];
        $reviewm=Db::getOne("SELECT COUNT(*) AS total FROM `zc_month` where chk_time>'".$time."'");
	   $reviews=Db::getOne("SELECT COUNT(*) AS total FROM `zc_season` where chk_time>'".$time."'");
	   $reviewy=Db::getOne("SELECT COUNT(*) AS total FROM `zc_year` where chk_time>'".$time."'");
	   $review_total=$reviewm['total']+$reviews['total']+$reviewy['total'];
	   $approvalm=Db::getOne("SELECT COUNT(*) AS total FROM `zc_month` where sp_time>'".$time."'");
	   $approvals=Db::getOne("SELECT COUNT(*) AS total FROM `zc_season` where sp_time>'".$time."'");
	   $approvaly=Db::getOne("SELECT COUNT(*) AS total FROM `zc_year` where sp_time>'".$time."'");
	   $approval_total=$approvalm['total']+$approvals['total']+$approvaly['total'];
	   $mymessage_total=Db::getOne("SELECT COUNT(*) AS total FROM `zc_message` where sender_id='".$admin['id']."'");
	   $message_total=Db::getOne("SELECT COUNT(*) AS total FROM `zc_message`");
        $this->view->assign([
            'totaluser'        => $titaluser['total'],
            'totalmonth'       => $totalmonth['total'],
            'totalseason'       => $totalseason['total'],
            'totalyear' => $totalyear['total'],
            'todayuserlogin'   => $todayuserlogin['total'],
            'declared_total'  => $declared_total,
            'review_total'       => $review_total,
            'approval_total'    => $approval_total,
            'mymessage_total'    => $mymessage_total['total'],
            'message_total'    => $message_total['total'],
            'sevendnu'         => '80%',
            'sevendau'         => '32%',
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'addonversion'       => $addonVersion,
            'uploadmode'       => $uploadmode
        ]);

        return $this->view->fetch();
    }

}
