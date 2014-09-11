<?php
$gTitle = LG_FAILURE_RATE_NPI_IMPORT;
include tpl('header');
Load::js('uploadify');
?>
<form method="post" class="form-horizontal" id="importNpiMffr" action="<?=url('basicData/importNpiMffr')?>">
    <div class="control-group">
        <label class="control-label" for="file"><strong><?=LG_FILE?></strong></label>
        <div class="controls">
            <input id="file" name="file" type="file" />
            <a href="<?=rurl('static/npi-mffr.xlsx')?>" target="_blank">Sample File</a>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
            <a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $('#file').uploadify({
        'width': '80',
        'swf'      : '<?=rurl('img/uploadify/uploadify.swf')?>',
        'uploader' : '<?=url('basicData/importNpiMffr')?>',
        'formData': {"id": "<?=$lt['id']?>"},
        'auto' : false,
        'buttonText': 'Browse',
        'onUploadSuccess': function(file, data, response){
            ajaxHandler(data);
        }
    });

    $("#importNpiMffr").submit(function(){
        $('#file').uploadify("upload", '*');
        return false;
    });
});
</script>