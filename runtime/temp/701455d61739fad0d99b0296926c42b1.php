<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:84:"D:\xampp\tongji-master\public/../application/admin\view\declared\declaredm\edit.html";i:1587373193;s:65:"D:\xampp\tongji-master\application\admin\view\layout\default.html";i:1574934466;s:62:"D:\xampp\tongji-master\application\admin\view\common\meta.html";i:1574934466;s:64:"D:\xampp\tongji-master\application\admin\view\common\script.html";i:1574934466;}*/ ?>
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
                                <form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
<input type="hidden" value="<?php echo $ids; ?>" id="edit_id">
<?php
        	$st=date("Y")-2;
        	$ed=date("Y")+2;
	foreach($row as $k => $v)
	{
		if($v['pid']=='')
		{
			$temp[$v['rf_id']]['id']=$v['id'];
			$temp[$v['rf_id']]['item_val']=$v['item_val'];
		}
	}
	$validity_arr=explode("-",$temp['306']['item_val']);
	$nd=$validity_arr['0'];	
	$validity_mon=$validity_arr['1'];
?>
	
	    <div class="form-group">
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title=""  style="width: 150px;">有限期至</label>
        <div class="col-xs-12 col-sm-4">
	        	         <select name="validity_year" class="selectpicker" style="width: 150px;" id="validity_year">
                <?php
            	for($i=$st;$i<=$ed;$i++)
            	{
            	?>
                <option value="<?php echo $i;?>" <?php if($i==$nd){ echo "selected";}?>><?php echo $i;?>年</option>
                <?php
                }
                ?>
            </select>&nbsp;&nbsp;
            <select name="validity_mon" class="selectpicker" style="width: 150px;" id="validity_mon">
                <option value="01" <?php if("01"==$validity_mon){ echo "selected";}?>>01月</option>
                <option value="02" <?php if("02"==$validity_mon){ echo "selected";}?>>02月</option>
                <option value="03" <?php if("03"==$validity_mon){ echo "selected";}?>>03月</option>
                <option value="04" <?php if("04"==$validity_mon){ echo "selected";}?>>04月</option>
                <option value="05" <?php if("05"==$validity_mon){ echo "selected";}?>>05月</option>
                <option value="06" <?php if("06"==$validity_mon){ echo "selected";}?>>06月</option>
                <option value="07" <?php if("07"==$validity_mon){ echo "selected";}?>>07月</option>
                <option value="08" <?php if("08"==$validity_mon){ echo "selected";}?>>08月</option>
                <option value="09" <?php if("09"==$validity_mon){ echo "selected";}?>>09月</option>
                <option value="10" <?php if("10"==$validity_mon){ echo "selected";}?>>10月</option>
                <option value="11" <?php if("11"==$validity_mon){ echo "selected";}?>>11月</option>
                <option value="12" <?php if("12"==$validity_mon){ echo "selected";}?>>12月</option>
            </select>&nbsp;&nbsp;
	                </div>
	                <input type="hidden" class="form-control"  style="width: 300px;" id="validity" name="row[<?php echo $temp['306']['id'];?>]" value="<?php echo $temp['306']['item_val'];?>"/>
    </div>
        <div class="form-group">
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">单位负责人</label>
        <div class="col-xs-12 col-sm-2">
	                    <input type="text" class="form-control"  style="width: 300px;"  name="row[<?php echo $temp['307']['id'];?>]" value="<?php echo $temp['307']['item_val'];?>"/>
                    </div>
    </div>
        <div class="form-group">
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">填报人</label>
        <div class="col-xs-12 col-sm-2">
	                    <input type="text" class="form-control"  style="width: 300px;"  name="row[<?php echo $temp['308']['id'];?>]" value="<?php echo $temp['308']['item_val'];?>"/>
                    </div>
    </div>
        <div class="form-group">
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">报出日期</label>
        <div class="col-xs-12 col-sm-2">
	                    <input type="datetime" class="form-control" style="width: 300px;"  name="row[<?php echo $temp['309']['id'];?>]" value="<?php echo $temp['309']['item_val'];?>" placeholder=""  readonly="readonly">
                    </div>
    </div>
        <div class="form-group">
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-2" title="" style="width: 150px;">说明</label>
        <div class="col-xs-12 col-sm-4">
	                    <input type="text" class="form-control"  style="width: 300px;"  name="row[<?php echo $temp['310']['id'];?>]" value="<?php echo $temp['310']['item_val'];?>"/>
                    </div>
    </div>
    <div class="row headerBackground">
        <div class="col-sm-3">指标</div>
        <div class="col-sm-1">代码</div>
        <div class="col-sm-2">单位</div>
        <div class="col-sm-2">本月</div>
        <div class="col-sm-2">1-本月</div>
        <div class="col-sm-1">上年同期</div>
        <div class="col-sm-1">同比增长</div>
        <!--<div class="col-sm-2">5</div>-->
    </div>
    <?php foreach($row as $k => $v): ?>
    <input type="hidden" id="declareds_time" value="<?php echo $v['mon']; ?>">
    
        <?php if($v['pid'] == '0'): ?>
		<div class="form-group">
        <label for="rf_title" class="control-label col-xs-12 col-sm-3" title="<?php echo $v['rf_note']; ?>"><b><?php echo $v['rf_title']; ?></b></label>
        <label for="rf_title" class="control-label col-xs-12 col-sm-1"></label>
        <label class="control-label col-xs-12 col-sm-2"></label>
         <label for="rf_title" class="control-label col-xs-12 col-sm-2"></label>
        <label for="rf_title" class="control-label col-xs-12 col-sm-2"></label>
        <label for="rf_title" class="control-label col-xs-12 col-sm-2"></label>
        </div>
        <?php elseif($v['pid'] == ''): else: ?>
        <div class="form-group">
         <?php
         $rf_title=$v['rf_title'];
         $num=intval(substr($rf_title,0,3));
         $title=explode($num,$rf_title);
         ?>
        <label for="rf_title" class="control-label col-xs-12 col-sm-3" title="<?php echo $v['rf_note']; ?>"><?php 
        	if($num==0)
       	{
       		echo $v['rf_title'];
       	}
        	else
        	{
        		for($i=0;$i<$num;$i++)
        		{
        			echo "&nbsp;";
        		}
        		echo $title[1];
        	}
        
        
        ?></label>
        <label for="rf_title" class="control-label col-xs-12 col-sm-1"><?php echo $v['code']; ?></label>
        <label class="control-label col-xs-12 col-sm-2"><?php echo $v['unit_name']; ?></label>
        <div class="col-xs-12 col-sm-2">
            <?php
            $month_arr=explode("-",$v['mon']);
            $month=$month_arr[1];
        	  if($v['code']=="10" or $v['code']=="11" or $v['code']=="16" or $v['code']=="17")
        	  {
        	  	if($month=="03" or $month=="06"  or $month=="09" or $month=="12")
        	  	{
        	  	?>
        	  	<input type="text" class="form-control" style="width: 100px;" id="month_<?php echo $v['rf_id']; ?>" onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" onafterpaste="this.value=this.value.replace(/[^\-?\d.]/g,'')" name="row[<?php echo $v['id']; ?>]" value="<?php echo $v['item_val']; ?>"/>
        	  <?php
        	  	}
        	  	else
        	  	{
        	  ?>
        	   		<input type="text" class="form-control" style="width: 100px;" id="month_<?php echo $v['rf_id']; ?>" onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" onafterpaste="this.value=this.value.replace(/[^\-?\d.]/g,'')" name="row[<?php echo $v['id']; ?>]" value="<?php echo $v['item_val']; ?>"  readonly="readonly"/>
        	  <?php
        	  	}
        	  }
        	  else
        	  {
        	  ?>
           	<input type="text" class="form-control" style="width: 100px;" id="month_<?php echo $v['rf_id']; ?>" onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" onafterpaste="this.value=this.value.replace(/[^\-?\d.]/g,'')" name="row[<?php echo $v['id']; ?>]" value="<?php echo $v['item_val']; ?>"/>
            <?php
            }
            ?>
        </div>
         <?php
        	  if($v['code']=="8" or $v['code']=="25" or $v['code']=="26" or $v['code']=="32" or $v['code']=="33" or $v['code']=="37" or $v['code']=="38" or $v['code']=="40" or $v['code']=="41" or $v['code']=="43" or $v['code']=="45")
        	  {
        	  ?>
        		 <div class="col-xs-12 col-sm-2">
        		<input type="text" class="form-control" style="width: 100px;"  onkeyup="this.value=this.value.replace(/[^\-?\d.]/g,'')" onafterpaste="this.value=this.value.replace(/[^\-?\d.]/g,'')" name="row2[<?php echo $v['rf_id']; ?>]" value="<?php echo $v[$v['rf_id']];?>"/>
        		</div>
        	   <?php
        	  }
        	  else
        	  {
        	  ?>
        	     <label for="rf_title" class="control-label col-xs-12 col-sm-2" id="total_<?php echo $v['rf_id']; ?>"><?php echo $v['total']; ?></label>
        <?php
        	  }
        ?>
        
        <label for="rf_title" class="control-label col-xs-12 col-sm-1" id="lastyear_<?php echo $v['rf_id']; ?>"></label>
        <label for="rf_title" class="control-label col-xs-12 col-sm-1" id="growth_<?php echo $v['rf_id']; ?>"></label>
        </div>
        <?php endif; ?>
        <!--<label class="control-label col-xs-12 col-sm-2">同比增长</label>-->
    
    <?php endforeach; ?>
	 <div class="form-group">
		<label for="c-thumb" class="control-label col-xs-12 col-sm-2">附件上传:</label>
		<div class="col-xs-12 col-sm-8">
				<div class="input-group">
					<input id="c-thumb" class="form-control" size="50" name="row[thumb]" type="text" value="" />
					<div class="input-group-addon no-border no-padding">
						<span><button type="button" id="plupload-imagethumb" class="btn btn-danger plupload" data-input-id="c-thumb" data-multiple="true" data-preview-id="p-thumb"><i class="fa fa-upload"></i>上传</button></span>
					</div>
					<span class="msg-box n-right"></span>
				</div>
		        <ul class="row list-inline plupload-preview" id="p-thumb"></ul>
		</div>
</div>
	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-2" >校验员意见</label>
		<div class="col-xs-12 col-sm-8">
		    <textarea rows="4" cols="120" name="" disabled="disabled"><?php echo $checkIdea; ?></textarea>
		</div>
	</div>
	<?php if($current_company_id == $project_company_id): ?>
    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
    <?php endif; ?>
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