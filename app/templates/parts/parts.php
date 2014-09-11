<?php
$gTitle = LG_PARTS_LIST;
include tpl('header');
?>
<form id="form1" class="form-inline">
    <select name="partsCategory" id="partsCategory" class="selectpicker" data-live-search="true">
        <option value="0">All Parts Category</option>
        <?php foreach ($partsCategories as $partsCategory) {?>
            <option value="<?=$partsCategory['id']?>"><?=$partsCategory['partsGroupName']?></option>
        <?php }?>
    </select>
	<input type="text" name="pn" placeholder="PN" class="span2" />
	<input type="text" name="name" placeholder="Parts Name" class="span3" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small" />
    <input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('parts/partsReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="partsList" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#partsList").loading("<?=url('parts/partsList')?>");
	$("a.p").live('click', function(){
		$("#partsList").loading(this.href, $("#form1").serializeArray());
		return false;
	});

	$("#form1").submit(function(){
		$("#partsList").loading("<?=url('parts/partsList')?>", $("#form1").serializeArray());
		return false;
	});
    $("#reset").click(function(){
        $.posting("<?=url('parts/partsChecking')?>", {}, function(result){
            if (result == '1') {
                $.alertSuccess("Reset Parts Feature Success!");
                $("#partsList").loading("<?=url('parts/partsList')?>", $("#form1").serializeArray());
            } else {
                $.alertError("Reset Parts Feature Failed!");
            }
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>