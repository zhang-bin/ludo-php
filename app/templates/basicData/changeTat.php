<?php
$conf = Load::conf('setting');
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_TAT_CHANGE : LG_TAT_ADD;
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=url($change ? 'basicData/changeTat/'.$tat['id'] : 'basicData/addTat')?>">
	<div class="control-group">
		<label class="control-label" for="from"><strong><?=LG_TAT_FROM?></strong></label>
		<div class="controls">
			<select name="from" id="from" class="selectpicker" data-live-search="true">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($points as $point) {?>
		  		<option value="<?=$point['id']?>" <?=($point['id'] == $tat['from']) ? 'selected' : ''?>><?=$point['point']?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="to"><strong><?=LG_TAT_TO?></strong></label>
		<div class="controls">
			<select name="to" id="to" class="selectpicker" data-live-search="true">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($points as $point) {?>
		  		<option value="<?=$point['id']?>" <?=($point['id'] == $tat['to']) ? 'selected' : ''?>><?=$point['point']?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="transportWay"><strong><?=LG_TAT_TRANS_WAY?></strong></label>
		<div class="controls">
			<select name="transportWay" id="transportWay" class="selectpicker">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($conf['transport'] as $k => $v) {?>
		  		<option value="<?=$k?>" <?=($k == $tat['transportWay']) ? 'selected' : ''?>><?=$v?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="consumeDays"><strong><?=LG_TAT_DAY?></strong></label>
		<div class="controls">
			<div class="input-append">
				<input type="text" name="consumeDays" id="consumeDays" value="<?=$tat['consumeDays']?>" />
				<span class="add-on">Day</span>
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="fee"><strong><?=LG_TAT_FEE?></strong></label>
		<div class="controls">
			<div class="input-append input-prepend">
				<span class="add-on">$</span>
				<input type="text" name="fee" id="fee" value="<?=$tat['fee']?>" style="width:120px;" />
				<span class="add-on">USD</span>
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="type"><strong><?=LG_TAT_TYPE?></strong></label>
		<div class="controls">
			<select name="type" id="type" class="selectpicker">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($conf['tatType'] as $k => $v) {?>
		  		<option value="<?=$k?>" <?=($k == $tat['type']) ? 'selected' : ''?>><?=$v?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="remark"><strong><?=LG_TAT_REMARK?></strong></label>
		<div class="controls">
			<textarea name="remark" id="remark" rows="4" class="span4"><?=$tat['remark']?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$tat['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer');?>