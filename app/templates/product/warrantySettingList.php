<table class="table table-hover">
    <thead>
    <tr>
        <th>Country</th>
        <th>Percentage</th>
        <th><?=LG_OPERATION?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($settings)) { foreach ($settings as $setting) {?>
    <tr>
        <td><?=$setting['country']?></td>
        <td>
        <?php if (!empty($setting['vendors'])) {foreach ($setting['vendors'] as $vendor) {?>
            <span><?=$vendor['name']?>: </span>
            <span class="label <?=$setting['warning']?>"><?=$vendor['percentage']?>%</span>
            <br />
        <?php }}?>
        </td>
        <td>
            <a href="<?=url('product/changeWarrantySetting/'.$setting['country'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
            <a name="del" title="Delete Percentage of Country Sales Volume" href="<?=url('product/delWarrantySetting/'.$setting['country'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
        </td>
    </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>