<?php 
$gTitle = LG_SETTING;
include tpl('header');
?>
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
			<table class="table table-bordered table-hover">
				<tr>
					<td class="span3"><strong><?=LG_SETTING_EXPIRE_TIME?></strong></td>
					<td><?=$data[BasicData::EXPIRE_TIME]?>Month</td>
				</tr>
			</table>
		</section>
		<section id="weight">
			<div class="page-header">
				<h2><?=LG_SETTING_WEIGHT_SETTING?></h2>
			</div>
			<div>
			<table class="table table-bordered table-hover">
				<tr>
					<td class="span3"><strong><?=LG_SETTING_WEIGHT1?></strong></td>
					<td><?=$data[BasicData::PSI_WEIGHT1]?>%</td>
				</tr>
				<tr>
					<td><strong><?=LG_SETTING_WEIGHT2?></strong></td>
					<td><?=$data[BasicData::PSI_WEIGHT2]?>%</td>
				</tr>
				<tr>
					<td><strong><?=LG_SETTING_WEIGHT3?></strong></td>
					<td><?=$data[BasicData::PSI_WEIGHT3]?>%</td>
				</tr>
				<tr>
					<td></td>
					<td><?=($data[BasicData::PSI_WEIGHT1] + $data[BasicData::PSI_WEIGHT2] + $data[BasicData::PSI_WEIGHT3])?>%</td>
				</tr>
			</table>
			</div>
		</section>
		<section id="stock">
			<div class="page-header">
				<h2><?=LG_SETTING_STOCK_SETTING?></h2>
			</div>
			<table class="table table-bordered table-hover">
                <?php
                    $saftyStock = json_decode($data[BasicData::PSI_SAFTY_STOCK], true);
                    foreach ($countries as $country) {
                ?>
                    <tr>
                        <td class="span3"><strong><?=$country['country']?></strong></td>
                        <td><?=$saftyStock[$country['country']]?>Month</td>
                    </tr>
                <?php }?>
			</table>
		</section>
		<section id="abc">
			<div class="page-header">
				<h2><?=LG_SETTING_ABC_CLASSIFY?></h2>
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<td class="span3"><strong><?=LG_SETTING_ABC_A?></strong></td>
					<td><?=$data[BasicData::ABC_A_T]?>%</td>
				</tr>
				<tr>
					<td><strong><?=LG_SETTING_ABC_B?></strong></td>
					<td><?=$data[BasicData::ABC_B_T]?>%</td>
				</tr>
				<tr>
					<td><strong><?=LG_SETTING_ABC_C?></strong></td>
					<td><?=$data[BasicData::ABC_C_T]?>%</td>
				</tr>
			</table>
		</section>
		<section id="slowMoving">
			<div class="page-header">
				<h2><?=LG_SETTING_SLOW_MOVING?></h2>
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<td class="span3"><strong><?=LG_SETTING_SLOW_MOVING_TO_LIMINT?></strong></td>
					<td><?=$data[BasicData::SLOW_TO_LIMIT]?></td>
				</tr>
				<tr>
					<td><strong><?=LG_SETTING_SLOW_MOVING_MONTH_LIMINT?></strong></td>
					<td><?=$data[BasicData::SLOW_MONTH_LIMIT]?>Month</td>
				</tr>
			</table>
		</section>
        <section id="obsolete">
			<div class="page-header">
				<h2><?=LG_SETTING_OBSOLETE_PARAMETER?></h2>
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<td class="span3"><strong><?=LG_SETTING_OBSOLETE?></strong></td>
					<td><?=$data[BasicData::OBSOLETE]?>Month</td>
				</tr>
			</table>
		</section>
        <section id="npi">
            <div class="page-header">
                <h2><?=LG_SETTING_NPI_PLANNING_MONTHS?></h2>
            </div>
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="span3"><strong><?=LG_SETTING_NPI_PLANNING_MONTHS?></strong></td>
                    <td><?=$data[BasicData::NPI_PLANNING_MONTHS]?>Month</td>
                </tr>
            </table>
        </section>
		<hr>
		<div><a href="<?=url('basicData/changeBasic')?>" class="btn btn-primary"><?=LG_BTN_EDIT?></a></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".crumbs").css("margin-bottom", "0px");
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