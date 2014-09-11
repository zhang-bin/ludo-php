<style>
.bg-1{
	background-color:#D4DFED !important;
}
.bg-2{
	background-color:#183769 !important;
	color:#FFFFFF !important;
}
.bg-red{
	background-color:red !important;
}
.bg-green{
	background-color:green !important;
}
</style>
<table class="table table-hover">
	<thead>
		<tr>
			<th class="bg-1"><?=LG_KPI?></th>
			<th class="bg-1"><?=LG_KPI_TARGET?></th>
			<th class="bg-1"><?=LG_KPI_COUNTRY?></th>
			<th class="bg-1"><?=LG_KPI_YTM?></th>
			<?php foreach (Kpi::$month as $v) {?>
			<th class="bg-2"><?=$v?></th>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($kpi as $v) {?>
		<tr>
			<td><?=$v['kpi']?></td>
			<td><?=$v['targetText']?></td>
			<td><?=$vendor['countryShortName']?></td>
			<td><?=$v['ytm']?></td>
			<?php 
				$data = json_decode($v['data'], true);
				$class = json_decode($v['class'], true);
				for ($i = 1; $i <= 12; $i++) {
					echo '<td class="bg-'.$class[$i].'">'.$data[$i]['value'].'</td>';
				}
			?>
		</tr>
		<?php }?>
	</tbody>
</table>