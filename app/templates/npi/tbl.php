<table class="table table-hover">
    <thead>
        <tr>
            <th>NPI Code</th>
            <th>Service Vendor</th>
            <th>Product Series</th>
            <th>Planning Months</th>
            <th>FG Sales Volume</th>
            <th>Create Time</th>
            <th>Remark</th>
            <th><?=LG_OPERATION?></th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($list)) { foreach ($list as $v) {?>
        <tr>
            <td><a href="<?=url('npi/view/'.$v['id'])?>"><?=$v['code']?></a></td>
            <td><?=$v['countryShortName']?></td>
            <td><?=$v['productSeries']?></td>
            <td><?=$v['planningMonths']?></td>
            <td><?=$v['forecastSalesNumber']?></td>
            <td><?=$v['createTime']?></td>
            <td><?=$v['remark']?></td>
            <td>
                <a name="del" title="Delete NPI Plan" href="<?=url('npi/del/'.$v['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
            </td>
        </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>