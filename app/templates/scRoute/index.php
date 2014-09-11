<?php
$gTitle = LG_SC_ROUTE;
$gToolbox = '<a class="add" href="'.url('scRoute/add').'">'.LG_SC_ROUTE_ADD.'</a>';
include tpl('header');
$conf = Load::conf('Setting');
?>
<form id="form1" class="form-inline">
	<input type="text" name="name" id="name" class="span3" placeholder="<?=LG_SC_ROUTE_NAME?>"  />
	<select name="poType" id="poType" class="selectpicker">
		<option value="0">All PO Type</option>
		<?php foreach ($conf['poType'] as $k => $v) {?>
		<option value="<?=$k?>"><?=$v?></option>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
</form>
<div id="route" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#route").loading("<?=url('scRoute/tbl')?>");
	$("#form1").submit(function(){
		$("#route").loading("<?=url('scRoute/tbl')?>", $(this).serializeArray());
		return false;
	});
	
	$("a.p").live('click', function(){
		$("#route").loading(this.href, $("#form1").serializeArray());
		return false;
	});
});
function del(id) {
	if (confirm('<?=LG_DELETE_CONFIRM?>')) {
        $.post("<?=url('scRoute/del/')?>"+id, {}, function(result) {
          	if(ajaxHandler(result)) return;
        });
 	}
}
</script>
<?php include tpl('footer');?>