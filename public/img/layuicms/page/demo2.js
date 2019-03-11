layui.use(['form', 'layer'], function () {
    var form = layui.form
    layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery;

    form.on("submit(addUser)", function (data) {
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候', {icon: 16, time: false, shade: 0.8});

        $.post("/AfDemo/submit",{
            userName : $(".userName").val(),  //登录名
            userEmail : $(".userEmail").val(),  //邮箱
            userSex : data.field.sex,  //性别
            userDesc : $(".userDesc").html(),    //用户简介
        },function(res){
            console.log(res);
        })

        //关闭loading
        top.layer.close(index);
    })
})