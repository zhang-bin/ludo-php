<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? 'Change Percentage of Country Sales Volume' : 'Add Percentage of Country Sales Volume';
include tpl('header');
?>
<form method="post" class="form-horizontal form" action="<?=url($change ? 'product/changeWarrantySetting/'.$setting['id'] : 'product/addWarrantySetting')?>">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="partsGroupName">
                <strong>Country</strong>
            </label>
            <div class="controls" style="padding-left: 120px;">
                <?php if ($change) {?>
                    <p class="form-control-static"><?=$setting['country']?></p>
                    <input type="hidden" name="country" value="<?=$setting['country']?>" />
                <?php } else {?>
                <select name="country" id="country" class="selectpicker" data-live-search="true">
                    <option value="0"><?=LG_SELECT_CHOOSE?></option>
                    <?php foreach ($countries as $country) {?>
                    <option value="<?=$country['country']?>"><?=$country['country']?></option>
                    <?php }?>
                </select>
                <?php }?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="partsGroupName">
                <strong>Field Service</strong>
            </label>
            <div class="controls" id="fsPane">
                <?php if ($change) {foreach ($setting['vendors'] as $vendor) {?>
                    <div class="control-group">
                        <label class="control-label"><?=$vendor['name']?>:&nbsp;</label>
                        <div class="input-append">
                            <input class="span2 percentage" name="percentage[<?=$vendor['vendorId']?>]" type="text" value="<?=$vendor['percentage']?>" />
                            <span class="add-on">%</span>
                        </div>
                    </div>
                <?php }}?>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" id="submitBtn" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" />
                <a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
            </div>
        </div>
    </fieldset>
</form>
<div class="control-group hide" id="fsTpl">
    <label class="control-label"></label>
    <div class="input-append">
        <input class="span2 percentage" name="percentage[]" type="text" />
        <span class="add-on">%</span>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#country").change(function(){
        $.getJSON("<?=url('product/getVendorsByCountry')?>", {"country": $(this).val()}, function(result){
            var tpl = $("#fsTpl");
            $("#fsPane").empty();
            if (result) {
                $(result).each(function(){
                    var clone = tpl.clone();
                    clone.attr("id", "");
                    clone.removeClass("hide");
                    clone.children(".control-label").html(this.name+":&nbsp;");
                    clone.find(".percentage").attr("name", "percentage["+this.id+"]");
                    if (typeof this.percentage != "undefined") {
                        clone.find(".percentage").val(this.percentage);
                    }
                    $("#fsPane").append(clone);
                });
            }
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>