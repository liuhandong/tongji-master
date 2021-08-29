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
				alert("请选择考核时间");
			}
			else if($("#companydata").val()==null)
			{
				alert("请选择被考核单位");
			}
			//else if($("#objdata").val()==null)
			//{
				//alert("请选择填报项目");
			//}
			else
			{
				window.location.href="yearranking/print_excel?mondata="+$("#mondata").val()+'&companydata='+$("#companydata").val()+'&objdata='+$("#objdata").val(); 
			}	
		});
		 // 为表格绑定事件
          $("#submit").click(function(){
			if($("#mondata").val()==null)
			{
				alert("请选择考核时间");
			}
			else if($("#companydata").val()==null)
			{
				alert("请选择被考核单位");
			}
			//else if($("#objdata").val()==null)
			//{
				//alert("请选择填报项目");
			//}
			else
			{
				$.ajax({url:"assessment/yearranking/getLastYearDeclaredsList",success:function(result){
					alert();
					Table.api.init({
						extend: {
							index_url: 'assessment/yearranking/index',
							//edit_url: 'statistics/month/edit'
						
						}
					});	
					var table = $("#table");
					  var objdata=$("#objdata").val();
					  alert(objdata);
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
					  aoColumnsShow[0] = {field: 'mon', title: '考核时间',width:80,sortable: false};
				      aoColumnsShow[1] = {field: 'company_park_name', title: '被考核单位',width:160,sortable: false};
					  aoColumnsShow[2] = {field: 'mon', title: '考核总分',width:80,sortable: false};
					  aoColumnsShow[3] = {field: 'mon', title: '排名',width:80,sortable: false};
					  aoColumnsShow[4] = {field: 'mon', title: '状态',width:80,sortable: false};
					  aoColumnsShow[5] = {field: 'mon', title: '操作',width:80,sortable: false};
				      
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