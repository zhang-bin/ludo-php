<?php
$gTitle = LG_USE_NUMBER_CHART;
$gToolbox = '<a href="'.url('report/model').'"><i class="icon-table"></i> '.LG_USE_NUMBER_LIST.'(by model)</a>';
include tpl('header');
Load::js('amcharts');
?>
<form id="form1" class="form-inline">
    <select name="country[]" class="selectpicker" data-live-search="true" multiple="multiple" data-selected-text-format="count">
        <?php foreach ($countries as $country) {?>
            <option value="<?=$country['country']?>"><?=$country['country']?></option>
        <?php }?>
    </select>
    <select name="model" class="selectpicker" data-live-search="true">
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
        $.getJSON("<?=url('report/modelChartData')?>", $(this).serializeArray(), function(result){
            $("#pie, #chartTbl").empty();
            // SERIAL CHART
            chart = new AmCharts.AmSerialChart();
            chart.pathToImages = "<?=SITE_URL.'/img/amcharts/images/'?>";
            chart.dataProvider = result;
            chart.categoryField = "month";
            chart.dataDateFormat = "YYYY-MM";
            chart.startDuration = 1;
            chart.startEffect = 'easeOutSine';
            chart.theme = AmCharts.themes.chalk;
            chart.addListener("clickGraphItem", function(item){
                $("#pieContainer").show();
                var date = new Date(item.item.category);
                var month = date.getMonth();
                var year = date.getFullYear();
                $("#chartTbl").loading("<?=url('report/modelChartTbl')?>",
                    $("#form1").serialize()+"&month="+month+"&year="+year);
                $.getJSON("<?=url('report/modelPieChartDate')?>", $("#form1").serialize()+"&month="+month+"&year="+year, function(result){
                    // PIE CHART
                    pieChart = new AmCharts.AmPieChart();
                    pieChart.dataProvider = result;
                    pieChart.titleField = "category";
                    pieChart.valueField = "value";
                    pieChart.sequencedAnimation = true;
                    pieChart.startEffect = "elastic";
                    pieChart.innerRadius = "30%";
                    pieChart.startDuration = 2;
                    pieChart.labelRadius = 15;
                    pieChart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
                    // this makes the chart 3D
                    pieChart.depth3D = 15;
                    pieChart.angle = 30;

                    // WRITE
                    pieChart.write("pie");

                });
            });

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
            graph.type = "column";
            graph.valueAxis = valueAxis; // we have to indicate which value axis should be used
            graph.title = 'Monthly Usage';
            graph.valueField = 'qty';
            graph.fillAlphas = 1;
            graph.balloonText = "[[value]]";
            graph.legendValueText = "[[value]]";
            chart.addGraph(graph);

            var graph = new AmCharts.AmGraph();
            graph.type = "line";
            graph.valueAxis = valueAxis; // we have to indicate which value axis should be used
            graph.title = 'Monthly Usage';
            graph.valueField = 'qty';
            graph.bullet = "round";
            graph.hideBulletsCount = 30;
            graph.bulletBorderThickness = 1;
            graph.balloonText = "[[value]]";
            graph.legendValueText = "[[value]]";
            chart.addGraph(graph);

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
<div class="row">
    <div class="portlet span8 hide" id="pieContainer">
        <div class="portlet-title">
            <div class="caption">
                Proportion of Spare Parts Usage Pie Chart
            </div>
        </div>
        <div class="portlet-body" id="pie" style="height: 417px;">
        </div>
    </div>
    <div id="chartTbl" style="width: 363px;float:left;"></div>
</div>
<?php include tpl('footer');?>