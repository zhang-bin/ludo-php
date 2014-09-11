<?php
$gTitle = LG_SETTING_NPI_MFFR;
$gToolbox = '
<a href="'.url('basicData/importNpiMffr').'" class="add">'.LG_FAILURE_RATE_NPI_IMPORT.'</a>
<a href="'.url('basicData/addNpiMffr').'" class="add">'.LG_FAILURE_RATE_NPI_ADD.'</a>
';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="searchNpiMffr" class="form-inline">
    <select name="productSeries" data-live-search="true" class="selectpicker">
        <option value="0">All Product Series</option>
        <?php foreach ($productSeries as $v) {?>
            <option value="<?=$v?>"><?=$v?></option>
        <?php }?>
    </select>
    <select name="vendor" data-live-search="true" class="selectpicker">
        <option value="0">All Service Vendor</option>
        <?php foreach ($vendors as $vendor) {if (empty($vendor['countryShortName'])) continue;?>
            <option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
        <?php }?>
    </select>
    <select name="category" data-live-search="true" class="selectpicker">
        <option value="0">All Parts Category</option>
        <?php foreach ($partsCategories as $partsCategory) {?>
            <option value="<?=$partsCategory['id']?>"><?=$partsCategory['partsGroupName']?></option>
        <?php }?>
    </select>
    <input type="text" name="month" id="month" value="" placeholder="<?=LG_FAILURE_RATE_MONTH?>" />
    <input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
    <a class="btn btn-success btn-small" href="<?=url('basicData/uploadNpiMffr')?>">Upload</a>
</form>
<div id="npiMffr"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#npiMffr").loading("<?=url('basicData/npiMffrTbl')?>");
    $("#npiMffr").on("click", "a.p", function(){
        $("#npiMffr").loading(this.href, $("#searchNpiMffr").serializeArray());
        return false;
    });
    $("#searchNpiMffr").submit(function(){
        $("#npiMffr").loading("<?=url('basicData/npiMffrTbl')?>", $("#searchNpiMffr").serializeArray());
        return false;
    });
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