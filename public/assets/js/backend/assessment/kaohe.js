define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	  //重置
        	$("#reset").click(function(){
        		$("#mondata").val("").trigger('change');
        		$("#companydata").val("").trigger('change');
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
				window.location.href="kaohe/print_excel?mondata="+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val(); 
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
				$.ajax({url:"assessment/kaohe/getObjData",success:function(result){
					Table.api.init({
						extend: {
							index_url: 'assessment/kaohe/index',
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
						  	
						  	 aoColumnsShow[i+2] = {field: "f"+key, title: value,width:140,valign:'middle',align:'center'};
						  	 i++;
					          
					        });
					  }
					  else
					  {
						  for(var i=0;i<arr.length;i++)
						  {
						  	aoColumnsShow[i+2] = {field: "f"+arr[i], title: result[arr[i]],width:140,valign:'middle',align:'center',sortable: true};
							//alert(1111);
						  }
					  }
					  aoColumnsShow[0] = {field: 'mon', title: '报表月份',width:80,sortable: true};
				       aoColumnsShow[1] = {field: 'company_park_name', title: '申报单位',width:160,sortable: true};
				      
			             table.bootstrapTable('destroy'); 
			            // 初始化表格
			            table.bootstrapTable({
			                url: $.fn.bootstrapTable.defaults.extend.index_url+'?mondata='+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val(),
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
        	 
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});