define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'modular/seasonmodular/index',
                    add_url: 'modular/seasonmodular/add',
                    edit_url: 'modular/seasonmodular/edit'

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
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'rf_title',  title: __('rfTitle'),operate: 'LIKE'},
                        {field: 'code', title: __('Code'),operate: 'LIKE'},
                        {field: 'unit_name', title: __('unitId'),operate: false},
                        {field: 'rf_class', title: __('rfClass'),operate: false},
                        {field: 'rf_year', title: __('rfYear'),operate: false},
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