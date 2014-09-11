<?php
$gTitle = LG_FAILURE_RATE_IMPORT;
include tpl('header');
Load::js('bootstrap-datetimepicker');
Load::js('uploadify');
?>
<form id="uploadMffr" class="form-horizontal">
	<div class="control-group">
		<label class="control-label" for="month"><strong><?=LG_FAILURE_RATE_MONTH?></strong></label>
		<div class="controls">
			<input type="text" name="month" id="month" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="file"><strong><?=LG_PURCHASE_FILE?></strong></label>
		<div class="controls">
			<input id="file" name="file" type="file" />
			<a href="<?=rurl('static/mffr.xlsx')?>" target="_blank">Sample File</a>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
	 		<input type="submit" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" id="submitBtn"/>
	 		<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
	 	</div>
	 </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#file').uploadify({
		'width': '80',
		'swf'      : '<?=rurl('img/uploadify/uploadify.swf')?>',
		'uploader' : '<?=url('basicData/uploadMffr')?>',
		'auto' : false,
		'buttonText': 'Browse',
		'onUploadSuccess': function(file, data, response){
			ajaxHandler(data);
		}
	});
	
	$("#uploadMffr").submit(function(){
		$('#file').uploadify("settings", "formData", {"month": $("#month").val()});
		$('#file').uploadify("upload", '*');
		return false;
	});

	$("#month").datetimepicker({
		startView: 3,
		minView: 3,
        maxView: 3,
        format: 'yyyy-mm',
        todayBtn: 1,
		autoclose: 1,
		todayHighlight: 1
	});
});
</script>
<?php include tpl('footer');?>