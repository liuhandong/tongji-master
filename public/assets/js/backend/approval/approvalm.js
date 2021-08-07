define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'approval/approvalm/index',
                    //edit_url: 'approval/approvalm/edit'

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
                        {field: 'id', title: 'ID',sortable: true},
                        {field: 'company_park_name',  title:'填报单位',operate: 'LIKE',sortable: true},
                        {field: 'mon', title: '报表月份',operate: 'LIKE',sortable: true},
                        {field: 'nickname', title:'创建人',operate: false,sortable: true},
                        {field: 'add_time', title: '创建时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange',operate: false,sortable: true},
                       // {field: 'sb_time', title: '填报时间', formatter: Table.api.formatter.datetime, addclass: 'datetimerange',operate: false},
                        {field: 'file', title: '附件', operate: false, formatter: Controller.api.formatter.icon},
                        {field: 'is_key_name', title: '状态', operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'exam',
                                text: '审批通过',
                                icon: 'fa fa-list',
                                classname: 'btn btn-xs btn-warning btn-ajax',
                                url: 'approval/approvalm/yessp',
                                confirm: '确认通过?',
                                success: function (data){
                                    setTimeout(function(){location.reload()},1000);
                                },
                                error: function (data) {
                                    //return false;
                                }
                            },{
                                name: 'kaohe_ed',
                                text: '导出EXCEL',
                                icon: 'fa fa-file',
                                classname: 'btn btn-xs btn-warning',
                                url: 'declared/declaredm/print_excel'

                            },{
                                name: 'kaohe_ed',
                                text: '审批驳回',
                                icon: 'fa fa-file',
                                classname: 'btn btn-xs btn-warning btn-dialog',
                                url: 'approval/approvalm/nosp',

                            },{
                                name: 'kaohe_ed',
                                text: '查看',
                                icon: 'fa fa-pencil',
                                classname: 'btn btn-xs btn-success btn-dialog',
                                url: 'approval/approvalm/edit'

                            }],
                            formatter: Table.api.formatter.operate
                        }
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
        },
        nosp: function () {
            Form.api.bindevent($("form[role=form]"));
        },api: {
            formatter: {
                title: function (value, row, index) {
                    return !row.ismenu || row.status == 'hidden' ? "<span class='text-muted'>" + value + "</span>" : value;
                },
                name: function (value, row, index) {
                    return !row.ismenu || row.status == 'hidden' ? "<span class='text-muted'>" + value + "</span>" : value;
                },
                icon: function (value, row, index) {
                    return '<span class="' + (!row.ismenu || row.status == 'hidden' ? 'text-muted' : '') + '"><i class="' + value + '"></i></span>';
                },
                subnode: function (value, row, index) {
                    return '<a href="javascript:;" data-toggle="tooltip" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs '
                        + (row.haschild == 1 || row.ismenu == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                }
            },
            bindevent: function () {
                $(document).on('click', "input[name='row[ismenu]']", function () {
                    var name = $("input[name='row[name]']");
                    name.prop("placeholder", $(this).val() == 1 ? name.data("placeholder-menu") : name.data("placeholder-node"));
                });
                $("input[name='row[ismenu]']:checked").trigger("click");

                var iconlist = [];
                var iconfunc = function () {
                    Layer.open({
                        type: 1,
                        area: ['99%', '98%'], //宽高
                        content: Template('chooseicontpl', {iconlist: iconlist})
                    });
                };
                Form.api.bindevent($("form[role=form]"), function (data) {
                    Fast.api.refreshmenu();
                });
                $(document).on('click', ".btn-search-icon", function () {
                    if (iconlist.length == 0) {
                        $.get(Config.site.cdnurl + "/assets/libs/font-awesome/less/variables.less", function (ret) {
                            var exp = /fa-var-(.*):/ig;
                            var result;
                            while ((result = exp.exec(ret)) != null) {
                                iconlist.push(result[1]);
                            }
                            iconfunc();
                        });
                    } else {
                        iconfunc();
                    }
                });
                $(document).on('click', '#chooseicon ul li', function () {
                    $("input[name='row[icon]']").val('fa fa-' + $(this).data("font"));
                    Layer.closeAll();
                });
                $(document).on('keyup', 'input.js-icon-search', function () {
                    $("#chooseicon ul li").show();
                    if ($(this).val() != '') {
                        $("#chooseicon ul li:not([data-font*='" + $(this).val() + "'])").hide();
                    }
                });
            }
        }
    };
    return Controller;
});