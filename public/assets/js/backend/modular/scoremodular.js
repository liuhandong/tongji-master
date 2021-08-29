define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                search: true,
                advancedSearch: true,
                pagination: true,
                extend: {
                    index_url: 'modular/scoremodular/index',
                    add_url: 'modular/scoremodular/add',
                    edit_url: 'modular/scoremodular/edit',
                    del_url: 'modular/scoremodular/del'
                }
            });
        	//重置
        	$("#reset").click(function(){
        		var date = new Date();
                var year = date.getFullYear();
        		$("#rf_year").selectpicker ("val",year).trigger("change");
        		$("#their_garden").selectpicker ("val","1").trigger("change");
        	});
            
            $("#export_excel").click(function(){
                var where="?1=1";
                if($("#rf_year").val()!=null)
                {
                    where+='&rf_year='+$("#rf_year").val();
                }
                if($("#their_garden").val()!=null)
                {
                    where+='&their_garden='+$("#their_garden").val();
                }
                window.location.href="scoremodular/print_excel"+where;	
            });
            
            // 为表格绑定事件
            $("#submit").click(function(){

                
                var table = $("#table");
                
               var aoColumnsShow = [];
             
               aoColumnsShow[0] = {field: 'id', title: 'ID'};
               aoColumnsShow[1] = {field: 'name', title: '描述'};
               aoColumnsShow[2] = {field: 'seqn', title: '代码'};
               aoColumnsShow[3] = {field: 'unit_name', title: '计量单位'};
               aoColumnsShow[4] = {field: 'report_type', title: '报表类型'};
               aoColumnsShow[5] = {field: 'rf_year', title: '报表年份'};
               aoColumnsShow[6] = {field: 'operate', title: __('Operate'), table: table, 
                             buttons: [
                                       {name: 'del',  url: 'video/auth'},
                                       {name: 'edit', url: 'video/auth'}
                                     ], 
                        events: Table.api.events.operate, formatter: Table.api.formatter.operate};
               //aoColumnsShow[9] = {field: 'list2', title: '填报单位'};
               
                table.bootstrapTable('destroy'); 
                // 初始化表格
                table.bootstrapTable({
                    url: $.fn.bootstrapTable.defaults.extend.index_url+'?rf_year='+$("#rf_year").val()+'&their_garden='+$("#their_garden").val(),
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

                //在表格内容渲染完成后回调的事件
                // 初始化表格
                //table.bootstrapTable({
                //    url: $.fn.bootstrapTable.defaults.extend.index_url+'?rf_year='+$("#rf_year").val()+'&their_garden='+$("#their_garden").val(),
                //    showToggle: false,//切换格式
                //    showColumns: false,//列
                //    search:false,      //快速搜索
                //    showExport: false, //导出整表
                //    commonSearch: false, //禁用通用搜索
                //    columns: [
                //        [
                //            {field: 'id', title: 'ID',sortable: true},
                //            {field: 'name',  title: __('name'),operate: false},
                //            {field: 'seqn',  title: __('seqn'),operate: false},
                //            {field: 'topic', title: __('topic'),operate: false,visible:false},
                //            {field: 'order_no', title: __('orderNo'),operate: false,visible:false},
                //            {field: 'num_flag', title: __('numFlag'),operate: false,visible:false},
                //            {field: 'their_garden', title: __('theirGarden'),operate: false},
                //            {field: 'unit_name', title: __('unitId'),operate: false},
                //            {field: 'rf_year', title: __('rfYear'),operate: false},
                //            {field: 'list1', title: __('list1'),operate: false,visible:false},
                //            {field: 'list2', title: __('list2'),operate: false,visible:false},
                //            
                //            //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                //            //    return Table.api.formatter.operate.call(this, value, row, index);
                //            //}}
                //        ]
                //    ]
                //});

                // 为表格绑定事件
                //Table.api.bindevent(table);
            });
           Form.api.bindevent($("form[role=form]"));
           $(document).ready(function () {
                $("#submit").click();
           });
            
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