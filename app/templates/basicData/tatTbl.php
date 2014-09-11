<?php 
$conf = Load::conf('Setting');
?>
<table class="table table-hover">
	<thead>
	<tr>
		<th><?=LG_TAT_FROM?></th>
		<th><?=LG_TAT_TO?></th>
		<th><?=LG_TAT_TRANS_WAY?></th>
		<th><?=LG_TAT_DAY?></th>
		<th><?=LG_TAT_FEE?></th>
		<th><?=LG_TAT_TYPE?></th>
		<th><?=LG_OPERATION?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($tats)) { foreach ($tats as $tat) {?>
	<tr>
		<td><?=$tat['fromPoint']?></td>
		<td><?=$tat['toPoint']?></td>
		<td><?=$conf['transport'][$tat['transportWay']]?></td>
		<td><?=$tat['consumeDays']?></td>
		<td><?=$tat['fee']?></td>
		<td><?=$conf['tatType'][$tat['type']]?></td>
		<td>
			<a href="<?=url('basicData/changeTat/'.$tat['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
			<a name="del" title="<?=LG_TAT_DELETE?>" href="<?=url('basicData/delTat/'.$tat['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
		</td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>