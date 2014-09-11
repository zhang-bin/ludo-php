<table class="table table-hover">
    <thead>
    <tr>
        <th>Country</th>
        <th>Model Name</th>
        <th>Model PN</th>
        <th>Number</th>
        <th>Sales Time</th>
        <th>Expire Time</th>
        <th>Remaining warranty months</th>
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
        <td><?=$warranty['expireTime']?></td>
        <td><?=ceil((strtotime($warranty['expireTime']) - time()) / 86400 / 30)?></td>
    </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>