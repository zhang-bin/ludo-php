<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_SC_POINT_NAME?></th>
			<th><?=LG_SC_POINT_CREATE_TIME?></th>
			<th><?=LG_OPERATION?></th>
		</tr>	
	</thead>
	<tbody>
		<?php if (!empty($points)) { foreach ($points as $point) {?>
		<tr>
			<td><?=$point['point']?></td>
			<td><?=getCurrentTime($point['createTime'])?></td>
			<td>
				<a href="<?=url('scPoint/change/'.$point['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
				<a name="del" title="<?=LG_SC_POINT_DELETE?>" href="<?=url('scPoint/del/'.$point['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
			</td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>