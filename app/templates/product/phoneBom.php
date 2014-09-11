<?php
$gTitle = LG_BOM_PHONE_BOM_LIST;
include tpl('header');
?>
<form id="form1" class="form-inline">
    <select name="country" id="country" class="selectpicker" data-live-search="true">
        <option value="0">All Country</option>
        <?php foreach ($countries as $country) {?>
        <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <select name="modelName" id="modelName" class="selectpicker" data-live-search="true">
        <option value="0">All Model</option>
        <?php foreach ($models as $model) {?>
        <option value="<?=$model?>"><?=$model?></option>
        <?php }?>
    </select>
	<input type="text" name="pn" placeholder="Parts PN" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small"/>
    <a class="excel" href="<?=url('product/phoneBomReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="phoneBomList" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#phoneBomList").loading("<?=url('product/phoneBomList')?>");
	$("a.p").live('click', function(){
		$("#phoneBomList").loading(this.href, $("#form1").serializeArray());
		return false;
	});

	$("#form1").submit(function(){
		$("#phoneBomList").loading("<?=url('product/phoneBomList')?>", $("#form1").serializeArray());
		return false;
	});

    $("#excel").click(function(){
        $.posting(this.href, $("#form1").serializeArray(), function(result){
            ajaxHandler(result);
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>