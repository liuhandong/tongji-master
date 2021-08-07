define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	  //重置
        	$("#reset").click(function(){
        		var date = new Date();
	  		var year = date.getFullYear();
        		$("#mondata").selectpicker ("val",year).trigger("change");
        		$("#companydata").selectpicker ("val","1").trigger("change");
        		$("#objdata").selectpicker ("val","1").trigger("change");
        		$("#monthdata").selectpicker ("val","01").trigger("change");
        		$("#seasondata").selectpicker ("val","01").trigger("change");
        		$("#monthhtml").css('display','none'); 
        		$("#seasonhtml").css('display','none'); 
        		 //$("#add-form li").removeClass("selected");
        	});
        	$("#objdata").change(function(){
        		
        		if($("#objdata").val()=="2")
        		{
        			$("#monthhtml").css('display','block'); 
        			$("#seasonhtml").css('display','none'); 
        		}
        		else if($("#objdata").val()=="3")
        		{
        			$("#monthhtml").css('display','none'); 
        			$("#seasonhtml").css('display','block'); 
        		}
        		else
        		{
        			$("#monthhtml").css('display','none'); 
        			$("#seasonhtml").css('display','none'); 
        		}
        	});
		 // 为表格绑定事件
          $("#submit").click(function(){
			if($("#companydata").val()==null)
			{
				alert("请选择填报单位");
			}
			else
			{
				
				Table.api.init({
					extend: {
						index_url: 'report_search/index',
						//edit_url: 'statistics/month/edit'
					
					}
				});	
				var table = $("#table");
				  
				  var aoColumnsShow = [];
				 
			       aoColumnsShow[0] = {field: 'company_park_name', title: '填报单位'};
			       aoColumnsShow[1] = {field: 'mon', title: '填报时间'};
			       aoColumnsShow[2] = {field: 'rf_class_name', title: '填报种别'};
			       aoColumnsShow[3] = {field: 'add_time', title: '创建时间',addclass: 'datetimerange', formatter: Table.api.formatter.datetime};
			       aoColumnsShow[4] = {field: 'is_key_name', title: '状态'};
			       aoColumnsShow[5] = {field: 'operate', title: __('Operate'), formatter: Controller.api.formatter.url};
			      
		             table.bootstrapTable('destroy'); 
		            // 初始化表格
		            table.bootstrapTable({
		                url: $.fn.bootstrapTable.defaults.extend.index_url+'?mondata='+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val()+'&monthdata='+$("#monthdata").val()+'&seasondata='+$("#seasondata").val(),
		                showToggle: false,//切换格式
		                search: false, //是否搜索
		                showColumns: false,//列
		                commonSearch: false,//检索
		                showExport: false,//导出
		                exportTypes: [ 'excel'],
		                columns:aoColumnsShow,
		            });
		            // 为表格绑定事件
	            	 Table.api.bindevent(table);
			}
		   });
		  Form.api.bindevent($("form[role=form]"));
        },
        add: function () {
        	
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
        	 
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
                },
                url: function (value, row, index) {
                	if(row.rf_class=="m")
                	{
                		if(row.is_key=="0")
                		{
                    		return '<a href="declared/declaredm/?id='+ row.id +'" title="月报填报" class="btn btn-xs btn-default addtabsit" >操作</a>';
                		}
                		else if(row.is_key=="1")
	                	{
	                    	return '<a href="review/proofreadingm/?id='+ row.id +'" title="月报校对" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else if(row.is_key=="2")
	                	{
	                    	return '<a href="approval/approvalm/?id='+ row.id +'" title="月报审核" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else
	                	{
	                		return '<a href="statistics/month/edit/?ids='+ row.id +'" title="月报汇总" class="btn btn-xs btn-default btn-dialog" >操作</a>';
	                	}
                	}
                	else if(row.rf_class=="s")
                	{
                    	if(row.is_key=="0")
                		{
                    		return '<a href="declared/declareds/?id='+ row.id +'" title="季报填报" class="btn btn-xs btn-default addtabsit" >操作</a>';
                		}
                		else if(row.is_key=="1")
	                	{
	                    	return '<a href="review/proofreadings/?id='+ row.id +'" title="季报校对" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else if(row.is_key=="2")
	                	{
	                    	return '<a href="approval/approvals/?id='+ row.id +'" title="季报审核" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else
	                	{
	                		return '<a href="statistics/season/edit/?ids='+ row.id +'" title="季报汇总" class="btn btn-xs btn-default btn-dialog" >操作</a>';
	                	}
                	}
                	else if(row.rf_class=="y")
                	{
                    	if(row.is_key=="0")
                		{
                    		return '<a href="declared/declaredy/?id='+ row.id +'" title="年报填报" class="btn btn-xs btn-default addtabsit" >操作</a>';
                		}
                		else if(row.is_key=="1")
	                	{
	                    	return '<a href="review/proofreadingy/?id='+ row.id +'" title="年报校对" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else if(row.is_key=="2")
	                	{
	                    	return '<a href="approval/approvaly/?id='+ row.id +'" title="年报审核" class="btn btn-xs btn-default addtabsit" >操作</a>';
	                	}
	                	else
	                	{
	                		return '<a href="statistics/year/edit/?ids='+ row.id +'" title="年报汇总" class="btn btn-xs btn-default btn-dialog" >操作</a>';
	                	}
                	}
                	
                	
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