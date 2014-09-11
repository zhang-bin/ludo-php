<?php
$gTitle = LD_HOLIDAY_IMPORT; 
include tpl('header');
Load::js('uploadify');
?>
<form method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label" for="vendorId"><strong><?=LG_HOLIDAY_VENDOR?></strong></label>
		<div class="controls">
			<select name="vendorId" id="vendorId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($vendors as $vendor) {?>
				<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="excel"><strong><?=LG_HOLIDAY_FILE?></strong></label>
		<div class="controls">
			<input id="excel" name="excel" type="file" />
	 		<a href="<?=rurl('static/holiday.xlsx')?>" target="_blank">Sample File</a>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
	 		<input type="submit" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" id="submitBtn"/>
	 		<input type="button" class="btn" value="<?=LG_BTN_CANCEL?>" onclick="javascript:history.back();" />
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	var i = j = 0;
	$('#excel').uploadify({
		'width': '150',
		'swf'      : '<?=rurl('img/uploadify/uploadify.swf')?>',
		'uploader' : '<?=url('holiday/import')?>',
		'auto' : false,
		'uploadLimit': 1,
		'onSelect': function() {
			i++;
		},
		'onCancel': function() {
			i--;
		},
		'onUploadSuccess': function(){
			j++;
			if (i == j) {
				window.location.href = "<?=url('holiday/index')?>";
			}
		}
	});

	$("#form1").submit(function(){
		if (i > 0) {
			$("#excel").uploadify("settings", "formData", {"vendorId": $("#vendorId").val()});
			$('#excel').uploadify("upload", '*');
		} else {
			alert("请选择商品文件!");
		}
		return false;
	});
});
</script>
<?php include tpl('footer');?>