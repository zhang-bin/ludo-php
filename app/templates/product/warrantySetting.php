<?php
$gTitle = 'Percentage of Country Sales Volume Setting';
$gToolbox = '
<a href="'.url('product/addWarrantySetting').'" class="add">Add Percentage of Country Sales Volume</a>';
include tpl('header');
?>
<form id="form1" class="form-inline">
    <select name="country" id="country" class="selectpicker" data-live-search="true">
        <option value="0"><?=LG_SELECT_CHOOSE?></option>
        <?php foreach ($countries as $country) {?>
        <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small"/>
</form>
<div id="warrantyList" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#warrantyList").loading("<?=url('product/warrantySettingList')?>");
    $("a.p").live('click', function(){
        $("#warrantyList").loading(this.href, $("#form1").serializeArray());
        return false;
    });

    $("#form1").submit(function(){
        $("#warrantyList").loading("<?=url('product/warrantySettingList')?>", $("#form1").serializeArray());
        return false;
    });
});
</script>
<?php include tpl('footer');?>