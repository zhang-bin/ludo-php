<?php
$gTitle = LG_PARTS_DELIVERY_COMPARE_RESULT;
include tpl('header');
?>
<p>
	<a class="excel" href="<?=url('partsDelivery/compareReport')?>" target="_blank"></a>
	<a class="button" href="<?=url('partsDelivery/compareConfirm')?>" id="confirm">Confirm</a>
</p>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_PO_CODE?></th>
			<th><?=LG_PN?></th>
			<th><?=LG_PN_QTY?></th>
			<th><?=LG_PN_CFM_QTY?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_SESSION['po'] as $po) {?>
		<?php 
		if ($po['qty'] == $po['cfm']) {
			$class = 'success';
		} else {
			$class = 'warning';
		}
		?>
		<tr class="<?=$class?>">
			<td><a href="<?=url('purchaseOrder/view/'.$po['id'])?>" target="_blank"><?=$po['code']?></a></td>
			<td><?=$po['pn']?></td>
			<td><?=$po['qty']?></td>
			<td><?=$po['cfm']?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$("#confirm").click(function(){
		$.posting(this.href, {}, function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>