<?php
$gTitle = LG_BY_MODEL;
$gToolbox = '<a href="'.url('report/modelChart').'"><i class="icon-bar-chart"></i> '.LG_USE_NUMBER_CHART.'(by model)</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
    <select name="country" data-live-search="true" class="selectpicker">
        <option value="0">All Country</option>
        <?php foreach ($countries as $country) {?>
            <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <select name="model" data-live-search="true" class="selectpicker">
        <option value="0">All Model</option>
        <?php foreach ($models as $model) {?>
            <option value="<?=$model?>"><?=$model?></option>
        <?php }?>
    </select>
    <select name="category" data-live-search="true" class="selectpicker">
        <option value="0">All Parts Category</option>
        <?php foreach ($categories as $category) {?>
            <option value="<?=$category['id']?>"><?=$category['partsGroupName']?></option>
        <?php }?>
    </select>
    <input type="text" name="month" id="month" placeholder="Month" />
    <input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
    <input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
</form>
<div id="useNumber"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#useNumber").loading("<?=url('report/modelList')?>");
    $("a.p").live("click", function(){
        $("#useNumber").loading(this.href, $("#form1").serializeArray());
        return false;
    });
    $("#form1").submit(function(){
        $("#useNumber").loading("<?=url('report/modelList')?>", $("#form1").serializeArray());
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
    $("#reset").click(function(){
        $.posting("<?=url('report/resetModel')?>", $("#form1").serializeArray(), function(result){
            if (result == '1') {
                $.alertSuccess("Reset Model Monthly Usage Success!");
                $("#useNumber").loading("<?=url('report/modelList')?>", $("#form1").serializeArray());
            } else {
                $.alertError("Reset Model Monthly Usage Failed!");
            }
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>