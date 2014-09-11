<?php
$gTitle = 'Parts Cluster';
$gToolbox = '<a class="add" href="'.url('parts/clusterAdd').'">Add Parts Cluster</a>';
include tpl('header');
?>
<form id="form1" class="form-inline" action="<?=url('parts/cluster')?>">
    <select name="category" class="selectpicker" data-live-search="true">
        <option value="0">All Parts Category</option>
        <?php foreach ($categories as $category) {?>
        <option value="<?=$category['id']?>" <?=($_GET['category'] == $category['id']) ? 'selected' : ''?>><?=$category['partsGroupName']?></option>
        <?php }?>
    </select>
    <select name="type" class="selectpicker">
        <option value="0">All Cluster Type</option>
        <?php foreach (Parts::$clusterType as $typeId => $type) {?>
            <option value="<?=$typeId?>" <?=($_GET['type'] == $typeId) ? 'selected' : ''?>><?=$type?></option>
        <?php }?>
    </select>
    <input type="text" name="pn" value="<?=$_GET['pn']?>" placeholder="PN" />
    <input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
    <a class="excel" href="<?=url('parts/clusterReport')?>" id="excel" style="float:right;"></a>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Cluster Number</th>
        <th>Master PN</th>
        <?php for ($i = 1; $i <= 7; $i++) {?>
            <th><?='Slave PN'.$i?></th>
        <?php }?>
        <th>Cluster Type</th>
        <th><?=LG_OPERATION?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($clusters)) { foreach ($clusters as $v) {?>
        <tr>
            <td><?=$v['cluster']?></td>
            <td><?=$v['masterPn']?></td>
            <?php $slavePn = json_decode($v['slavePn'], true);for ($i = 0; $i < 7; $i++) {?>
                <td><?=$slavePn[$i]?></td>
            <?php }?>
            <td><?=Parts::$clusterType[$v['type']]?></td>
            <td>
                <a href="<?=url('parts/clusterChange/'.$v['id'])?>" class="btn btn-primary btn-small"><?=LG_BTN_EDIT?></a>
                <a name="del" title="Delete Parts Cluster" href="<?=url('parts/clusterDel/'.$v['id'])?>" class="btn btn-warning btn-small"><?=LG_BTN_DEL?></a>
            </td>
        </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>
<?php include tpl('footer');?>