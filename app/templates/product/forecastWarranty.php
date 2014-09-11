<?php
$gTitle = LG_FORECAST_MODEL_WARRANTY_LIST;
$gToolbox = '<a href="'.url('product/importForecastWarranty').'" class="add">'.LG_FORECAST_MODEL_WARRANTY_IMPORT.'</a>';
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
    <select name="batch" id="batch" class="selectpicker" data-live-search="true">
        <option value="0">All Batch</option>
        <?php foreach ($batches as $batch) {?>
            <option value="<?=$batch?>"><?=$batch?></option>
        <?php }?>
    </select>
    <input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small"/>
    <a class="excel" href="<?=url('product/forecastWarrantyReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="warrantyList" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#warrantyList").loading("<?=url('product/forecastWarrantyList')?>");
    $("a.p").live('click', function(){
        $("#warrantyList").loading(this.href, $("#form1").serializeArray());
        return false;
    });

    $("#form1").submit(function(){
        $("#warrantyList").loading("<?=url('product/forecastWarrantyList')?>", $("#form1").serializeArray());
        return false;
    });
});
</script>
<?php include tpl('footer');?>