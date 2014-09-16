</div>
<footer class="footer">
	<div class="container">
		<p>
			Lenovo Mobile Service System(CRM) © 2012-2013 All Rights Reserved. |
			<?php if (DEBUG) { ?><span>Process time: <?=sprintf('%.3f', (microtime(true) - SYS_START_TIME)*1000)?> ms.</span>
			<a href="<?=LD_UPLOAD_URL?>/debug_console.php" target="debug_console">debug_console</a>
			<?php } ?>
		</p>
		<p><a href="mailto:support@lenovomobileservice.com"> System Support </a> |<a href="termsofuse.php">  Terms of Use </a></p>
	</div>
</footer>
<div class="modal-del modal hide fade">
    <div class="modal-header">
   		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   		<h4 class="modal-title">Delete</h4>
   	</div>
	<div class="modal-body">
		<p style="text-align: center;"><?=LG_DELETE_CONFIRM?></p>
	</div>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=LG_BTN_CANCEL?></button>
        <button type="button" class="btn btn-primary" id="confirmDel"><?=LG_BTN_CONFIRM?></button>
	</div>
</div>
<script type="text/javascript">
jQuery.extend({
	alertSuccess: function(msg, callback) {
		$("#message-drawer").find(".message-text").text(msg).removeClass("text-error-important");
		$("#message-drawer").show().delay(1500).fadeOut(1000, callback);
	},
	alertError: function(msg) {
		$("#message-drawer").find(".message-text").text(msg).addClass("text-error-important");
		$("#message-drawer").show();
	}
});
$(document).ready(function(){
	$.placeholder.shim();
    $("input.switch").bootstrapSwitch("state", true, true);
	$("a[name=del]").live("click", function(){
		$(".modal-del").modal();
		$(".modal-del .modal-title").text($(this).attr("title"));
		if ($(this).attr("body") != '') {
			$(".modal-del .modal-body p").text($(this).attr("body"));
		}
		$("#confirmDel").attr("href", this.href);
	 	return false;
	});
	$("#confirmDel").click(function(){
		$.post($(this).attr("href"), {}, function(result) {
          	if(ajaxHandler(result)) return;
          	return false;
		});
		return false;
	});
	$("form.form").submit(function(){
		preSubmit();
		$.post($(this).attr("action"), $(this).serialize(), function(result) {
			ajaxHandler(result);
			postSubmit();
		}, "json");
		return false;
	});
	$('.selectpicker').selectpicker();
});
</script>
</body>
</html>