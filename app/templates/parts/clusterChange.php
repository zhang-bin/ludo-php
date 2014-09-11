<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? 'Change Parts Cluster' : 'Add Parts Cluster';
include tpl('header');
?>
<form method="post" class="form-horizontal form" action="<?=url($change ? 'parts/clusterChange/'.$_GET['id'] : 'parts/clusterAdd')?>">
    <fieldset>
        <div class="control-group">
            <label class="control-label" for="type">
                <strong>Cluster Type</strong>
            </label>
            <div class="controls">
                <?php if ($change) {?>
                    <p class="form-control-static"><?=Parts::$clusterType[$cluster['type']]?></p>
                <?php } else {?>
                <select name="type" class="selectpicker">
                    <option value="0"><?=LG_SELECT_CHOOSE?></option>
                    <?php foreach (Parts::$clusterType as $typeId => $type) {?>
                    <option value="<?=$typeId?>" <?=($cluster['type'] == $typeId) ? 'selected' : ''?>><?=$type?></option>
                    <?php }?>
                </select>
                <?php }?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="partsGroupName">
                <strong>Cluster Category</strong>
            </label>
            <div class="controls">
                <?php if ($change) {?>
                    <p class="form-control-static"><?=$cluster['partsGroupName']?></p>
                <?php } else {?>
                <select name="partsCategory" id="partsCategory" class="selectpicker" data-live-search="true">
                    <option value="0"><?=LG_SELECT_CHOOSE?></option>
                    <?php foreach ($partsCategories as $partsCategory) {?>
                    <option value="<?=$partsCategory['id']?>" <?=($cluster['partsCategoryId'] == $partsCategory['id']) ? 'selected' : ''?>><?=$partsCategory['partsGroupName']?></option>
                    <?php }?>
                </select>
                <?php }?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="masterPn">
                <strong>Master PN</strong>
            </label>
            <div class="controls">
                <select name="masterPn" id="masterPn" class="selectpicker" data-live-search="true" data-size="10">
                    <option value="0"><?=LG_SELECT_CHOOSE?></option>
                    <?php if (!empty($pns)) {foreach ($pns as $pn){?>
                    <option value="<?=$pn?>" <?=($pn == $cluster['masterPn']) ? 'selected' : ''?>><?=$pn?></option>
                    <?php }}?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="pn">
                <strong>Select PN</strong>

            </label>
            <div class="controls">
                <select name="pn[]" id="pn" class="selectpicker" data-live-search="true" multiple="multiple" data-selected-text-format="count" data-size="8">
                    <?php if (!empty($pns)) {$slavePn = json_decode($cluster['slavePn'], true);foreach ($pns as $pn){?>
                        <option value="<?=$pn?>" <?=(in_array($pn, $slavePn)) ? 'selected' : ''?>><?=$pn?></option>
                    <?php }}?>
                </select>
                <span id="pnChosed">
                    <?php foreach ($slavePn as $pn) {?>
                        <span class="label"><?=$pn?></span>
                    <?php }?>
                </span>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" id="id" name="id" value="<?=$cluster['id']?>" />
                <input type="submit" id="submitBtn" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" />
                <a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
            </div>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#partsCategory").change(function(){
        $.getJSON("<?=url('api/getPnByCategory/')?>"+$(this).val(), {}, function(result) {
            $("#pn,#masterPn").empty();
            $("#pn,#masterPn").append('<option value="0"><?=LG_SELECT_CHOOSE?></option>')
            $.each(result, function(k, v){
                $("#pn,#masterPn").append('<option value="'+v+'">'+v+'</option>');
            });
            $("#pn,#masterPn").selectpicker("refresh");
            return;
        });
    });
    $("#pn").change(function(){
        var pns = $(this).val();
        if (pns.length > 0) {
            var pnChosed = $("#pnChosed");
            pnChosed.empty();
            $.each(pns, function(k, v){
                pnChosed.append("<span class='label'>"+v+"</span>\n");
            });
        }
    });
});
</script>
<?php include tpl('footer');?>