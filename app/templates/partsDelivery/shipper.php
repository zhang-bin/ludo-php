<?php
$gTitle = LG_PARTS_DELIVERY_SHIPPER;
$gToolbox = '<a href="'.url('partsDelivery/addShipper').'" class="add">'.LG_PARTS_DELIVERY_SHIPPER_ADD.'</a>';
include tpl('header');
?>
<div id="partsList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#partsList").loading("<?=url('partsDelivery/shipperList')?>");
});
</script>
<?php include tpl('footer');?>