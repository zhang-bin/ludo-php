<table class="table table-hover">
    <thead>
        <tr>
            <th>Warehouse Name</th>
            <th>New PN</th>
            <th>Order PN</th>
            <th>Old PN</th>
            <th>Part Description</th>
            <th>Quantity</th>
            <th>In Transit</th>
            <th>Freeze</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $k=>$v) {?>
        <tr>
            <td><?=$v['name']?></td>
            <td><?=$v['pn'] ?></td>
            <td><?=$v['pn2'] ?></td>
            <td><?=$v['pn3'] ?></td>
            <td><?=$v['en'] ?></td>
            <td><?=$v['qty'] ?></td>
            <td><?=$v['inTransit']?></td>
            <td><?=$v['freeze']?></td>
        </tr>
        <?php }?>
        <?php if(!empty($pager)){?>
        <tr>
            <td style="text-align: right;" colspan="10"><?=$pager?></td>
        </tr>
        <?php }?>
    </tbody>
</table>