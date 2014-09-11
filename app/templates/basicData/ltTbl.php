<?php 
$conf = Load::conf('Setting');
?>
<table class="table table-hover" width="100%">
	<thead>
	<tr>
		<th><?=LG_LT_SUPPLIER?></th>
		<th><?=LG_LT_PO_TYPE?></th>
		<th><?=LG_LT_LEAD_TIME?></th>
		<th><?=LG_OPERATION?></th>
	</tr>	
	</thead>	
	<tbody>
	<?php if (!empty($lts)) { foreach ($lts as $lt) {?>
	<tr>
		<td><?=$lt['supplier']?></td>
		<td><?=$conf['poType'][$lt['poType']]?></td>
		<td><?=$lt['leadTime']?></td>
		<td>
			<a href="<?=url('basicData/changeLt/'.$lt['id'])?>" class="btn edit jump" title="<?=LG_BTN_EDIT?>"></a>
			<a href="<?=url('basicData/delLt/'.$lt['id'])?>" class="btn del" title="<?=LG_BTN_DEL?>"></a>
		</td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>