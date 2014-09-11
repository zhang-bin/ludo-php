<?php
$gTitle = 'Weekly Replenish Plan';
include tpl('header');
?>
<form id="form1">
    <input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-warning" id="reset" />
    <a href="<?=url('replenish/report')?>" class="btn btn-primary" id="download"><?=LG_BTN_DOWNLOAD?></a>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#reset").click(function(){
        $.posting("<?=url('replenish/reset')?>", {}, function(result){
            if (result == '1') {
                $.alertSuccess("Reset Weekly Replenish Plan Success!");
            } else {
                $.alertError("Reset Weekly Replenish Plan Failed!");
            }
            return false;
        });
        return false;
    });
});
</script>
<?php include tpl('footer');?>