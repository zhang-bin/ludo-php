<?php
$gTitle = 'Add NPI Plan';
include tpl('header');
Load::css('wizard');
?>
<ul class='nav nav-wizard' id="npi">
    <li class='active' tabindex="1" style="width:350px;"><a href='#global'>Step 1 - Global</a></li>
    <li tabindex="2" style="width:350px;"><a href='#model'>Step 2 - Model</a></li>
    <li tabindex="3" class="last" style="width:318px;"><a href='#pn' tabindex="3">Step 3 - PN</a></li>
</ul>
<div class="tab-content" style="overflow: visible;">
    <div class="tab-pane active" id="global">
        <form class="form form-horizontal" id="globalForm">
            <div class="control-group">
                <label class="control-label"><strong>NPI Code</strong></label>
                <div class="controls">
                    <input type="text" name="code" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="vendorId"><strong>Service Vendor</strong></label>
                <div class="controls">
                    <select name="vendorId" id="vendorId" class="selectpicker" data-live-search="true">
                        <option value="0"><?=LG_SELECT_CHOOSE?></option>
                        <?php foreach ($vendors as $vendor) {?>
                            <option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="productSeries"><strong>Product Series</strong></label>
                <div class="controls">
                    <select name="productSeries" id="productSeries" class="selectpicker" data-live-search="true">
                        <option value="0"><?=LG_SELECT_CHOOSE?></option>
                        <?php foreach ($productSeries as $series) {?>
                            <option value="<?=$series?>"><?=$series?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><strong>Planning Months</strong></label>
                <div class="controls">
                    <div class="input-append">
                        <input type="text" name="planningMonths" value="<?=$planningMonths?>" />
                        <span class="add-on">Month</span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="remark"><strong>Remark</strong></label>
                <div class="controls">
                    <textarea name="remark" id="remark" rows="4" class="span5"></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="model" >
    </div>
    <div class="tab-pane" id="pn" >
    </div>
</div>
<ul class="pager">
    <li class="previous disabled" id="previous"><a href="#">Previous</a></li>
    <li class="next" id="next"><a href="#">Next</a></li>
    <li class="next" id="finish" style="display: none;"><a href="#">Finish</a></li>
</ul>
<script type="text/javascript">
$(document).ready(function() {
    $("#vendorId").change(function(){
        $.getJSON("<?=url('api/getModelByVendor/')?>"+$(this).val(), {}, function(result) {
            $("#modelId").empty();
            $("#modelId").append('<option value="0"><?=LG_SELECT_CHOOSE?></option>')
            $.each(result, function(k, v){
                $("#modelId").append('<option value="'+v+'">'+v+'</option>');
            });
            $("#modelId").selectpicker("refresh");
            return;
       });
    });
    var $tabs = $('#npi li');
    $("#previous").click(function(){
        $tabs.filter('.active').prev('li').find('a').tab('show');
    });
    $("#next").click(function(){
        $tabs.filter('.active').next('li').find('a').tab('show');
    });

    var changeGlobal = changeModel = changePN = false;
    $("#npi li").on("show", function(){
        $("#previous").removeClass("disabled");
        $("#next").show();
        $("#finish").hide();
        var tabindex = parseInt($(this).attr("tabindex"));
        switch (tabindex) {
            case 1:
                $("#previous").addClass("disabled");
                break;
            case 2:
                if (changeGlobal) $("#model").loading("<?=url('npi/model')?>", $("#globalForm").serializeArray());
                break;
            case 3:
                $("#finish").show();
                $("#next").hide();
                if (changeModel) $("#pn").loading("<?=url('npi/pn')?>", $("#modelForm").serializeArray());
                break;
            default:
                break;
        }
    }).on("shown", function(){
        changeGlobal = changeModel = changePN = false;
    });

    $("#global").on("change", "input,textarea,select", function(){
        changeGlobal = true;
    });
    $("#model").on("click", "input", function(){
        changeModel = true;
    });
    $("#finish").click(function(){
        $.post("<?=url('npi/add')?>", $("#globalForm,#modelForm,#pnForm").serializeArray(), function(result){
            ajaxHandler(result);
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>