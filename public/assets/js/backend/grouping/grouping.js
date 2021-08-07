define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'grouping/grouping/index',
                    add_url: 'grouping/grouping/add',
                    edit_url: 'grouping/grouping/edit',
                    del_url: 'grouping/grouping/del',
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
                showExport: true,//导出
                exportTypes: [ 'excel'],
                columns: [
                    [
                        {field: 'id', title: 'ID'},
                        {field: 'company_park_name',  title: __('company_park_name'),operate: 'LIKE'},
                        {field: 'company_name',  title: __('company_name'),operate: 'LIKE'},
                        {field: 'company_telephone', title: __('company_telephone'),operate: false},
                        {field: 'company_mailbox', title: __('company_mailbox'),operate: false},
                        {field: 'company_person_in_charge', title: __('company_person_in_charge'),operate: false},
                        {field: 'add_time', title: __('add_time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
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