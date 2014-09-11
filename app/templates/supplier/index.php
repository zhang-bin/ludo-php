<?php
$gTitle = LG_SUPPLIER;
$gToolbox = '<a href="'.url('supplier/add').'" class="add">'.LG_SUPPLIER_ADD.'</a>';
include tpl('header');
?>
<div id="supplierList"></div>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#supplierList").loading("<?=url('supplier/tbl/'.$_SESSION[USER]['page'])?>");

	$("#supplierForm").submit(function(){
		$("#supplierList").loading("<?=url('supplier/tbl')?>", $(this).serializeArray());
		return false;
	});
});
</script>
<?php include tpl('footer');?>