<form id="pnForm">
    <div class="accordion" id="accordion2">
        <?php if (!empty($pns)) {foreach ($pns as $model => $pn) {?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?=$model?>"><?=$model?></a>
            </div>
            <div id="collapse_<?=$model?>" class="accordion-body collapse in">
                <div class="accordion-inner">
                    <table class="table table-hovered">
                        <tr>
                            <th><input type="checkbox" class="checkAllPn" /></th>
                            <th>PN</th>
                            <th>Parts Category</th>
                            <th>Parts Name</th>
                        </tr>
                        <?php if (!empty($pn)) {foreach ($pn as $v) {?>
                            <tr>
                                <td><input type="checkbox" class="checkOnePn" name="pns[<?=$model?>][]" value="<?=$v['pn']?>" /></td>
                                <td><?=$v['pn']?></td>
                                <td><?=$v['partsGroup']?></td>
                                <td><?=$v['en']?></td>
                            </tr>
                        <?php }}?>
                    </table>
                </div>
            </div>
        </div>
        <?php }}?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $(".checkAllPn").click(function(){
            var checkOne = $(this).closest("table").find(".checkOnePn");
            if ($(this).prop("checked")) {
                checkOne.prop("checked", true);
            } else {
                checkOne.prop("checked", false);
            }
        });
    });
</script>