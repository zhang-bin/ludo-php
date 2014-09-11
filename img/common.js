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
	}
});

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
        default:
            alert(result['msg']);
            return false;
    }
}
/**
 * submit data to target url with ajax 
 * @param url
 * @param String|Object data could be a form ID, or object of all data to be submited
 * @param callback
 * @return
 */
function doPost(url, data, callback) {
	preSubmit();
	if (typeof data == "string") data = $('#'+data).serialize();
	$.posting(url, data, function(result) {
		 if (typeof callback == "function") {
			 callback(result);
			 postSubmit();
		 } else {
			 ajaxHandler(result);
			 postSubmit();
		 }
	});
	return false;
}
 /**
  * do form submit with ajax
  * @param formId
  * @return
  */
 function formSubmit(formId, callback) {
	 var form1 = typeof formId == 'object' ? $(formId) : $('#' + formId);
	 var url = form1.attr('action');
	 var data = form1.serialize();
	 preSubmit();
	 $.post(url, data, function(result) {
		 if (typeof callback == "function") {
			 callback(result);
			 postSubmit();
		 } else {
			 ajaxHandler(result);
			 postSubmit();
		 }
	 });
	 return false;
}