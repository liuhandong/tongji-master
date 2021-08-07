define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'review/proofreadings/index',
                    edit_url: 'review/proofreadings/edit',
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
                        {field: 'mon', title: '报表季度',operate: 'LIKE',sortable: true},
                        {field: 'nickname', title:'创建人',operate: false,sortable: true},
                        {field: 'add_time', title: '创建时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange',operate: false,sortable: true},
                        {field: 'sb_time', title: '填报时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange',operate: false,sortable: true},
                         {field: 'file', title: '附件', operate: false, formatter: Controller.api.formatter.icon},
                        {field: 'is_key_name', title: '状态', operate: false},
                        {
                            field: 'buttons',
                            width: "300px",
                            title: __('操作'),
                            table: table,
                            operate: false,
                            events: Table.api.events.operate,
                            buttons: [{
                                name: 'exam',
                                text: '驳回',
                                icon: 'fa fa-step-backward',
                                classname: 'btn btn-xs btn-warning btn-ajax',
                                url: 'review/proofreadings/backward',
                                confirm: '确认驳回?',
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
                                url: 'declared/declareds/print_excel'

                            },
                                {
                                    name: 'addtabs',
                                    text: __('校对'),
                                    title: __('校对'),
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    icon: 'fa fa-folder-o',
                                    url: 'review/proofreadings/edit'
                                },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa fa-pencil',
                                classname: 'btn btn-xs btn-success btn-dialog',
                                url: 'review/proofreadings/view'

                            }
                            ],
                            formatter: Table.api.formatter.buttons

                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
        			$.ajax({url:"review/proofreadings/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   //alert()
				     if(val.pid==0)
				     {
				     	
				     	$("#check_"+val.id).blur(function(){
				     		if($("#check_"+val.id).val()!=0)
				     		{
				     			if($("#month_"+val.id).val()=="")
				     			{
				     				var newNum="";
				     			}
				     			else
				     			{
				     				deviation=Number(Number($("#check_"+val.id).val())-Number($("#month_"+val.id).val()))/ Number($("#month_"+val.id).val())*100;
				     				var newNum=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
				     			}
				     			
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
						     				deviation=Number(Number($("#check_"+val.id).val())-Number($("#lastyear_"+val.id).text()))/Math.abs(Number($("#lastyear_"+val.id).text()))*100;
						     				var growth=parseInt(deviation*Math.pow(10,1))/Math.pow(10,1)+'%';
					     				}
				     				//alert(deviation);
				     			}
			    
			     				$('#growth_'+val.id).html(growth);
						  		$('#deviation_'+val.id).html(newNum);
						  		
				     		}
				     		else
				     		{
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
				     			$('#deviation_'+val.id).html("");	
				     		}
						});
				     }
				  });   
			        	
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