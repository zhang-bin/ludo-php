<?php
$gTitle = LG_PARTS_DELIVERY_CONSIGNEE;
$gToolbox = '<a href="'.url('partsDelivery/addConsignee').'" class="add">'.LG_PARTS_DELIVERY_CONSIGNEE_ADD.'</a>';
include tpl('header');
?>
<div id="partsList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#partsList").loading("<?=url('partsDelivery/consigneeList')?>");
});
</script>
<?php include tpl('footer');?>