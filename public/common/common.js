function beforeSubmit() {
    $("#submitBtn").attr("disabled", "disabled");
}

function afterSubmit() {
    $("#submitBtn").removeAttr("disabled");
}

function msgAlert(msg) {
    $("#alert-box").children('p').text(msg);
    $("#alert-box").fadeIn(1000).delay(5000).fadeOut(1000);
}

function ajaxHandler(result) {
    if (result == null) {
        msgAlert(result);
        return false;
    }
    switch (result['status']) {
        case 'success':
            if (typeof result['url'] != "undefined") {
                window.location.href = result['url'];
            }
            return true;
        case 'alert':
            msgAlert(result['msg']);
            return false;
        case 'alert2go':
            alert(result['msg']);
            if (result['url']) {
                window.location.href = result['url'];
            }
            return false;
        case 'go':
            if (result['url']) {
                window.location.href = result['url'];
            }
            return false;
        default:
            if (result['msg']) {
                msgAlert(result['msg']);
            }
            return false;
    }
}

jQuery.extend({
    posting: function (url, params, callback, type) {
        $.post(url, params, function(data) {
            if (typeof callback == "function") {
                callback(data);
            }
        }, type);
    },
    formSubmit: function (formId, callback) {
        var form1 = typeof formId == 'object' ? $(formId) : $('#' + formId);
        var url = form1.attr('action');
        var data = form1.serialize();
        beforeSubmit();
        $.posting(url, data, function(result) {
            if (typeof callback == "function") {
                callback(result);
            } else {
                ajaxHandler(result);
                return false;
            }
        }, "json");
        return false;
    }
});