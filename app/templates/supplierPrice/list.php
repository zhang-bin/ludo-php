<?php 
$gTitle = LG_PARTSPRICE_SUPPLIER_PRICE;
include tpl('header');
$conf = Load::conf('partsPrice');
?>
<form action="<?=url('partsPrice/supplierPrice')?>" class="form-inline">
	<input type="text" value="<?=$_GET['pn']?>" name="pn" placeholder="Part Number" />
	<select name="type" class="selectpicker">
		<option value="0">All Price Type</option>
		<?php foreach ($conf['type'] as $k => $v) {?>
		<option value="<?=$k?>" <?=($k == $_GET['type']) ? 'selected' : ''?>><?=$v?></option>
		<?php }?>
	</select>
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small"/>
	<a class="excel" href="<?=url('partsPrice/supplierPriceReport')?>" id="excel" style="float:right;"></a>
</form>
<table class="table table-hover">
	<thead>
	<tr>
		<th>Part Number</th>
		<th>Supplier</th>
		<th>Price Type</th>
		<th>USD Price</th>
		<th>RMB Price</th>
		<th>Show History</th>				
	</tr>
	</thead>
	<tbody>
	<?php foreach($list as $k=>$v) {?>
	<tr>
		<td><?=$v['pn']?></td>
		<td><?=$v['supplier']?></td>
		<td><?=$conf['type'][$v['priceType']]?></td>
		<td><?=sprintf('%.2f', Crypter::decrypt($v['usd']))?></td>
		<td><?=sprintf('%.2f', Crypter::decrypt($v['rmb']))?></td>
		<td>
			<a href="<?=url('partsPrice/history/'.$v['id'])?>" class="btn btn-small btn-info redirect" target="_blank">History</a>
		</td>		
	</tr>
	<?php }?>
	<?php if(!empty($pager)){?>
	<tr>
		<td style="text-align: right;" colspan="10"><?=$pager?></td>
	</tr>
	<?php }?>
	</tbody>
</table>
<?php include tpl('footer');?>