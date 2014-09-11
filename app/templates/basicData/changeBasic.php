<?php 
$gTitle = LG_SETTING_CHANGE;
include tpl('header');
?>
<form class="form form-horizontal" method="post" action="<?=url('basicData/changeBasic')?>">
<div class="row">
	<div class="span3 sidebar">
		<ul class="nav nav-list bs-docs-sidenav">
			<li class=""><a href="#expireTime"><i class="icon-chevron-right"></i><?=LG_SETTING_EXPIRE_TIME_SETTING?></a></li>
			<li><a href="#weight"><i class="icon-chevron-right"></i><?=LG_SETTING_WEIGHT_SETTING?></a></li>
			<li><a href="#stock"><i class="icon-chevron-right"></i><?=LG_SETTING_STOCK_SETTING?></a></li>
			<li><a href="#abc"><i class="icon-chevron-right"></i><?=LG_SETTING_ABC_CLASSIFY?></a></li>
			<li><a href="#slowMoving"><i class="icon-chevron-right"></i><?=LG_SETTING_SLOW_MOVING?></a></li>
			<li><a href="#obsolete"><i class="icon-chevron-right"></i><?=LG_SETTING_OBSOLETE_PARAMETER?></a></li>
            <li><a href="#npi"><i class="icon-chevron-right"></i><?=LG_SETTING_NPI_PLANNING_MONTHS?></a></li>
		</ul>
	</div>
	<div class="span9">
		<section id="expireTime">
			<div class="page-header">
				<h2><?=LG_SETTING_EXPIRE_TIME_SETTING?></h2>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::EXPIRE_TIME?>"><strong><?=LG_SETTING_EXPIRE_TIME?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="<?=BasicData::EXPIRE_TIME?>" name="<?=BasicData::EXPIRE_TIME?>" value="<?=$data[BasicData::EXPIRE_TIME]?>" />
						<span class="add-on">Month</span>
					</div>
				</div>
			</div>
		</section>
		<section id="weight">
			<div class="page-header">
				<h2><?=LG_SETTING_WEIGHT_SETTING?></h2>
			</div>
				<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::PSI_WEIGHT1?>"><strong><?=LG_SETTING_WEIGHT1?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::PSI_WEIGHT1?>" name="<?=BasicData::PSI_WEIGHT1?>" value="<?=$data[BasicData::PSI_WEIGHT1]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::PSI_WEIGHT2?>"><strong><?=LG_SETTING_WEIGHT2?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::PSI_WEIGHT2?>" name="<?=BasicData::PSI_WEIGHT2?>" value="<?=$data[BasicData::PSI_WEIGHT2]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::PSI_WEIGHT3?>"><strong><?=LG_SETTING_WEIGHT3?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::PSI_WEIGHT3?>" name="<?=BasicData::PSI_WEIGHT3?>" value="<?=$data[BasicData::PSI_WEIGHT3]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
			<div class="control-group">
				<div class="controls weightTotal"><?=($data[BasicData::PSI_WEIGHT1] + $data[BasicData::PSI_WEIGHT2] + $data[BasicData::PSI_WEIGHT3])?>%</div>
			</div>
		</section>
		<section id="stock">
			<div class="page-header">
				<h2><?=LG_SETTING_STOCK_SETTING?></h2>
			</div>
            <?php
            $saftyStock = json_decode($data[BasicData::PSI_SAFTY_STOCK], true);
            foreach ($countries as $country) {
            ?>
			<div class="control-group">
	    		<label class="control-label" for="<?=$country['country']?>"><strong><?=$country['country']?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=$country['country']?>" name="<?=BasicData::PSI_SAFTY_STOCK.'['.$country['country'].']'?>" value="<?=$saftyStock[$country['country']]?>" />
						<span class="add-on">Month</span>
					</div>
				</div>
			</div>
            <?php }?>
		</section>
		<section id="abc">
			<div class="page-header">
				<h2><?=LG_SETTING_ABC_CLASSIFY?></h2>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::ABC_A_T?>"><strong><?=LG_SETTING_ABC_A?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::ABC_A_T?>" name="<?=BasicData::ABC_A_T?>" value="<?=$data[BasicData::ABC_A_T]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::ABC_B_T?>"><strong><?=LG_SETTING_ABC_B?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::ABC_B_T?>" name="<?=BasicData::ABC_B_T?>" value="<?=$data[BasicData::ABC_B_T]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::ABC_C_T?>"><strong><?=LG_SETTING_ABC_C?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" class="weight" id="<?=BasicData::ABC_C_T?>" name="<?=BasicData::ABC_C_T?>" value="<?=$data[BasicData::ABC_C_T]?>" />
						<span class="add-on">%</span>
					</div>
				</div>
			</div>
		</section>
		<section id="slowMoving">
			<div class="page-header">
				<h2><?=LG_SETTING_SLOW_MOVING?></h2>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::SLOW_TO_LIMIT?>"><strong><?=LG_SETTING_SLOW_MOVING_TO_LIMINT?></strong></label>
				<div class="controls">
					<input type="text" id="<?=BasicData::SLOW_TO_LIMIT?>" name="<?=BasicData::SLOW_TO_LIMIT?>" value="<?=$data[BasicData::SLOW_TO_LIMIT]?>" />
				</div>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::SLOW_MONTH_LIMIT?>"><strong><?=LG_SETTING_SLOW_MOVING_MONTH_LIMINT?></strong></label>
				<div class="controls">
					<div class="input-append">
						<input type="text" id="<?=BasicData::SLOW_MONTH_LIMIT?>" name="<?=BasicData::SLOW_MONTH_LIMIT?>" value="<?=$data[BasicData::SLOW_MONTH_LIMIT]?>" />
						<span class="add-on">Month</span>
					</div>
				</div>
			</div>
		</section>
        <section id="obsolete">
			<div class="page-header">
				<h2><?=LG_SETTING_OBSOLETE_PARAMETER?></h2>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::OBSOLETE?>"><strong><?=LG_SETTING_OBSOLETE?></strong></label>
				<div class="controls">
                    <div class="input-append">
                        <input type="text" id="<?=BasicData::OBSOLETE?>" name="<?=BasicData::OBSOLETE?>" value="<?=$data[BasicData::OBSOLETE]?>" />
                        <span class="add-on">Month</span>
                    </div>
				</div>
			</div>
		</section>
        <section id="npi">
			<div class="page-header">
				<h2><?=LG_SETTING_NPI_PLANNING_MONTHS?></h2>
			</div>
			<div class="control-group">
	    		<label class="control-label" for="<?=BasicData::NPI_PLANNING_MONTHS?>"><strong><?=LG_SETTING_NPI_PLANNING_MONTHS?></strong></label>
				<div class="controls">
                    <div class="input-append">
                        <input type="text" id="<?=BasicData::NPI_PLANNING_MONTHS?>" name="<?=BasicData::NPI_PLANNING_MONTHS?>" value="<?=$data[BasicData::NPI_PLANNING_MONTHS]?>" />
                        <span class="add-on">Month</span>
                    </div>
				</div>
			</div>
		</section>
		<hr>
		<div class="control-group">
			<div class="controls">
		 		<input type="submit" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" id="submitBtn"/>
		 		<a class="btn" href="<?=url('basicData')?>"><?=LG_BTN_CANCEL?></a>
			</div>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$(".crumbs").css("margin-bottom", "0px");
	$(".weight").change(function(){
		var total = 0;
		$(".weight").each(function(){
			var n = parseFloat($(this).val());
			if (isNaN(n)) return;
			total += n;
		});
		$(".weightTotal").text(total+"%");
	});
	setTimeout(function () {
		$('.bs-docs-sidenav').affix({
			offset: {
				top: function () { return $(window).width() <= 980 ? 155 : 85; }
	        	, bottom: 0
	        }
		})
	}, 100)
});
</script>
<?php include tpl('footer');?>