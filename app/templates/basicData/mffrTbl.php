<table class="table table-hover" width="100%">
	<thead>
		<tr>
			<th><?=LG_FAILURE_RATE_MODEL?></th>
			<th><?=LG_FAILURE_RATE_CATEGORY?></th>
			<th><?=LG_FAILURE_RATE_COUNTRY?></th>
			<th><?=LG_FAILURE_RATE_MONTH?></th>
			<th><?=LG_FAILURE_RATE_RATE?></th>
			<th><?=LG_FAILURE_SETTING_RATE_RATE?></th>
		</tr>	
	</thead>	
	<tbody>
		<?php if (!empty($mffrs)) { foreach ($mffrs as $mffr) {?>
		<tr>
			<td><?=$mffr['model']?></td>
			<td><?=$mffr['category']?></td>
			<td><?=$mffr['country']?></td>
			<td><?=$mffr['month']?></td>
			<td><?=($mffr['warranty'] == 0) ? 0 : round($mffr['qty'] / $mffr['warranty'] * 100, 2)?></td>
			<td>
				<a href="javascript:;" name="rate" data-type="text" data-pk="<?=$mffr['id']?>" title="Enter Failure Rate" class="editable editable-click"><?=floatval($mffr['rate'])?></a>
			</td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('[name=rate]').editable({
		ajaxOptions: {
		    dataType: 'json'
		},
        url: "<?=url('basicData/changeMffr')?>",
        success: function(response, newValue) {
            return response;
        }
 	});
});
</script>