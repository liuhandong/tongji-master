<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\xampp\tongji-master\public/../application/admin\view\declared\company\index.html";i:1574934466;s:65:"D:\xampp\tongji-master\application\admin\view\layout\default.html";i:1574934466;s:62:"D:\xampp\tongji-master\application\admin\view\common\meta.html";i:1574934466;s:64:"D:\xampp\tongji-master\application\admin\view\common\script.html";i:1574934466;}*/ ?>
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
                                <style type="text/css">
    #searchfloat {position:absolute;top:40px;right:20px;background:#F7F0A0;padding:10px;}
    #saved {position: relative;}
    #saved_sql {position:absolute;bottom:0;height:300px;background:#F7F0A0;width:100%;overflow:auto;display:none;}
    #saved_sql li {display:block;clear:both;width:100%;float:left;line-height:18px;padding:1px 0}
    #saved_sql li a{float:left;text-decoration: none;display:block;padding:0 5px;}
    #saved_sql li i{display:none;float:left;color:#06f;font-size: 14px;font-style: normal;margin-left:2px;line-height:18px;}
    #saved_sql li:hover{background:#fff;}
    #saved_sql li:hover i{display:block;cursor:pointer;}
    #database #tablename {height:205px;width:100%;padding:5px;}
    #database #tablename option{height:18px;}
    #database #subaction {height:210px;width:100%;}
    #database .select-striped > option:nth-of-type(odd) {background-color: #f9f9f9;}
    #database .dropdown-menu ul {margin:-3px 0;}
    #database .dropdown-menu ul li{margin:3px 0;}
    #database .dropdown-menu.row .col-xs-6{padding:0 5px;}
    #sqlquery {font-size:12px;color:#444;}
    #resultparent {padding:5px;}
</style>
<div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="database" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    
                    <div id="backuplist">
                    	<form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
                    	<div class="form-group">
        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报单位</label>
				        <div class="col-xs-12 col-sm-8">
					                    <select name="row[company_id]" class="selectpicker" style="width: 150px;" id="company_select">
					                    <option>请选择填报单位</option>
				
				            </select>
				            
				                    </div>
				    </div>
				    <div class="form-group">
        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报人</label>
				        <div class="col-xs-12 col-sm-8">
					                    <input type="text" class="form-control"  style="width: 300px;"  name="row[name]" value="" data-rule="required" />
				                    </div>
				    </div>
				        <div class="form-group">
				        
				        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报时间</label>
				        <div class="col-xs-12 col-sm-8">
					                    <input type="datetime" class="form-control " style="width: 300px;"  name="row[add_time]" value="<?php echo date('Y-m-d');?>" placeholder="" readonly="readonly" />
				                    </div>
				    </div>
				    <div class="form-group">
							<label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">附件上传</label>
							<div class="col-xs-12 col-sm-8">
									<div class="input-group">
										<input id="c-thumb" class="form-control" size="50" name="row[file_path]" type="text" value=""/>
										<div class="input-group-addon no-border no-padding">
											<span><button type="button" id="plupload-imagethumb" class="btn btn-danger plupload" data-mimetype="application/ms-excel" data-input-id="c-thumb" data-multiple="false" data-preview-id="p-thumb"><i class="fa fa-upload"></i>上传</button></span>
										</div>
										<span class="msg-box n-right"></span>
									</div>
							        
							</div>
					</div>
					    <input type="submit" class="btn btn-success btn-embossed" id="submit" value="<?php echo __('Add'); ?>" />
                        <input type="reset" class="btn btn-default btn-embossed" value="<?php echo __('Reset'); ?>" />
				    </form>
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
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
<script src="/assets/js/number_operation.js"></script>
    </body>
</html>