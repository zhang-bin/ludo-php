<?php
$gTitle = LG_MODIFY_USER_ACCOUNT;
include tpl('header');
?>
<form id="form1" method="post" name="form1">
	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="formtable">
	<tr>
	  	<th colspan="2"><?=$gTitle?></th>
	</tr>
	<tr>
		<td valign="top"><?=LG_COMPLETE_UNAME?>：</td>
		<td valign="top" ><?=$info['uname']?></td>
	</tr>
	<tr>
		<td valign="top"><?=LG_COMPLETE_OLDPASSWORD?>：</td>
		<td valign="top"><input type="password" name="password" /></td>
	</tr>
	<tr>
		<td valign="top"><?=LG_COMPLETE_NEWPASSWORD?>：</td>
		<td valign="top"><input type="password" name="newpassword" /></td>
	</tr>
	<tr>
		<td valign="top"><?=LG_COMPLETE_PASSWORD1?>：</td>
		<td valign="top"><input type="password" name="newpassword1" /></td>
	</tr>
	<tr>
		<td class="tablebottom">&nbsp;</td>
		<td class="tablebottom">
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="button">
			&nbsp;
			<a href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</td>
	</tr>
</table>
<input type="hidden" name="id" value="<?=$info['id']?>"></input>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$("#form1").submit(function() {
		$("#submitBtn").attr("disabled", "disabled");
		$.post("<?= url('user/changePw')?>", $("#form1").serialize(), function(result) {
			$("#submitBtn").removeAttr("disabled");
    		if(ajaxHandler(result)) return;
     	});
     	return false;
	});
});
</script>
<?php include tpl('footer')?>