define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'declared/declaredsyr/index',
                    add_url: 'declared/declaredsyr/add',
                }
            });

            var table = $("#table");

            //在表格内容渲染完成后回调的事件


            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                showToggle: false,//切换格式
                showColumns: false,//列
                commonSearch: true,//检索
                showExport: false,//导出
                exportTypes: [ 'excel'],
                columns: [
                    [
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'company_park_name',  title:'填报单位',operate: 'LIKE',sortable: true},
                        {field: 'nickname', title:'创建人',operate: false,sortable: true},
                        {field: 'mon', title: '报表年份',operate: false,sortable: true},
                        {field: 'add_time', title: '创建时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange',sortable: true},
                        {field: 'is_key_name', title: '状态', operate: false},
                        {field: 'file', title: '附件', operate: false, formatter: Controller.api.formatter.icon},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'kaohe_ed',
                                text: '查看附件',
                                icon: 'fa fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredsyr/uploadlist'

                            },{
                                name: 'exam',
                                text: '提交填报',
                                icon: 'fa fa-list',
                                classname: 'btn btn-xs btn-warning btn-ajax',
                                url: 'declared/declaredsyr/tibao',
                                confirm: '确认提报?',
                                success: function (data){
                                    setTimeout(function(){location.reload()},1000);
                                },
                                error: function (data) {
                                    //return false;
                                }
                            },{
                                name: 'kaohe_ed',
                                text: '导出EXCEL',
                                icon: 'fa fa-file',
                                classname: 'btn btn-xs btn-warning',
                                url: 'declared/declaredsyr/print_excel'

                            },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa fa-pencil',
                                classname: 'btn btn-xs btn-success btn-dialog',
                                url: 'declared/declaredsyr/edit'

                            },{
                                name: 'kaohe_ed',
                                text: '',
                                icon: 'fa fa-trash',
                                classname: 'btn btn-xs btn-danger btn-ajax',
                                url: 'declared/declaredsyr/del',
                                confirm: '确认删除?',
                                success: function (data){
                                    setTimeout(function(){location.reload()},1000);
                                },
                                error: function (data) {
                                    //return false;
                                }

                            }],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
         uploadlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'declared/declaredsyr/uploadlist?fa_id='+$('#fa_id').val(),
                    del_url: 'declared/declaredsyr/filedel',
                }
            });

            var table = $("#table");

            //在表格内容渲染完成后回调的事件


            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                showToggle: false,//切换格式
                showColumns: false,//列
                commonSearch: false,//检索
                showExport: false,//导出
                exportTypes: [ 'excel'],
                columns: [
                    [
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'file_name',  title:'文件名',operate: 'LIKE'},
                        {field: 'file_path', title: '文件路径', formatter: Table.api.formatter.url},
                        {field: 'add_time', title: '创建时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            /*buttons: [{
                                name: 'kaohe_ed',
                                text: '下载',
                                icon: 'fa fa-download',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredm/download',
                            },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa  fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredm/view'

                            }],*/
                            
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {

        	$.ajax({url:"declared/declaredm/getcompanydata",success:function(result){
			  		
				        $.each(result, function(i,val){     
				        	/*if(val.flg==1)
				        	{
				        		$('#company_select').append("<option value='"+val.id+"' selected>"+val.company_park_name+"</option>");
			        		}
			        		else
			        		{
				      		$('#company_select').append("<option value='"+val.id+"'>"+val.company_park_name+"</option>");
			        		}*/
					      $('#company_select').append("<option value='"+val.id+"'>"+val.company_park_name+"</option>");
					  });   
			        	
			    }});
		  $.ajax({url:"declared/declaredsyr/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	
				     	$("#month_"+val.id).blur(function(){
				     		if(Number($("#month_"+val.id).val())==0)
			     			{
			     				var growth="";
			     			}
			     			else
			     			{
			     				if(Number($("#lastyear_"+val.id).text())==0)
			     				{
			     					var growth="";
			     				}
			     				else
			     				{
			     					deviation=Number(Number($("#month_"+val.id).val())-Number($("#lastyear_"+val.id).text()))/Math.abs(Number($("#lastyear_"+val.id).text()))*100;
			     					var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
			     				}
			     			}
			     			
			     			$('#growth_'+val.id).html(growth);
						  	
						});
				     }
				  });   
			        	
			    }});
        	$.ajax({url:"declared/declaredsyr/getlastyeardata?mon="+$('#declareds_time').val()+"&company_id="+$('#company_select').val(),success:function(result){
        		//alert($('#company_select').val());
			  		if(result=="")
			        	{
			        		$('.lastyear_s').html("");	
			        	}
			        	else
			        	{
				        $.each(result, function(i,val){     
				        	
					      	$('#lastyear_'+val.rf_id).html(val.item_val);
				        	
					  });   
			        	}
			    }});
        	   $('#declareds_time').on("change",function (obj) {
			  $.ajax({url:"declared/declaredsyr/getlastyeardata?mon="+$('#declareds_time').val()+"&company_id="+$('#company_select').val(),success:function(result){
			  		if(result=="")
			        	{
			        		$('.lastyear_s').html("");
			        	}
			        	else
			        	{
				        $.each(result, function(i,val){     
				        		if(Number($("#month_"+val.rf_id).val())==0)
			     			{
			     				var growth="";
			     			}
			     			else
			     			{
			     				
		     					deviation=Number(Number($("#month_"+val.rf_id).val())-Number(val.item_val))/ Number(val.item_val)*100;
		     					
		     					var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
			     				
			     			}
			     			$('#growth_'+val.rf_id).html(growth);
					      	$('#lastyear_'+val.rf_id).html(val.item_val);
				        	
					  });   
			        	}
			    }});
		  });
		   $('#company_select').on("change",function (obj) {

		   		 $.ajax({url:"declared/declaredsyr/getlastyeardata?mon="+$('#declareds_time').val()+"&company_id="+$('#company_select').val(),success:function(result){
			  		if(result=="")
			        	{
			        		$('.lastyear_s').html("");
			        	}
			        	else
			        	{
				        $.each(result, function(i,val){     
				        		if(Number($("#month_"+val.rf_id).val())==0)
			     			{
			     				var growth="";
			     			}
			     			else
			     			{
			     				
		     					deviation=Number(Number($("#month_"+val.rf_id).val())-Number(val.item_val))/ Number(val.item_val)*100;
		     					var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
			     				
			     			}
			     			$('#growth_'+val.rf_id).html(growth);
					      	$('#lastyear_'+val.rf_id).html(val.item_val);
				        	
					  });   
			        	}
			    }});
		   });
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
        		$.ajax({url:"declared/declaredsyr/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	
				     	$("#month_"+val.id).blur(function(){
				     		
				     		if(Number($("#month_"+val.id).val())==0)
			     			{
			     				var growth="";
			     			}
			     			else
			     			{
			     				if(Number($("#lastyear_"+val.id).text())==0)
			     				{
			     					var growth="";
			     				}
			     				else
			     				{
			     					deviation=Number(Number($("#month_"+val.id).val())-Number($("#lastyear_"+val.id).text()))/Math.abs(Number($("#lastyear_"+val.id).text()))*100;
			     					var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
			     				}
			     			}
			     			$('#growth_'+val.id).html(growth);
						  	
						});
				     
				  });   
			        	
			    }});
        		$.ajax({url:"declared/declaredsyr/getlastyeardata?mon="+$('#declareds_time').val()+"&company_id=&id="+$('#edit_id').val(),success:function(result){
			  		if(result=="")
			        	{
			        		$('.lastyear_s').html("");	
			        	}
			        	else
			        	{
				        $.each(result, function(i,val){  
				        
				        		if(Number($("#month_"+val.rf_id).val())==0)
			     			{
			     				var growth="";
			     			}
			     			else
			     			{
			     				
		     					deviation=Number(Number($("#month_"+val.rf_id).val())-Number(val.item_val))/Math.abs(Number(val.item_val))*100;
		     					var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
			     				
			     			}   
			     			$('#growth_'+val.rf_id).html(growth);
					      	$('#lastyear_'+val.rf_id).html(val.item_val);
				        	
					  });   
			        	}
			    }});
        	  
            Form.api.bindevent($("form[role=form]"));
        },api: {
            formatter: {
                title: function (value, row, index) {
                    return !row.ismenu || row.status == 'hidden' ? "<span class='text-muted'>" + value + "</span>" : value;
                },
                name: function (value, row, index) {
                    return !row.ismenu || row.status == 'hidden' ? "<span class='text-muted'>" + value + "</span>" : value;
                },
                icon: function (value, row, index) {
                    return '<span class="' + (!row.ismenu || row.status == 'hidden' ? 'text-muted' : '') + '"><i class="' + value + '"></i></span>';
                },
                subnode: function (value, row, index) {
                    return '<a href="javascript:;" data-toggle="tooltip" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs '
                        + (row.haschild == 1 || row.ismenu == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                }
            },
            bindevent: function () {
                $(document).on('click', "input[name='row[ismenu]']", function () {
                    var name = $("input[name='row[name]']");
                    name.prop("placeholder", $(this).val() == 1 ? name.data("placeholder-menu") : name.data("placeholder-node"));
                });
                $("input[name='row[ismenu]']:checked").trigger("click");

                var iconlist = [];
                var iconfunc = function () {
                    Layer.open({
                        type: 1,
                        area: ['99%', '98%'], //宽高
                        content: Template('chooseicontpl', {iconlist: iconlist})
                    });
                };
                Form.api.bindevent($("form[role=form]"), function (data) {
                    Fast.api.refreshmenu();
                });
                $(document).on('click', ".btn-search-icon", function () {
                    if (iconlist.length == 0) {
                        $.get(Config.site.cdnurl + "/assets/libs/font-awesome/less/variables.less", function (ret) {
                            var exp = /fa-var-(.*):/ig;
                            var result;
                            while ((result = exp.exec(ret)) != null) {
                                iconlist.push(result[1]);
                            }
                            iconfunc();
                        });
                    } else {
                        iconfunc();
                    }
                });
                $(document).on('click', '#chooseicon ul li', function () {
                    $("input[name='row[icon]']").val('fa fa-' + $(this).data("font"));
                    Layer.closeAll();
                });
                $(document).on('keyup', 'input.js-icon-search', function () {
                    $("#chooseicon ul li").show();
                    if ($(this).val() != '') {
                        $("#chooseicon ul li:not([data-font*='" + $(this).val() + "'])").hide();
                    }
                });
            }
        }
    };
    return Controller;
});