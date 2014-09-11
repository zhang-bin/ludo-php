<?php
$gTitle = LG_FAILURE_RATE_CHART;
$gToolbox = '<a href="'.url('failureRate').'"><i class="icon-table"></i> '.LG_FAILURE_RATE.'</a>';
include tpl('header');
Load::js('amcharts');
?>
<form id="form1" class="form-inline">
    <select name="model" id="model" class="selectpicker" data-live-search="true">
        <option value="0">All Model</option>
        <?php foreach ($models as $model) {?>
            <option value="<?=$model?>"><?=$model?></option>
        <?php }?>
    </select>
    <select name="partsCategory" id="partsCategory" class="selectpicker" data-live-search="true">
        <option value="0">All Parts Category</option>
        <?php foreach ($partsCategories as $partsCategory) {?>
            <option value="<?=$partsCategory['id']?>"><?=$partsCategory['partsGroupName']?></option>
        <?php }?>
    </select>
    <input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#form1").submit(function(){
        if ($("#model").val() == "0") {
            alert("<?=LG_FAILURE_RATE_MODEL_NOT_INPUT?>");
            return false;
        } if ($("#partsCategory").val() == "0") {
            alert("<?=LG_FAILURE_RATE_CATEGORY_NOT_INPUT?>");
            return false;
        }
        $.getJSON("<?=url('failureRate/chartByShipmentData')?>", $(this).serializeArray(), function(result){
            // SERIAL CHART
            chart = new AmCharts.AmSerialChart();
            chart.pathToImages = "<?=SITE_URL.'/img/amcharts/images/'?>";
            chart.dataProvider = result['data'];
            chart.categoryField = "month";
            chart.dataDateFormat = "YYYY-MM";
            chart.startDuration = 1;
            chart.startEffect = 'easeOutSine';
            chart.theme = AmCharts.themes.chalk;


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
            $.each(result['title'], function(k, v){
                var graph = new AmCharts.AmGraph();
                graph.type = "line";
                graph.valueAxis = valueAxis; // we have to indicate which value axis should be used
                graph.title = k;
                graph.valueField = v;
                graph.bullet = "round";
                graph.hideBulletsCount = 30;
                graph.bulletBorderThickness = 1;
                graph.balloonText = "[[value]]";
                graph.legendValueText = "[[value]]";
                chart.addGraph(graph);
            });
            // CURSOR
            var chartCursor = new AmCharts.ChartCursor();
            chartCursor.cursorAlpha = 0.1;
            chartCursor.fullWidth = true;
            chartCursor.categoryBalloonDateFormat = "MMM-YYYY";
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