function loading(show) {
	var o = $('#loading');
	show ? o.fadeIn('slow') : o.fadeOut('slow');
}
jQuery.fn.loading = function(url, params, callback) {
	loading(true);
	$(this).load(url, params, function() {
		loading(false);
		if (typeof callback == "function") callback();
	});
};

function refreshCaptcha(imgUrl) {
	$('#captchaImg').attr("src", imgUrl + "?sid="+Math.random());
	$('#captcha').val('');
}
function preSubmit() {
	loading(true);
	$("#err").html('').hide();
	$("#submitBtn").attr("disabled", "disabled");
}
function postSubmit() {
	loading(false);
	$("#submitBtn").removeAttr("disabled");
	if ($('#err').html() != '') $('#err').fadeIn('fast');
}
function ajaxHandler(result) {
    switch (result['status']) {
        case 'success':
            if (typeof result['url'] != "undefined") {
                window.location.href = result['url'];
            }
            return true;
        case 'alert':
            alert(result['msg']);
            return false;
        case 'alert2go':
            alert(result['msg']);
            if (result['url']) window.location.href = result['url'];
            return false;
        case 'go':
            if (result['url']) window.location.href = result['url'];
            return false;
        default:
            alert(result['msg']);
            return false;
    }
}

jQuery.extend({
    getting: function(url, params, callback, type) {
        loading(true);
        $.get(url, params, function(data) {
            loading(false);
            if (typeof callback == "function") callback(data);
        }, type);
    },
    posting: function (url, params, callback, type) {
        loading(true);
        $.post(url, params, function(data) {
            loading(false);
            if (typeof callback == "function") callback(data);
        }, type);
    },
    doPost: function (url, data, callback) {
        preSubmit();
        if (typeof data == "string") data = $('#'+data).serialize();
        $.posting(url, data, function(result) {
            if (typeof callback == "function") {
                callback(result);
            } else {
                ajaxHandler(result);
            }
            postSubmit();
        }, "json");
        return false;
    },
    formSubmit: function(formId, callback) {
        var form1 = typeof formId == 'object' ? $(formId) : $('#' + formId);
        var url = form1.attr('action');
        var data = form1.serialize();
        preSubmit();
        $.post(url, data, function(result) {
            if (typeof callback == "function") {
                callback(result);
            } else {
                ajaxHandler(result);
            }
            postSubmit();
        }, "json");
        return false;
    }
});

function del(url, data, callback) {
    if (confirm('您确定要删除吗?')) {
        $.doPost(url, data, callback);
    }
}