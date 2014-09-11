<table class="table table-hover" width="100%">
    <thead>
    <tr>
        <th><?=LG_FAILURE_RATE_PRODUCT_SERIES?></th>
        <th><?=LG_FAILURE_RATE_CATEGORY?></th>
        <th><?=LG_FAILURE_RATE_VENDOR?></th>
        <th><?=LG_FAILURE_RATE_MONTH?></th>
        <th><?=LG_FAILURE_RATE_RATE?></th>
        <th><?=LG_OPERATION?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($mffrs)) { foreach ($mffrs as $mffr) {?>
        <tr>
            <td><?=$mffr['productSeries']?></td>
            <td><?=$mffr['category']?></td>
            <td><?=$mffr['countryShortName']?></td>
            <td><?=$mffr['month']?></td>
            <td><?=$mffr['rate']?></td>
            <td>
                <a href="<?=url('basicData/changeNpiMffr/'.$mffr['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
                <a name="del" title="<?=LG_FAILURE_RATE_NPI_DELETE?>" href="<?=url('basicData/delNpiMffr/'.$mffr['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
            </td>
        </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>