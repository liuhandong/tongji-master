define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'declared/declaredm/index',
                    add_url: 'declared/declaredm/add',
                    //edit_url: 'declared/declaredm/edit',
                    //del_url: 'declared/declaredm/del',
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
                    //operate: 'BETWEEN',
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'company_park_name',  title:'填报单位',operate: 'LIKE',sortable: true},
                        {field: 'nickname', title:'创建人',operate: false,sortable: true},
                        {field: 'mon', title: '报表月份',operate: false,sortable: true},
                        {field: 'add_time', title: '创建时间',addclass: 'datetimerange', formatter: Table.api.formatter.datetime,sortable: true},
                        {field: 'is_key_name', title: '状态', operate: false},
                        {field: 'file', title: '附件', operate: false, formatter: Controller.api.formatter.icon},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'kaohe_ed',
                                text: '查看附件',
                                icon: 'fa fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredm/uploadlist'

                            },{
                                name: 'exam',
                                text: '提交填报',
                                icon: 'fa fa-list',
                                classname: 'btn btn-xs btn-warning btn-ajax',
                                url: 'declared/declaredm/tibao',
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
                                url: 'declared/declaredm/print_excel'

                            },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa fa-pencil',
                                classname: 'btn btn-xs btn-success btn-dialog',
                                url: 'declared/declaredm/edit'

                            },{
                                name: 'kaohe_ed',
                                text: '',
                                icon: 'fa fa-trash',
                                classname: 'btn btn-xs btn-danger btn-ajax',
                                url: 'declared/declaredm/del',
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
                    index_url: 'declared/declaredm/uploadlist?fa_id='+$('#fa_id').val(),
                    del_url: 'declared/declaredm/filedel',
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
           $('#declareds_mon').on("change",function (obj) {
           	if($('#declareds_mon').val()=="12")
           	{
           		$('#month_148').removeAttr("disabled");
           		$('#month_151').removeAttr("disabled");
           		$('#month_156').removeAttr("disabled");
           		$('#month_166').removeAttr("disabled");
           		$('#month_168').removeAttr("disabled");
           		$('#month_171').removeAttr("disabled");	
           		$('#month_159').removeAttr("disabled");	
           		$('#month_173').removeAttr("disabled");	
           	}
           	else if($('#declareds_mon').val()=="03" || $('#declareds_mon').val()=="06" || $('#declareds_mon').val()=="09")
           	{
           		
           		$('#month_148').removeAttr("disabled");
           		$('#month_151').removeAttr("disabled");
           		$('#month_156').removeAttr("disabled");
           		$('#month_166').removeAttr("disabled");
           		$('#month_168').removeAttr("disabled");
           		$('#month_171').removeAttr("disabled");
           	}
           	else
           	{
           		$('#month_148').val("");
           		$('#month_151').val("");
           		$('#month_156').val("");
           		$('#month_166').val("");
           		$('#month_168').val("");
           		$('#month_171').val("");
           		$('#month_159').val("");
           		$('#month_173').val("");
           		
           		$('#month_148').attr("disabled",true);
           		$('#month_151').attr("disabled",true);
           		$('#month_156').attr("disabled",true);
           		$('#month_166').attr("disabled",true);
           		$('#month_168').attr("disabled",true);
           		$('#month_171').attr("disabled",true);
           		$('#month_159').attr("disabled",true);
           		$('#month_173').attr("disabled",true);
           	}
           });
        	$.ajax({url:"declared/declaredm/getcompanydata",success:function(result){
			  		
				        $.each(result, function(i,val){     
					      $('#company_select').append("<option value='"+val.id+"'>"+val.company_park_name+"</option>");
					      
					  });   
			        	
			    }});
		  declareds_time=$('#declareds_year').val()+"-"+$('#declareds_mon').val();
		  
        	   $.ajax({url:"declared/declaredm/getlastyeardata?mon="+declareds_time,success:function(result){
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
        	   $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	//alert()
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
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+declareds_time+"&rf_id="+val.id+"&company_id="+$('#company_select').val(),success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html($("#month_"+val.id).val());	
						        	}
						        	else
						        	{
						        		//total=(Number(Number($("#month_"+val.id).val())*1000+Number(result)*1000))/1000;
						        		total=Number($("#month_"+val.id).val()).add(Number(result));
						        		//total=t1.divide
						        		//alert(total);
							          $('#total_'+val.id).html(total);	
						        	}
						    }});
						});
				     }
				  });   
			        	
			    }});
		   $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+declareds_time+"&rf_id="+val.id+"&company_id="+$('#company_select').val(),success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html($("#month_"+val.id).val());
						        	}
						        	else
						        	{
						        		total=Number(result);
							          $('#total_'+val.id).html(total);	
						        	}
						    }});

				     }
				  });   
			        	
			    }});
		   $('#company_select').on("change",function (obj) {
        	   	  declareds_time=$('#declareds_year').val()+"-"+$('#declareds_mon').val();
        	   	  company_id=$('#company_select').val();
        	   	 $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+declareds_time+"&rf_id="+val.id+"&company_id="+company_id,success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html($("#month_"+val.id).val());	
						        	}
						        	else
						        	{
						        		total=Number($("#month_"+val.id).val())+Number(result);
							          $('#total_'+val.id).html(total);	
						        	}
						    }});

				     }
				  });   
			        	
			    }});
			    
			  $.ajax({url:"declared/declaredm/getlastyeardata?mon="+declareds_time+"&company_id="+company_id,success:function(result){
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
        	   $('#declareds_year').on("change",function (obj) {
        	   	  declareds_time=$('#declareds_year').val()+"-"+$('#declareds_mon').val();
        	   	  company_id=$('#company_select').val();
        	   	 $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+declareds_time+"&rf_id="+val.id+"&company_id="+company_id,success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html($("#month_"+val.id).val());
						        	}
						        	else
						        	{
						        		total=Number($("#month_"+val.id).val())+Number(result);
							          $('#total_'+val.id).html(total);	
						        	}
						    }});

				     }
				  });   
			        	
			    }});
			    
			  $.ajax({url:"declared/declaredm/getlastyeardata?mon="+declareds_time+"&company_id="+company_id,success:function(result){
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
		  $('#declareds_mon').on("change",function (obj) {
		  	 declareds_time=$('#declareds_year').val()+"-"+$('#declareds_mon').val();
		  	 company_id=$('#company_select').val();
		  	 if($('#declareds_mon').val()=="03" || $('#declareds_mon').val()=="06" || $('#declareds_mon').val()=="09" || $('#declareds_mon').val()=="12")
		  	 {
		  	 	$('#month_148').attr("readonly",false);
		  	 	$('#month_151').attr("readonly",false);
		  	 	$('#month_166').attr("readonly",false);
		  	 	$('#month_168').attr("readonly",false);
		  	 }
		  	 else
		  	 {
		  	 	$('#month_148').attr("readonly",true);
		  	 	$('#month_151').attr("readonly",true);
		  	 	$('#month_166').attr("readonly",true);
		  	 	$('#month_168').attr("readonly",true);
		  	 }
        	   	 $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
			        	   
				     if(val.pid>0)
				     {
				     	
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+declareds_time+"&rf_id="+val.id+"&company_id="+company_id,success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html($("#month_"+val.id).val());
						        	}
						        	else
						        	{
						        		total=Number($("#month_"+val.id).val())+Number(result);
							          $('#total_'+val.id).html(total);	
						        	}
						    }});

				     }
				  });   
			        	
			    }});
			    
			  $.ajax({url:"declared/declaredm/getlastyeardata?mon="+declareds_time+"&company_id="+company_id,success:function(result){
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
        	$.ajax({url:"declared/declaredm/getlastyeardata?mon="+$('#declareds_time').val()+"&id="+$('#edit_id').val(),success:function(result){
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
		     				//alert(deviation);
		     			}
		     			$('#growth_'+val.rf_id).html(growth);
					      $('#lastyear_'+val.rf_id).html(val.item_val);
					      
					  });   
			        	}
			    }});
		    $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
			        $.each(result, function(i,val){  
				     if(val.pid>0)
				     {
					  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+$('#declareds_time').val()+"&rf_id="+val.id+"&id="+$('#edit_id').val(),success:function(result){
					  		if(result=="")
					        	{
					        		$('#total_'+val.id).html($("#month_"+val.id).val());	
					        	}
					        	else
					        	{
					        		//total=Number($("#month_"+val.id).val())+Number(result);
					        		total=Number($("#month_"+val.id).val()).add(Number(result));
						          $('#total_'+val.id).html(total);	
					        	}
					    }});
				     }
				  });   
			        	
			    }});
        	   $.ajax({url:"declared/declaredm/getdata",success:function(result){
		  			
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
			     				//alert(deviation);
			     			}
		     			
		     				$('#growth_'+val.id).html(growth);
						  	$.ajax({url:"declared/declaredm/gettotaldata?mon="+$('#declareds_time').val()+"&rf_id="+val.id+"&id="+$('#edit_id').val(),success:function(result){
						  		if(result=="")
						        	{
						        		$('#total_'+val.id).html(Number($("#month_"+val.id).val()));	
						        	}
						        	else
						        	{
						        		//total=(Number(Number($("#month_"+val.id).val())*1000+Number(result)*1000))/1000;
						        		//alert(1111);
						        		total=Number($("#month_"+val.id).val()).add(Number(result));
							          $('#total_'+val.id).html(total);	
						        	}
						    }});
						});
				     }
				  });   
			        	
			    }});
	       $('#validity_year').on("change",function (obj) {
		  	 validity=$('#validity_year').val()+"-"+$('#validity_mon').val();
		  	 $('#validity').val(validity);
		  });
		  $('#validity_mon').on("change",function (obj) {
		  	 validity=$('#validity_year').val()+"-"+$('#validity_mon').val();
		  	 $('#validity').val(validity);
		  });
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