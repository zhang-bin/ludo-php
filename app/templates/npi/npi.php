<?php
$gTitle = 'NPI Plan';
$gToolbox = '<a href="'.url('npi/add').'" class="add">Add NPI Plan</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
    Product Series:
    <select name="productSeries" data-live-search="true" class="selectpicker">
        <option value="0">All Product Series</option>
        <?php foreach ($productSeries as $v) {?>
            <option value="<?=$v?>"><?=$v?></option>
        <?php }?>
    </select>
    Service Vendor:
    <select name="vendor[]" class="selectpicker" data-live-search="true" multiple="multiple">
        <option value="0">All Service Vendor</option>
        <?php foreach ($vendors as $vendor) {if (empty($vendor['countryShortName'])) continue;?>
            <option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
        <?php }?>
    </select>
    Create Date:
    <input type="text" name="from" id="from" placeholder="from" />
    To:
    <input type="text" name="to" id="to" placeholder="to" />
    <input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-small btn-primary" />
    <a class="excel" href="<?=url('npi/report')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="npi"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#from").datetimepicker({
        startView: 2,
        minView: 2,
        maxView: 2,
        format: 'yyyy-mm-dd',
        autoclose: 1,
        endDate: "<?=date(DATE_FORMAT)?>"
    }).on("changeDate", function(ev){
        $("#to").datetimepicker("setStartDate", ev.date);
    });

    $("#to").datetimepicker({
        startView: 2,
        minView: 2,
        maxView: 2,
        format: 'yyyy-mm-dd',
        autoclose: 1,
        endDate: "<?=date(DATE_FORMAT)?>"
    }).on("changeDate", function(ev){
        $("#from").datetimepicker("setEndDate", ev.date);
    });
    $("#npi").loading("<?=url('npi/tbl')?>", $("#form1").serializeArray());

    $("#form1").submit(function(){
        $("#npi").loading("<?=url('npi/tbl')?>", $("#form1").serializeArray());
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