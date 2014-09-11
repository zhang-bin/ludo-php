<table class="table table-hover">
    <thead>
    <tr>
        <th>Country</th>
        <th>Model Name</th>
        <th>Model PN</th>
        <th>Number</th>
        <th>Sales Time</th>
        <th>Batch</th>
        <th><?=LG_OPERATION?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <?php if (!empty($warranties)) { foreach ($warranties as $warranty) {?>
    <tr>
        <td><?=$warranty['country']?></td>
        <td><?=$warranty['modelName']?></td>
        <td><?=$warranty['pn']?></td>
        <td><?=$warranty['number']?></td>
        <td><?=$warranty['salesTime']?></td>
        <td><?=$warranty['batch']?></td>
        <td><a name="del" title="<?=LG_FORECAST_MODEL_WARRANTY_DELETED?>" href="<?=url('product/delForecastWarranty/'.$warranty['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a></td>
    </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>