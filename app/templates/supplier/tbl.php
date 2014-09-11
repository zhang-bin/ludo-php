<table class="table table-hover">
	<thead>
	<tr>
		<th><?=LG_SUPPLIER_NAME?></th>
		<th><?=LG_SUPPLIER_DEFAULT_SUPPLIER?></th>
		<th><?=LG_SUPPLIER_CREATE_TIME?></th>
		<th><?=LG_OP?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($suppliers)) {foreach($suppliers as $supplier) {?>
		<tr>
			<td><?=$supplier['supplier']?></td>
			<td><?=$supplier['isDefault'] ? 'Yes' : 'No'?></td>
			<td><?=$supplier['createTime']?></td>
			<td>
				<a href="<?=url('supplier/change/'.$supplier['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
				<a href="<?=url('supplier/del/'.$supplier['id'])?>" name="del" class="btn btn-warning btn-small" title="<?=LG_SUPPLIER_DEL?>"><?=LG_BTN_DEL?></a>
			</td>
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
		$("#supplierList").loading(this.href);
		return false;
	});
});
</script>