<?php
$gTitle = LG_MODEL_WARRANTY_LIST;
$gToolbox = '
<a id="autoWarranty" href="'.url('product/autoWarranty').'" class="add">import data from phone warranty</a>
<a href="'.url('product/warrantyChart').'"><i class="icon-bar-chart"></i> '.LG_MODEL_WARRANTY_CHART.'</a>';
include tpl('header');
?>
<form id="form1" class="form-inline">
    <select name="country[]" id="country" class="selectpicker" data-live-search="true" multiple>
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
    <input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small"/>
    <a class="excel" href="<?=url('product/warrantyReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="warrantyList" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#warrantyList").loading("<?=url('product/warrantyList')?>");
    $("a.p").live('click', function(){
        $("#warrantyList").loading(this.href, $("#form1").serializeArray());
        return false;
    });

    $("#form1").submit(function(){
        $("#warrantyList").loading("<?=url('product/warrantyList')?>", $("#form1").serializeArray());
        return false;
    });

	$("#autoWarranty").click(function(){
		$.getting(this.href, {}, function(result){
            if (result == '1') {
                $.alertSuccess('Operation Success!', function(){
                    window.location.reload();
                });
            } else {
                $.alertError('Operation Failed!');
            }
			return false;
		});
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