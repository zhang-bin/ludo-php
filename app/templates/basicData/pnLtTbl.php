<table class="table table-hover" width="100%">
	<thead>
	<tr>
		<th><?=LG_PURCHASE_PN?></th>
		<th><?=LG_LT_LEAD_TIME?></th>
		<th><?=LG_OPERATION?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($lts)) { foreach ($lts as $lt) {?>
	<tr>
		<td><?=$lt['pn']?></td>
		<td><?=$lt['leadTime']?></td>
		<td>
			<a href="<?=url('basicData/changePnLt/'.$lt['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
			<a name="del" title="<?=LG_PN_LT_DELETE?>" href="<?=url('basicData/delPnLt/'.$lt['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
		</td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>