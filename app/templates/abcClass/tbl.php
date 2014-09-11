<table class="table" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<th><?=LG_ABC_CLASS_PN?></th>
		<th><?=LG_ABC_CLASS_CLASS?></th>
		<th><?=LG_ABC_CLASS_MODEL?></th>
		<th><?=LG_ABC_CLASS_GROUP?></th>
		<th><?=LG_ABC_CLASS_PRICE?></th>
		<th><?=LG_ABC_CLASS_CATEGORY?></th>
		<th><?=LG_ABC_CLASS_DESCR?></th>
	</tr>
	<?php if (!empty($classes)) { foreach ($classes as $class) {?>
	<tr>
		<td><?=$class['pn']?></td>	
		<td><?=$class['abcClass']?></td>
		<td><?=$class['model']?></td>
		<td><?=$class['group']?></td>
		<td><?=Crypter::decrypt($class['price'])?></td>
		<td><?=$class['category']?></td>
		<td><?=$class['descr']?></td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
</table>
