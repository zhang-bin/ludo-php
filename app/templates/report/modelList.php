<table class="table table-hover">
    <thead>
    <tr>
        <th>Country</th>
        <th>Model</th>
        <th>Parts Category</th>
        <th>Month</th>
        <th>Qty</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($list)) {foreach ($list as $v) {?>
        <tr>
            <td><?=$v['country']?></td>
            <td><?=$v['model']?></td>
            <td><?=$v['category']?></td>
            <td><?=$v['month']?></td>
            <td><?=$v['qty']?></td>
        </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>