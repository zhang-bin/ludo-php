layui.define(['form', 'layer', 'table'], function (exports) {
    var Common = {
        ajaxHandler: function(result) {
            var layer = parent.layer === undefined ? layui.layer : top.layer;

            if (result == null) {
                layer.msg(result);
                return false;
            }
            switch (result['status']) {
                case 'success':
                    if (typeof result['url'] != "undefined") {
                        var index = parent.layer.getFrameIndex(window.name);
                        if (typeof index != "undefined") {
                            parent.layer.close(index);
                            parent.location.href = result['url'];
                        } else {
                            window.location.href = result['url'];
                        }
                    }
                    return true;
                case 'alert':
                    layer.msg(result['msg']);
                    return false;
                case 'alert2go':
                    layer.msg(result['msg']);
                    if (result['url']) {
                        window.location.href = result['url'];
                    }
                    return false;
                case 'go':
                    if (typeof result['url'] != "undefined") {
                        var index = parent.layer.getFrameIndex(window.name);
                        if (typeof index != "undefined") {
                            parent.layer.close(index);
                            parent.location.href = result['url'];
                        } else {
                            window.location.href = result['url'];
                        }
                    }
                    return false;
                default:
                    layer.msg(result['msg']);
                    return false;
            }
        },

        tableRender: function (formId, column) {
            var layer = parent.layer === undefined ? layui.layer : top.layer,
                $ = layui.jquery,
                table = layui.table;

            var currentForm = $("#"+formId);
            var tableId = currentForm.attr("data-table-tag");

            table.render({
                elem: '#' + tableId,
                url: $("#" + tableId).attr("data-url"),
                page: true,
                limits: [10, 15, 20, 25],
                limit: 10,
                id: tableId + 'Id',
                cols: [column],
                done: function() {
                    $(".row-view").click(function() {
                        var row = $(this);
                        var index = layui.layer.open({
                            title: row.attr('data-title'),
                            type: 2,
                            content: row.attr('href'),
                            success: function(layero, index) {
                                var body = layui.layer.getChildFrame('body', index);
                                body.find(".close-layer").click(function() {
                                    layui.layer.close(index);
                                });
                            }
                        });
                        layui.layer.full(index);
                        //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
                        $(window).on("resize",function(){
                            layui.layer.full(index);
                        });
                        return false;
                    });
                }
            });

            var layerPopup = function(titleName, url) {
                var index = layui.layer.open({
                    title: titleName,
                    type: 2,
                    content: url,
                    success: function(layero, index) {
                        var body = layui.layer.getChildFrame('body', index);
                        body.find(".close-layer").click(function() {
                            layui.layer.close(index);
                        });
                    }
                });
                layui.layer.full(index);
                //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
                $(window).on("resize",function(){
                    layui.layer.full(index);
                });
            };

            table.on('tool('+tableId+')', function(obj) {
                var layEvent = obj.event,
                    data = obj.data;

                var currentTool = $(this);
                if (layEvent == 'popup') {
                    layerPopup(currentTool.attr('data-title-name'), currentTool.attr('data-url'));
                } else if (layEvent == 'tips') {
                    layer.confirm(currentTool.attr('data-title-name'), {icon: 3, title: '提示信息'}, function(index){
                        $.post(currentTool.attr('data-url'), {id: data.id}, function(result) {
                            layer.close(index);
                            layui.common.ajaxHandler(result);
                            return false;
                        }, "json");
                        return false;
                    })
                }
            });

            var addTagId = currentForm.attr("data-add-tag");
            if (addTagId != '') {
                var title = $('#'+addTagId).text();
                $('#'+addTagId).click(function() {
                    layerPopup(title, currentForm.attr('data-add-url'));
                });
            }
        }
    };

    exports('common', Common);
});