<?php
$gTitle = LG_SC_POINT;
$gToolbox = '<a class="add" href="'.url('scPoint/add').'">'.LG_SC_POINT_ADD.'</a>';
include tpl('header');
?>
<form id="form1" class="form-inline">
	<input type="text" name="name" id="name" placeholder="<?=LG_SC_POINT_NAME?>" ></input>	
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
</form>
<div id="point"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#point").loading("<?=url('scPoint/tbl')?>");
	$("#form1").submit(function(){
		$("#point").loading("<?=url('scPoint/tbl')?>", $(this).serializeArray());
		return false;
	});
	
	$("a.p").live('click', function(){
		$("#point").loading(this.href, $("#form1").serializeArray());
		return false;
	});
});
</script>
<?php include tpl('footer');?>