<?php
$conf = Load::conf('Setting');
?>
<table class="table table-hover table-bordered table-condensed">
	<thead>
		<tr>
			<th></th>
			<th><?=LG_SC_ROUTE_LT_SUPPLIER?></th>
			<th><?=LG_SC_ROUTE_LT_PO?></th>
			<th><?=LG_SC_ROUTE_LT_LT?></th>
		</tr>	
	</thead>
	<tbody>	
		<?php if (!empty($lts)) { foreach ($lts as $lt) {?>
		<tr>
			<td><input type="checkbox" name="lt[]" value="<?=$lt['id']?>" <?=($lt['checked']) ? 'checked' : ''?> /></td>
			<td><?=$lt['supplier']?></td>
			<td><?=$conf['poType'][$lt['poType']]?></td>
			<td><?=$lt['leadTime']?></td>
		</tr>
		<?php }}?>
	</tbody>
</table>