<?php 
$gTitle = LG_SETTING_PARTS_GROUP;
$gToolbox = '<a href="'.url('basicData/addPartsGroup').'" class="add">'.LG_PARTS_GROUP_ADD.'</a>';
include tpl('header');
?>

<table class="table table-hover">
	<thead>
	<tr>
		<th>Parts Category</th>
		<th><?=LG_OPERATION?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($groups)) { foreach ($groups as $group) {?>
	<tr>
		<td><?=$group['partsGroupName']?></td>
		<td>
			<a href="<?=url('basicData/changePartsGroup/'.$group['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
			<a name="del" title="<?=LG_PARTS_GROUP_DELETE?>" href="<?=url('basicData/delPartsGroup/'.$group['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
		</td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>
<?php include tpl('footer');?>