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
				var mondataChk=$("#mondata").val();
				mondataArr=mondataChk.toString().split(',');
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
				else if($("#oneCnt").val()==1 && mondataArr.length>1)
				{
					alert("[显示1-本月合计]选择[是]时，日期只能选择一个");
				}
				else
				{
					var iptSelectChk=$("#iptSelect").val();
					var oneCntChk=$("#oneCnt").val();
					var searchType=0;

					if(iptSelectChk==1 && oneCntChk==0)
					{
						searchType=1;
					}
					else if(iptSelectChk==0 && oneCntChk==1)
					{
						searchType=2;
					}
					else if(iptSelectChk==1 && oneCntChk==1)
					{
						searchType=3;
					}
					window.location.href="month/print_excel?mondata="+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val()+'&searchType='+searchType;
				}	
			});
		// 为表格绑定事件
		$("#submit").click(function(){
			var mondataChk=$("#mondata").val();
			mondataArr=mondataChk.toString().split(',');
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
			else if($("#oneCnt").val()==1 && mondataArr.length>1)
			{
				alert("[显示1-本月合计]选择[是]时，日期只能选择一个");
			}
			else
			{
			
				 $.ajax({url:"statistics/month/getObjData",success:function(result){
				 
					Table.api.init({
						extend: {
							index_url: 'statistics/month/index',
							//edit_url: 'statistics/month/edit'
						
						}
					});
				  var table = $("#table");
				  var objdata=$("#objdata").val();
				  arr=objdata.toString().split(',');//注split可以用字符或字符串分割
				  var aoColumnsShow = [];
				  //alert(objdata);
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
	                                url: 'statistics/month/edit'
	
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
	                                url: 'statistics/month/edit'
	
	                            }],
			                  formatter: Table.api.formatter.operate
			            };
				  }
			       
				  var iptSelectChk=$("#iptSelect").val();
				  var oneCntChk=$("#oneCnt").val();
				  var searchType=0;

					if(iptSelectChk==1 && oneCntChk==0)
					{
						searchType=1;
					}
					else if(iptSelectChk==0 && oneCntChk==1)
					{
						searchType=2;
					}
					else if(iptSelectChk==1 && oneCntChk==1)
					{
						searchType=3;
					}
		            //在表格内容渲染完成后回调的事件
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
        	  	window.location.href="http://"+window.location.hostname+url.substring(0,url3)+"declared/declaredm/print_excel?ids="+$("#ids").val();
        	  });

        	 Form.api.bindevent($("form[role=form]"), function(data, ret){
        	 	window.parent.location.reload();
        	 });
        }
    };
    return Controller;
});