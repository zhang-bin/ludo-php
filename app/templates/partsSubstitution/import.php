<?php
$gTitle = LG_PARTS_SUBSTITUTION_IMPORT;
include tpl('header');
?>
<form id="form1" method="post">
	<table class="table table-hover table-bordered">
		<thead>
		<tr>
			<th><?=LG_PARTS_SUBSTITUTION_MODEL?></th>
			<th><?=LG_PARTS_SUBSTITUTION_REPLACE_TYPE?></th>
			<?php for ($i = 1; $i <= 5; $i++) {?>
		  	<th><?=LG_PARTS_SUBSTITUTION_PN.$i?></th>
		  	<?php }?>
			<th><?=LG_PARTS_SUBSTITUTION_REMARK?></th>
		</tr>
		</thead>
		<tbody>
		<?php for ($j = 0; $j < 3; $j++) {?>
		<tr>
	 		<td><select name="model[]">
	 			<option value="0"><?=LG_SELECT_CHOOSE?></option>
	 			<?php foreach ($modelTypes as $model) {?>
	 			<option value="<?=$model?>"><?=$model?></option>
	 			<?php }?>
	 		</select></td>
	 		<td><select name="replaceType[]">
	 			<option value="1">Equivalent</option>
	 			<option value="2">One-way</option>
	 		</select></td>
 			<?php for ($i = 1; $i <= 5; $i++) {?>
	 		<td>
				<input type="text" name="<?='pn'.$i.'[]'?>" />
			</td>
			<?php }?>
			<td>
				<input type="text" name="remark[]" />
			</td>
		</tr>
		<?php }?>
		<tr>
	 		<td colspan="8" class="tablebottom">
	 			<input type="button" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" id="submitBtn"/>
	 			<input type="button" class="btn" value="<?=LG_BTN_CANCEL?>" onclick="javascript:history.back();" />
	 		</td>
	 	</tr>
	 	</tbody>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#submitBtn").click(function(){
		if (confirm('是否确认录入')) {
			$.posting("<?=url('partsSubstitution/import')?>", $("#form1").serialize(), function(result) {
				ajaxHandler(result);
				return false;
			});
		}
		return false;
	});
});
</script>
<?php include tpl('footer');?>