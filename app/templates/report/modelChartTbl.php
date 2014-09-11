<table class="table table-hover">
    <thead>
    <tr>
        <th>Category</th>
        <th>Qty</th>
        <th>Percent(%)</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($models)) {foreach ($models as $model) {?>
        <tr data-toggle="collapse">
            <td><?=$model['category']?></td>
            <td><?=$model['qty']?></td>
            <td><?=$model['qty'] == '0' ? '' : round($model['qty'] / $totalQty * 100, 2)?></td>
        </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
    $("a.p").click(function(){
        $("#chartTbl").loading(this.href, <?=$params?>);
        return false;
    });
});
</script>