//==trim
String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g,"");}
String.prototype.ltrim = function() {return this.replace(/^\s+/,"");}
String.prototype.rtrim = function() {return this.replace(/\s+$/,"");}

//==SITE ROOT
var t=document.getElementsByTagName("SCRIPT");
t = t[t.length-1].src;
var LD_JS_ROOT = (t.lastIndexOf("/") < 0) ? "." : t.substring(0, t.lastIndexOf("/"));
t = null;

//==loading and getting and posting
function loading(show) {
	var o = $('#loading');
//	o.is(':hidden') ? o.fadeIn('slow') : o.fadeOut('slow');
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
jQuery.fn.myOverlay = function(callback) {
	 myTrigger = $(this).overlay({ 	 
        expose: '#444', 
        effect: 'apple', 
 
        onBeforeLoad: function() { 	 
            // grab wrapper element inside content 
            var wrap = this.getContent().find(".contentWrap");
            wrap.empty();
            // load the page specified in the trigger 
            wrap.load(this.getTrigger().attr("href")); 
        },
        onClose: function() {
        	if ((typeof myTriggerSvrList != "undefined") && (myTriggerSvrList != "")) {
        		if (typeof callback == 'function') callback(myTriggerSvrList);
        	}
        }
	});
};

//==default form submit button
function enterBtn(event, btn) { 
	if (event.keyCode==13)	{
		$('#'+btn).click(); 
		return false;
	}
}
function enter(event) {
	if (event.keyCode==13)	{
		//console.log($(this).next("a.button"));
		$(this).next("a.button").click();
		return false;
	}
}

//==smartFocus
jQuery.fn.smartFocus = function(text) {
	$(this).val(text).focus( function() {
		if ($(this).val() == text) {
			$(this).val('');
		}
	}).blur( function() {
		if ($(this).val() == '') {
			$(this).val(text);
		}
	});
};
function refreshCaptcha(imgUrl) { 
	$('#captchaImg').attr("src", imgUrl + "?sid="+Math.random());
	$('#captcha').val('');
}
function preSubmit() {
//	$("#loading").fadeIn('slow');
	loading(true);
	$("#err").html('').hide();
	$("#submitBtn").attr("disabled", "disabled");
}
function postSubmit(haveCaptcha) {
	//hide loading and enable submit button and hide err block
//	$("#loading").fadeOut('slow');
	loading(false);
	$("#submitBtn").removeAttr("disabled");
	if ($('#err').html() != '') 	$('#err').fadeIn('fast');
}
function ajaxHandler(result, url, alertError) {
	var result = result.split('|');
	var title = result[0].trim();
	var info = result[1];
	switch (title) {
		case 'success':
			if (info != null) url = info;
			if (url && url.toLowerCase().indexOf('http://') == 0 ) window.location.href = url;
			if (url) return url;
			return true;
		case 'error':
			if (alertError) {
				alert(info);
			} else {
				$('#err').html(info);
				$('#err').show();
			}
			return false;
		case 'alert':
			alert(info);
			return false;
		case 'alert2go':
			alert(info);
			if (result[2]) window.location.href=result[2];
			return false;
		case 'go':
			window.location.href=info;
			return false;
		case 'view':
			$("#resultView").empty().show().html(info).fadeOut(5000);
			if (result.length == 3) {
				return result[2];
			} else {
				return false;
			}
		default:
			if (info != null && title.indexOf('<') == -1 && $('#'+title+'Tip').length > 0) {
				$.formValidator.setFailState(title+'Tip', info);
			} else {
				alert(result);
			}
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
function stopDefault( e ) { 
    if ( e && e.preventDefault ) // Prevent the default browser action (W3C) 
        e.preventDefault(); 
    else //A shortcut for stoping the browser action in IE 
        window.event.returnValue = false; 
    return false; 
}

function getCheckedSvr(name) {
    var data='';
    var comma='';
    $("#"+name).find("a").each(function(){
    	data+=comma+this.id;
		comma = ',';
    });
    return data;
}

function getChecked(c){
    var data='';
    var comma='';
    $(":checkbox[name="+c+"][checked]").each(function(){
		data+=comma+$(this).val();
		comma = ',';
    });
    return data;
}


function checkAll(p,c) {
	if($(":checkbox[name="+p+"]").attr("checked")==true){   
       $(":checkbox[name="+c+"]").each(function(){
    	   $(this).attr("checked",true);
       });
    }else{   
    	$(":checkbox[name="+c+"]").each(function(){
    		$(this).removeAttr("checked");
    	});
    }   
}

$(".language").live('click', function() {
	language = $(this).attr('type');
	lang = 'lang';
	if(getCookie(lang) != null) delCookie(lang);
	setCookie(lang, language);
	location.reload();
});

function getCookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}
function setCookie(cookieName, cookieValue) {
	var expires = new Date();
	expires.setTime(expires.getTime() + 3600000000);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue) + "; expires=" + expires.toGMTString()+"; path=/"; 
}

function delCookie(name)//删除cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}