<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\xampp\tongji-master\public/../application/admin\view\statistics\month\index.html";i:1587006356;s:65:"D:\xampp\tongji-master\application\admin\view\layout\default.html";i:1574934466;s:62:"D:\xampp\tongji-master\application\admin\view\common\meta.html";i:1574934466;s:64:"D:\xampp\tongji-master\application\admin\view\common\script.html";i:1574934466;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
        <form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
        <div class="form-group">
				        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报时间</label>
				        <div class="col-xs-12 col-sm-4">
					          <?php echo build_select('mondata[]', $mondata, null, ['id'=>'mondata','class'=>'form-control selectpicker', 'multiple'=>'', 'data-rule'=>'required']); ?>
				         </div>
				    </div>
				            <div class="form-group">
				        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报单位</label>
				            <?php if($admin_company_id == 1): ?>
								<div class="col-xs-12 col-sm-4">
								 <?php echo build_select('companydata[]', $companydata, [], ['id'=>'companydata','class'=>'form-control selectpicker', 'multiple'=>'', 'data-rule'=>'required']); ?>
								</div>
				            <?php else: ?>
								<div class="col-xs-12 col-sm-4">
								 <?php echo build_select('companydata[]', $companydata, [$admin_company_id], ['id'=>'companydata','class'=>'form-control selectpicker', 'multiple'=>'', 'disabled'=>'disabled']); ?>
								</div>
				            <?php endif; ?>
				            <input type="hidden" id="ad_c_id" value="<?php echo $admin_company_id; ?>" />
				    </div>
				            <div class="form-group">
				        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报项目</label>
				        <div class="col-xs-12 col-sm-4">
					         <?php echo build_select('objdata[]', $objdata, null, ['id'=>'objdata','class'=>'form-control selectpicker', 'multiple'=>'', 'data-rule'=>'required']); ?>
				        </div>
				    </div>
					<div class="form-group">
						<label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">是否合计</label>
						<div class="col-xs-12 col-sm-4">
							<select id="iptSelect" class="form-control selectpicker">
								<option value ="0">否</option>
								<option value ="1">是</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">只显示1-本月合计</label>
						<div class="col-xs-12 col-sm-4">
							<select id="oneCnt" class="form-control selectpicker">
								<option value ="0">否</option>
								<option value ="1">是</option>
							</select>
						</div>
					</div>
				     <input type="button" class="btn btn-success btn-embossed" id="submit" value="<?php echo __('Search'); ?>" />
                        <input type="reset" class="btn btn-default btn-embossed" id="reset" value="<?php echo __('Reset'); ?>" />
                        <input type="button" class="btn btn-warning btn-embossed" id="export_excel" value="导出EXCEL" />
				    </form>
            <div class="tab-pane fade active in" id="one">
            
                <div class="widget-body no-padding">
                    <table id="table" class="table table-striped table-bordered table-hover" style="word-break:break-all; word-wrap:break-all;table-layout:fixed"
                           data-operate-del="<?php echo $auth->check('review/proofreadingm/del'); ?>"
                           width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
<script src="/assets/js/number_operation.js"></script>
    </body>
</html>