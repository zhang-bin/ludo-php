<?php
$gTitle = LG_PARTS_SUBSTITUTION_LIST;
$gToolbox = '<a class="add" href="'.url('partsSubstitution/import').'">'.LG_PARTS_SUBSTITUTION_IMPORT.'</a>';
include tpl('header');
?>
<form id="form1" class="form-inline" action="<?=url('PartsSubstitution/index')?>">
	<input type="text" name="pn" value="<?=$pn?>" placeholder="<?=PN1?>" />
	<input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
	<a class="excel" href="<?=url('partsSubstitution/report')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_PARTS_SUBSTITUTION_MODEL?></th>
			<?php for ($i = 1; $i <= 10; $i++) {?>
			<th><?=LG_PARTS_SUBSTITUTION_PN.$i?></th>
			<?php }?>
			<th><?=LG_PARTS_SUBSTITUTION_REMARK?></th>
		</tr>	
	</thead>
	<tbody>	
		<?php if (!empty($list)) { foreach ($list as $v) {?>
		<tr>
			<td><?=$v['model']?></td>
			<?php for ($i = 1; $i <= 10; $i++) {?>
			<td><?=$v['pn'.$i]?></td>
			<?php }?>
			<td><?=$v['remark']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>
<?php include tpl('footer');?>