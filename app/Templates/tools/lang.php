<?php
$gTitle = '语言包';
include tpl('header');
$lang = Load::conf('Lang');
Load::js('bootstrap-fileinput');
?>
<form id="langDownload" action="<?=url('tools/langDownload')?>" method="post">
    <select name="type" class="selectpicker">
        <option value="1">后台</option>
        <option value="2">接口</option>
        <option value="3">长连接</option>
    </select>
    <select class="selectpicker" name="langDownload">
        <?php foreach ($lang as $k => $v) {?>
            <option value="<?=$v?>"><?=$k?></option>
        <?php }?>
    </select>
    <input type="submit" class="btn btn-primary" value="<?=DOWNLOAD?>" id="download" />
</form>
<hr />
<form id="langUpload" action="<?=url('tools/langUpload')?>" method="post" enctype="multipart/form-data" >
    <select name="type" class="selectpicker">
        <option value="1">后台</option>
        <option value="2">接口</option>
        <option value="3">长连接</option>
    </select>
    <select class="selectpicker" name="langUpload">
        <?php foreach ($lang as $k => $v) {?>
            <option value="<?=$v?>"><?=$k?></option>
        <?php }?>
    </select>
    <input id="file" name="file" type="file" />
    <input type="submit" class="btn btn-primary" value="<?=UPLOAD?>" id="upload" />
</form>

<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){
    var $file = $("#file");
    $file.fileinput({
        showPreview: false,
        language: 'zh',
        uploadUrl: '<?=url('tools/langUpload')?>',
        allowedFileExtensions : ['xls', 'xlsx']
    });
    $file.on("fileuploaded", function(event, data, previewId, index){
        ajaxHandler(data.response);
    });
});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>
