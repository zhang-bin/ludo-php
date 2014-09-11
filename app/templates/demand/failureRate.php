<?php
$gTitle = 'by Failure Rate';
include tpl('header');
?>
<form id="searchMffr" class="form-inline">
    Model:
    <select name="model[]" data-live-search="true" class="selectpicker" multiple>
        <option value="0">All Model</option>
        <?php foreach ($models as $model) {?>
            <option value="<?=$model?>"><?=$model?></option>
        <?php }?>
    </select>
    Parts Category:
    <select name="category[]" data-live-search="true" class="selectpicker" multiple>
        <option value="0">All Parts Category</option>
        <?php foreach ($categories as $category) {?>
            <option value="<?=$category['id']?>"><?=$category['partsGroupName']?></option>
        <?php }?>
    </select>
    <select name="country" data-live-search="true" class="selectpicker">
        <option value="0">All Country</option>
        <?php foreach ($countries as $country) {?>
            <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
    <a class="excel" href="<?=url('demand/failureRateReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="mffr"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#mffr").loading("<?=url('demand/failureRateTbl')?>");
    $("#mffr").on("click", "a.p", function(){
        $("#mffr").loading(this.href, $("#searchMffr").serializeArray());
        return false;
    });
    $("#searchMffr").submit(function(){
        $("#mffr").loading("<?=url('demand/failureRateTbl')?>", $("#searchMffr").serializeArray());
        return false;
    });

    $("#excel").click(function(){
        $.posting(this.href, $("#searchMffr").serializeArray(), function(result){
            ajaxHandler(result);
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>