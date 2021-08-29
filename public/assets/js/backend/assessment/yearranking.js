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
				Table.api.init({
						extend: {
							index_url: 'assessment/yearranking/index',
							edit_url: 'assessment/yearranking/edit'
						
						}
					});	
					var table = $("#table");

					  var aoColumnsShow = [];
					  
					  aoColumnsShow[0] = {field: 'time', title: '考核时间',sortable: false};
				      aoColumnsShow[1] = {field: 'company_name', title: '被考核单位',sortable: false};
					  aoColumnsShow[2] = {field: 'score', title: '考核总分',sortable: false};
					  aoColumnsShow[3] = {field: 'ranking', title: '排名',sortable: false};
					  aoColumnsShow[4] = {field: 'status', title: '状态',sortable: false};
					  //aoColumnsShow[5] = {field: 'operate', title: '操作',width:80,sortable: false};
					  aoColumnsShow[5] = {field: 'operate', title: __('Operate'), table: table, 
                             buttons: [
                                       {name: 'del',  url: 'video/auth'},
                                       {name: 'edit', url: 'video/auth'}
                                     ], 
                        events: Table.api.events.operate, formatter: Table.api.formatter.operate};
				      
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