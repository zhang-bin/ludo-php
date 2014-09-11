<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_FAILURE_RATE_NPI_CHANGE : LG_FAILURE_RATE_NPI_ADD;
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form method="post" class="form form-horizontal" action="<?=url($change ? 'basicData/changeNpiMffr/'.$lt['id'] : 'basicData/addNpiMffr')?>">
    <div class="control-group">
        <label class="control-label" for="productSeries"><strong><?=LG_FAILURE_RATE_PRODUCT_SERIES?></strong></label>
        <div class="controls">
            <select name="productSeries" id="productSeries" class="selectpicker">
                <option value="0"><?=LG_SELECT_CHOOSE?></option>
                <?php foreach ($productSeries as $v) {?>
                <option value="<?=$v?>" <?=($v == $mffr['productSeries']) ? 'selected' : ''?>><?=$v?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="vendor"><strong><?=LG_FAILURE_RATE_VENDOR?></strong></label>
        <div class="controls">
            <select name="vendor" id="vendor" data-live-search="true" class="selectpicker">
                <option value="0">All Service Vendor</option>
                <?php foreach ($vendors as $vendor) {?>
                    <option value="<?=$vendor['id']?>" <?=($vendor['id'] == $mffr['vendorId']) ? 'selected' : ''?>><?=$vendor['countryShortName']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="partsCategory"><strong><?=LG_FAILURE_RATE_CATEGORY?></strong></label>
        <div class="controls">
            <select name="partsCategory" id="partsCategory" data-live-search="true" class="selectpicker">
                <option value="0">All Parts Category</option>
                <?php foreach ($partsCategories as $partsCategory) {?>
                    <option value="<?=$partsCategory['id']?>" <?=($partsCategory['id'] == $mffr['categoryId']) ? 'selected' : ''?>><?=$partsCategory['partsGroupName']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="month"><strong><?=LG_FAILURE_RATE_MONTH?></strong></label>
        <div class="controls">
            <input type="text" name="month" id="month" value="<?=$change ? $mffr['month'] : date('Y-m')?>" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="rate"><strong><?=LG_FAILURE_RATE_RATE?></strong></label>
        <div class="controls">
            <div class="input-append">
                <input type="text" name="rate" id="rate" value="<?=$mffr['rate']?>" />
                <span class="add-on">%</span>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="hidden" id="id" name="id" value="<?=$mffr['id']?>"/>
            <input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
            <a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#month").datetimepicker({
        startView: 3,
        minView: 3,
        maxView: 3,
        format: 'yyyy-mm',
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1
    });
});
</script>
<?php include tpl('footer');?>