<?php
$gTitle = 'PSI Plan';
include tpl('header');
?>
<form id="form1">
    <input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-warning" id="reset" />
    <a href="<?=url('psi/report')?>" class="btn btn-primary" id="download"><?=LG_BTN_DOWNLOAD?></a>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#reset").click(function(){
        $.posting("<?=url('psi/reset')?>", {}, function(result){
            if (result == '1') {
                $.alertSuccess("Reset PSI Plan Success!");
            } else {
                $.alertError("Reset PSI Plan Failed!");
            }
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>