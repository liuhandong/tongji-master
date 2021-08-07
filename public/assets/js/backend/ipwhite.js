define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ipwhite/index',
                    add_url: 'ipwhite/add',
                    edit_url: 'ipwhite/edit',
                    del_url: 'ipwhite/del',
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
                    //operate: 'BETWEEN',
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'ip',  title:'IP地址',operate: 'LIKE'},
                        {field: 'add_time', title: '创建时间',addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate},
                      
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