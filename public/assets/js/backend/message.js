define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'message/index',
                    add_url: 'message/add',
                    //edit_url: 'declared/declaredm/edit',
                    //del_url: 'declared/declaredm/del',
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
                        {field: 'sender',  title:'发件人',operate: 'LIKE'},
                        {field: 'recipient', title:'收件人',operate: false},
                        {field: 'content', title: '内容',operate: false},
                        {field: 'add_time', title: '发送时间',addclass: 'datetimerange', formatter: Table.api.formatter.datetime}
                      
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
        	Form.api.bindevent($("form[role=form]"));
        }

    };
    return Controller;
});