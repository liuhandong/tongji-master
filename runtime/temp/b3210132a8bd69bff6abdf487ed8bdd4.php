<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"D:\xampp\tongji-master\public/../application/admin\view\modular\yearassessmodular\edit.html";i:1629593110;s:65:"D:\xampp\tongji-master\application\admin\view\layout\default.html";i:1574934466;s:62:"D:\xampp\tongji-master\application\admin\view\common\meta.html";i:1574934466;s:64:"D:\xampp\tongji-master\application\admin\view\common\script.html";i:1574934466;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
    <input type="hidden" name="row[id]" value="<?php echo $row['id']; ?>"/>
    <div class="form-group">
        <label for="rf_title" class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('name'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;" id="name" name="row[name]" value="<?php echo $row['name']; ?>" data-rule="required;name" />
        </div>
    </div>
    <div class="form-group">
        <label for="code" class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('seqn'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;" id="seqn" name="row[seqn]" value="<?php echo $row['seqn']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('unitId'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[unit_id]" class="selectpicker" style="width: 150px;">
                <?php foreach($unit_id as $k => $v): ?>
                 <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $row['unit_id']): ?>selected<?php endif; ?>><?php echo $v['unit_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    
    <div class="form-group">
        <label for="code" class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('topic'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;" id="topic" name="row[topic]" value="<?php echo $row['topic']; ?>" />
        </div>
    </div>
    
  
    <!--<div class="form-group">
        <label for="css_class" class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('cssClass'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;"  id="css_class" name="row[css_class]" value="" data-rule="required;css_class" />
        </div>
    </div>
    <div class="form-group">
        <label for="form_type" class="control-label col-xs-12 col-sm-2"><?php echo __('formType'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[form_type]" class="selectpicker" style="width: 150px;">
                <option value="text">文本</option>
                <option value="select">下拉列表</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('numBer'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[number]" class="selectpicker" style="width: 150px;">
                <?php if(is_array($number) || $number instanceof \think\Collection || $number instanceof \think\Paginator): $i = 0; $__LIST__ = $number;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $data; ?>"><?php echo $data; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>-->
    
    <div class="form-group">
        <label for="rf_year" class="control-label col-xs-12 col-sm-2"><font color='red'>*</font><?php echo __('rfYear'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;" id="rf_year" name="row[rf_year]" value="<?php echo $row['rf_year']; ?>" data-rule="required;rf_year" />
        </div>
    </div>
    
      <div class="form-group">
        <label for="code" class="control-label col-xs-12 col-sm-2"><?php echo __('orderNo'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" style="width: 220px;" id="orderNo" name="row[order_no]" value="<?php echo $row['order_no']; ?>" />
        </div>
    </div>
    

    
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('theirGarden'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[their_garden]" class="selectpicker" style="width: 150px;">
                <?php foreach($their_garden as $k => $v): ?>
                <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $row['unit_id']): ?>selected<?php endif; ?>><?php echo $v['company_park_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('list1'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <select name="row[rf_class]" class="selectpicker" style="width: 150px;">
                <?php if(is_array($list1) || $list1 instanceof \think\Collection || $list1 instanceof \think\Paginator): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $data; ?>"><?php echo $data; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="rf_year" class="control-label col-xs-12 col-sm-2"><?php echo __('YearComment'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea name="row[rf_note]" class="form-control"  rows="5" id="rf_note"><?php echo $row['rf_note']; ?></textarea>
        </div>
    </div>
    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>
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