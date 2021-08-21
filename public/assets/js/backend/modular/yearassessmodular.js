define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                search: true,
                advancedSearch: true,
                pagination: true,
                extend: {
                    index_url: 'modular/yearassessmodular/index',
                    add_url: 'modular/yearassessmodular/add',
                    edit_url: 'modular/yearassessmodular/edit',
                    del_url: 'modular/yearassessmodular/del'
                }
            });
            
            // 为表格绑定事件
            //$("#submit").click(function(){
            //    if($("#compdata").val()==null)
            //    {
            //        alert("请选择填报单位");
            //    }
            //    else
            //    {
            //
            //    }
            //});
           
            // 初始化表格参数配置
           //Table.api.init({
           //    search: true,
           //    advancedSearch: true,
           //    pagination: true,
           //    showExport: true,
           //    exportTypes: [ 'excel'],
           //    extend: {
           //        index_url: 'modular/yearassessmodular/index',
           //        add_url: 'modular/yearassessmodular/add',
           //        edit_url: 'modular/yearassessmodular/edit'
           //    }
           //});
           //
            var table = $("#table");

            //在表格内容渲染完成后回调的事件
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                showToggle: false,//切换格式
                showColumns: false,//列
                search:false,      //快速搜索
                showExport: false, //导出整表
                commonSearch: false, //禁用通用搜索
                columns: [
                    [
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'name',  title: __('name'),operate: false},
                        {field: 'seqn',  title: __('seqn'),operate: false},
                        {field: 'topic', title: __('topic'),operate: false,visible:false},
                        {field: 'order_no', title: __('orderNo'),operate: false,visible:false},
                        {field: 'num_flag', title: __('numFlag'),operate: false,visible:false},
                        {field: 'their_garden', title: __('theirGarden'),operate: false},
                        {field: 'unit_name', title: __('unitId'),operate: false},
                        {field: 'rf_year', title: __('rfYear'),operate: false},
                        {field: 'list1', title: __('list1'),operate: false,visible:false},
                        {field: 'list2', title: __('list2'),operate: false,visible:false},
                        
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                            return Table.api.formatter.operate.call(this, value, row, index);
                        }}
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
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});