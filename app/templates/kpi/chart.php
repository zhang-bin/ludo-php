<?php
$gTitle = LG_KPI_CHART;
$gToolbox = '<a href="'.url('kpi').'"><i class="icon-table"></i> '.LG_KPI.'</a>';
include tpl('header');
$thisYear = date('Y');
Load::js('amcharts');
?>
<form id="form1" class="form-inline">
	<select name="vendorId" id="vendorId" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['name']?></option>
		<?php }?>
	</select>
	<select name="year" id="year" class="selectpicker">
		<option value="0">Please Choose Year</option>
		<?php for ($i = 2012; $i <= $thisYear; $i++) {?>
		<option value="<?=$i?>"><?=$i?></option>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").submit(function(){
		if ($("#vendorId").val() == "0") {
			alert("<?=LG_KPI_VENDOR_EMPTY?>");
			return false;
		}
		if ($("#year").val() == "0") {
			alert("<?=LG_KPI_YEAR_EMPTY?>");
			return false;
		}
			
		$.getJSON("<?=url('kpi/chart')?>", $(this).serializeArray(), function(result){
			// SERIAL CHART
            chart = new AmCharts.AmSerialChart();
            chart.pathToImages = "<?=SITE_URL.'/img/amcharts/images/'?>";
            chart.dataProvider = result;
            chart.categoryField = "month";
            chart.dataDateFormat = "YYYY-MM";
            chart.startDuration = 1;
            chart.startEffect = 'easeOutSine';

            // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
            // chart.addListener("dataUpdated", zoomChart);

            // AXES
            // // category
            var categoryAxis = chart.categoryAxis;
            categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
            categoryAxis.minPeriod = "MM"; // our data is daily, so we set minPeriod to DD
          	categoryAxis.minorGridEnabled = true;
            categoryAxis.axisColor = "#DADADA";
            

            // first value axis (on the left)
            var valueAxis = new AmCharts.ValueAxis();
            valueAxis.axisColor = "#FF6600";
            valueAxis.axisThickness = 2;
            valueAxis.gridAlpha = 0;  
            valueAxis.unit = '%';
            chart.addValueAxis(valueAxis);

            // GRAPHS
            // first graph

            var tpls = <?=json_encode(array(Kpi::KPI_R_TAT, Kpi::KPI_FTF, Kpi::KPI_RR, Kpi::KPI_PPI, Kpi::KPI_PAL))?>;
            $.each(tpls, function(key, value){
	            var graph = new AmCharts.AmGraph();
	            graph.type = "smoothedLine";
	            graph.valueAxis = valueAxis; // we have to indicate which value axis should be used
	            graph.title = value;
	            graph.valueField = value;
	            graph.bullet = "round";
	            graph.hideBulletsCount = 30;
	            graph.bulletBorderThickness = 1;
	            graph.balloonText = "[[value]]%";
	            graph.legendValueText = "[[value]]%";
	            chart.addGraph(graph);
            });
            
            // CURSOR
            var chartCursor = new AmCharts.ChartCursor();
            chartCursor.cursorAlpha = 0.1;
            chartCursor.fullWidth = true;
            chartCursor.categoryBalloonDateFormat = "MMM";
            chart.addChartCursor(chartCursor);      

// SCROLLBAR
            var chartScrollbar = new AmCharts.ChartScrollbar();
            chart.addChartScrollbar(chartScrollbar);
            // LEGEND
            var legend = new AmCharts.AmLegend();
            legend.marginLeft = 110;
            legend.useGraphSettings = true;
            chart.addLegend(legend);

            // WRITE
            chart.write("chartdiv");
		});
		return false;
	});
});
</script>
<div id="chartdiv" style="width: 100%; height: 400px;"></div>
<?php include tpl('footer');?>