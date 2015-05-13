</div>
<footer class="footer">
	<div class="container">
		<p>
			Ludo-PHP © 2014-2015 All Rights Reserved. |
			<?php if (DEBUG) { ?><span>Process time: <?=sprintf('%.3f', (microtime(true) - SYS_START_TIME)*1000)?> ms.</span>
			<a href="<?=LD_UPLOAD_URL?>/debug_console.php" target="debug_console">debug_console</a>

            <?php
            $xhprof_data = xhprof_disable();
            include_once SITE_ROOT . '/../xhprof_lib/utils/xhprof_lib.php';
            include_once SITE_ROOT . '/../xhprof_lib/utils/xhprof_runs.php';

            $xhprof_runs = new XHProfRuns_Default();
            $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
            ?>
            <a href="<?=SITE_URL.'/public/xhprof_html/index.php?run='.$run_id.'&source=xhprof_foo'?>"target="xhprof">xhprof</a>
			<?php } ?>
		</p>
		<p><a href="mailto:hunter.zhangbin@gmail.com"> System Support </a></p>
	</div>
</footer>
<div class="modal-del modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?=DELETE?></h4>
            </div>
            <div class="modal-body">
                <p class="text-center"><?=CONFIRM_DELETE?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=CANCEL?></button>
                <button type="button" class="btn btn-primary" id="confirmDel"><?=CONFIRM?></button>
            </div>
        </div>
    </div>
</div>
<?php View::startJs();?>
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
    $("form").each(function(){
        $(this).append("<input type='hidden' name='_token' value='<?=csrf_token()?>' />")
    });

    $(".table").on("click", "a[name=del]", function(){
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
		}, "json");
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
<?php View::endJs();?>
<?php View::loadCss();?>
<?php View::loadJs();?>
</body>
</html>