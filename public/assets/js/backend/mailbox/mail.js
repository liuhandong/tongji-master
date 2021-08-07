define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        },
         uploadlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'declared/declaredm/uploadlist?fa_id='+$('#fa_id').val(),
                    del_url: 'declared/declaredm/filedel',
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
                        {field: 'id', title: 'ID',sortable: true},
                        //{field: 'file_path',  title:'文件路径',operate: 'LIKE'},
                        {field: 'file_path', title: '文件路径', formatter: Table.api.formatter.url},
                        {field: 'add_time', title: '创建时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            /*buttons: [{
                                name: 'kaohe_ed',
                                text: '下载',
                                icon: 'fa fa-download',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredm/download',
                            },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa  fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'declared/declaredm/view'

                            }],*/
                            
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        send:function(){
        	 Form.api.bindevent($("form[role=form]"), function (data, ret) {
                setTimeout(function () {
                    location.href = "/tongji-test/public/mailbox/mail/send";
                }, 1000);
            });
        },
        inbox:function(){
        	    // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'mailbox/mail/inbox',
                    //del_url: 'mailbox/mail/del',
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
                        {field: 'id', title: '邮件编号',sortable: true,operate: false},
                        {field: 'priority_level_name',  title:'优先级别',operate: 'LIKE'},
                        {field: 'title', title: '邮件标题',operate: 'LIKE'},
                        {field: 'content', title: '邮件内容',operate: false},
                        {field: 'add_time', title: '发件时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                           buttons: [{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa  fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'mailbox/mail/edit'

                            },{
                                name: 'kaohe_ed',
                                text: '放入回收站',
                                icon: 'fa fa-trash',
                                classname: 'btn btn-xs btn-danger btn-ajax',
                                url: 'mailbox/mail/del_recycle',
                                confirm: '确认放入回收站?',
                                success: function (data){
                                    setTimeout(function(){location.reload()},1000);
                                },
                                error: function (data) {
                                    //return false;
                                }
                            }],
                            
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);	
        },
        outbox: function () {
        	     // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'mailbox/mail/outbox',
                    //del_url: 'declared/declaredm/filedel',
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
                        {field: 'id', title: '邮件编号',sortable: true,operate: false},
                        {field: 'priority_level_name',  title:'优先级别',operate: 'LIKE'},
                       {field: 'title', title: '邮件标题',operate: 'LIKE'},
                        {field: 'content', title: '邮件内容',operate: false},
                        {field: 'add_time', title: '发件时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa  fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'mailbox/mail/edit'

                            },{
                                name: 'kaohe_ed',
                                text: '放入回收站',
                                icon: 'fa fa-trash',
                                classname: 'btn btn-xs btn-danger btn-ajax',
                                url: 'mailbox/mail/del_recycle',
                                confirm: '确认放入回收站?',
                                success: function (data){
                                    setTimeout(function(){location.reload()},1000);
                                },
                                error: function (data) {
                                    //return false;
                                }
                            }],
                            
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Form.api.bindevent($("form[role=form]"));
        },
        recycle: function () {
	        // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'mailbox/mail/recycle',
                    del_url: 'mailbox/mail/del',
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
                        {field: 'id', title: '邮件编号',sortable: true,operate: false},
                        {field: 'priority_level_name',  title:'优先级别',operate: 'LIKE'},
                        {field: 'title', title: '邮件标题',operate: 'LIKE'},
                        {field: 'content', title: '邮件内容',operate: false},
                        {field: 'add_time', title: '发件时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa  fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'mailbox/mail/edit'

                            }],
                            
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});