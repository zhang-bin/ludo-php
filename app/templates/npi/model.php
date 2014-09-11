<form id="modelForm">
    <table class="table table-hovered">
        <tr>
            <th><input type="checkbox" class="checkAllModel" /></th>
            <th>Model</th>
            <th>Model PN</th>
            <th>FG Sales Volumn</th>
        </tr>
        <?php if (!empty($models)) {foreach ($models as $model) {?>
        <tr>
            <td><input type="checkbox" class="checkOneModel" name="models[]" value="<?=$model['name']?>" /></td>
            <td><?=$model['name']?></td>
            <td><?=$model['pn']?></td>
            <td><?=$model['number']?></td>
        </tr>
        <?php }}?>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $(".checkAllModel").click(function(){
        if ($(this).prop("checked")) {
            $(".checkOneModel").prop("checked", true);
        } else {
            $(".checkOneModel").prop("checked", false);
        }
    });
});
</script>