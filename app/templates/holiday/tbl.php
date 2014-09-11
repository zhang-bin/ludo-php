<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_HOLIDAY_VENDOR?></th>
			<th><?=LG_HOLIDAY_DAY?></th>
			<th><?=LG_HOLIDAY_REMARK?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($holidays)) {foreach($holidays as $holiday) {?>
		<tr>
			<td><?=$holiday['countryShortName']?></td>
			<td><?=$holiday['holiday']?></td>
			<td><?=$holiday['remark']?></td>
		</tr>
		<?php }}?>
		<?php if(!empty($pager)){?>
		<tr>
			<td style="text-align: right;" colspan="20"><?=$pager?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$("a.p").click(function(){
		$("#holidayList").loading(this.href, $("#holidayForm").serializeArray());
		return false;
	});
});
</script>