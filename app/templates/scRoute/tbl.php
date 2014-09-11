<?php
$conf = Load::conf('Setting');
?>
<table class="table table-hover" width="100%">
	<thead>
		<tr>
			<th><?=LG_SC_ROUTE_NAME?></th>
			<th><?=LG_SC_ROUTE_COUNTRY?></th>
			<th><?=LG_SC_ROUTE_LT_PO?></th>
			<th><?=LG_SC_ROUTE_ROUTE?></th>
			<th><?=LG_SC_ROUTE_TOTAL_DAYS?></th>
			<th><?=LG_SC_ROUTE_REMARK?></th>
			<th><?=LG_SC_ROUTE_CREATE_TIME?></th>
			<th><?=LG_OPERATION?></th>
		</tr>	
	</thead>
	<tbody>	
		<?php if (!empty($routes)) { foreach ($routes as $route) {?>
		<tr>
			<td><?=$route['name']?></td>
			<td><?=$route['country']?></td>
			<td><?=$conf['poType'][$route['poType']]?></td>
			<td><?=$route['route']?></td>
			<td><?=$route['totalDays']?></td>
			<td><?=$route['remark']?></td>
			<td><?=getCurrentTime($route['createTime'])?></td>
			<td>
				<a href="<?=url('scRoute/change/'.$route['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
				<a name="del" title="<?=LG_SC_ROUTE_DELETE?>" href="<?=url('scRoute/del/'.$route['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
			</td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>