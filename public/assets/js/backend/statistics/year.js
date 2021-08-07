define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	  //重置
        	$("#reset").click(function(){
        		$("#mondata").val("").trigger('change');
				if($("#ad_c_id").val()=='1')
				{
					$("#companydata").val("").trigger('change');
				}
        		$("#objdata").val("").trigger('change');
        		 $("#add-form li").removeClass("selected");
        	});
        	$("#export_excel").click(function(){
			if($("#mondata").val()==null)
			{
				alert("请选择填报时间");
			}
			else if($("#companydata").val()==null)
			{
				alert("请选择填报单位");
			}
			else if($("#objdata").val()==null)
			{
				alert("请选择填报项目");
			}
			else
			{
				var iptSelectChk=$("#iptSelect").val();
				var searchType=0;

				if(iptSelectChk==1)
				{
					searchType=1;
				}
				window.location.href="year/print_excel?mondata="+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val()+'&searchType='+searchType;
			}	
		});
		 // 为表格绑定事件
          $("#submit").click(function(){
			if($("#mondata").val()==null)
			{
				alert("请选择填报时间");
			}
			else if($("#companydata").val()==null)
			{
				alert("请选择填报单位");
			}
			else if($("#objdata").val()==null)
			{
				alert("请选择填报项目");
			}
			else
			{
				$.ajax({url:"statistics/year/getObjData",success:function(result){
					Table.api.init({
						extend: {
							index_url: 'statistics/year/index',
							//edit_url: 'statistics/month/edit'
						
						}
					});	
					var table = $("#table");
					  var objdata=$("#objdata").val();
					  arr=objdata.toString().split(',');//注split可以用字符或字符串分割
					  var aoColumnsShow = [];
					   if(objdata.indexOf("999") != -1)
					  {
					  	 i=0;
						  $.each(result,function (key,value) {
						  	
						  	 aoColumnsShow[i+2] = {field: key, title: value,width:140,valign:'middle',align:'center'};
						  	 i++;
					          
					        });
						  aoColumnsShow[0] = {field: 'mon', title: '报表月份',width:80};
					       aoColumnsShow[1] = {field: 'company_park_name', title: '申报单位',width:160};
					       aoColumnsShow[i+2] = {field: 'is_key_name', title: '状态',width:80};
					       aoColumnsShow[i+3] = {field: 'operate', title: __('Operate'), width:80,table: table, events: Table.api.events.operate, buttons: [{
		                                name: 'kaohe_ed',
		                                text: '查看',
		                                icon: 'fa fa-pencil',
		                                classname: 'btn btn-xs btn-success btn-dialog',
		                                url: 'statistics/year/edit'
		
		                            }],
				                  formatter: Table.api.formatter.operate
				            };
					  }
					  else
					  {
						  for(var i=0;i<arr.length;i++)
						  {
						  	aoColumnsShow[i+2] = {field: arr[i], title: result[arr[i]],width:140,valign:'middle',align:'center'};
							//alert(1111);
						  }
						  aoColumnsShow[0] = {field: 'mon', title: '报表月份',width:80};
					       aoColumnsShow[1] = {field: 'company_park_name', title: '申报单位',width:160};
					       aoColumnsShow[arr.length+2] = {field: 'is_key_name', title: '状态',width:80};
					       aoColumnsShow[arr.length+3] = {field: 'operate', title: __('Operate'), width:80,table: table, events: Table.api.events.operate, buttons: [{
		                                name: 'kaohe_ed',
		                                text: '查看',
		                                icon: 'fa fa-pencil',
		                                classname: 'btn btn-xs btn-success btn-dialog',
		                                url: 'statistics/year/edit'
		
		                            }],
				                  formatter: Table.api.formatter.operate
				            };
					  }
					  var iptSelectChk=$("#iptSelect").val();
					  var searchType=0;

					  if(iptSelectChk==1)
					  {
					  	searchType=1;
					  }
			             table.bootstrapTable('destroy'); 
			            // 初始化表格
			            table.bootstrapTable({
			                url: $.fn.bootstrapTable.defaults.extend.index_url+'?mondata='+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val()+'&searchType='+searchType,
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
				}});
				
				//alert("11111");
			}
		   });
		  Form.api.bindevent($("form[role=form]"));
        },
        add: function () {
        	
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
        	   $("#export_excel").click(function(){
        	  	var url = window.location.pathname;
				url2 = url.substring(url.lastIndexOf('statistics/'), url.length);
				url3=url.length-url2.length;
        	  	window.location.href="http://"+window.location.hostname+url.substring(0,url3)+"declared/declaredy/print_excel?ids="+$("#ids").val();
        	  });

        	 Form.api.bindevent($("form[role=form]"), function(data, ret){
        	 	window.parent.location.reload();
        	 });
        }
    };
    return Controller;
});