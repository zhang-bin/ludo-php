<?php
$gTitle = LG_MODEL_WARRANTY_CHART;
$gToolbox = '<a href="'.url('product/warranty').'"><i class="icon-table"></i> '.LG_MODEL_WARRANTY_LIST.'</a>';
include tpl('header');
$thisYear = date('Y');
Load::js('amcharts');
?>
<form id="form1" class="form-inline">
    <select name="country" id="country" class="selectpicker" data-live-search="true">
        <option value="0">All Country</option>
        <?php foreach ($countries as $country) {?>
            <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <select name="model" id="model" class="selectpicker" data-live-search="true">
        <option value="0">All Model</option>
        <?php foreach ($models as $model) {?>
            <option value="<?=$model?>"><?=$model?></option>
        <?php }?>
    </select>
    <input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#form1").submit(function(){
        $.getJSON("<?=url('product/warrantyChartData')?>", $(this).serializeArray(), function(result){
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
            chart.addValueAxis(valueAxis);

            // GRAPHS
            // first graph

            var graph = new AmCharts.AmGraph();
            graph.type = "smoothedLine";
            graph.valueAxis = valueAxis; // we have to indicate which value axis should be used
            graph.valueField = 'qty';
            graph.bullet = "round";
            graph.bulletBorderThickness = 1;
            graph.balloonText = "[[value]]";
            graph.legendValueText = "[[value]]";
            chart.addGraph(graph);

            // CURSOR
            var chartCursor = new AmCharts.ChartCursor();
            chartCursor.cursorAlpha = 0.1;
            chartCursor.fullWidth = true;
            chartCursor.categoryBalloonDateFormat = "MMM";
            chart.addChartCursor(chartCursor);

            // SCROLLBAR
            var chartScrollbar = new AmCharts.ChartScrollbar();
            chart.addChartScrollbar(chartScrollbar);

            // WRITE
            chart.write("chartdiv");
        });
        return false;
    });
});
</script>
<div id="chartdiv" style="width: 100%; height: 400px;"></div>
<?php include tpl('footer');?>