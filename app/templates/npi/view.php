<?php
$gTitle = 'View NPI Plan';
include tpl('header');
$from = strtotime($npi['createTime']);
?>
<div class="form-horizontal">
    <div class="control-group">
        <label class="control-label"><strong>NPI Code</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['code']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>Service Vendor</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['countryShortName']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>Product Series</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['productSeries']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>Planning Months</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['planningMonths']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>FG Sales Volume</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['forecastSalesNumber']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>Remark</strong>:</label>
        <div class="controls"><p class="form-control-static"><?=$npi['remark']?></p></div>
    </div>
    <div class="control-group">
        <label class="control-label"><strong>PN</strong>:</label>
        <div class="controls">
            <div class="accordion" id="accordion2">
                <?php if (!empty($pns)) {foreach ($pns as $model => $pn) {?>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?=$model?>"><?=$model?></a>
                        </div>
                        <div id="collapse_<?=$model?>" class="accordion-body collapse in">
                            <div class="accordion-inner">
                                <table class="table table-hovered">
                                    <tr>
                                        <th rowspan="2">PN</th>
                                        <th rowspan="2">Parts Category</th>
                                        <th rowspan="2">Parts Name</th>
                                        <th rowspan="2">Parts Price</th>
                                        <th colspan="6">Monthly Failure Rate</th>
                                        <th rowspan="2">FCST NPI PO Qty</th>
                                    </tr>
                                    <tr>
                                        <?php
                                        for($i = 1; $i <= 6; $i++) {
                                            echo '<th>'.$i.'</th>';
                                        }
                                        ?>
                                    </tr>

                                    <?php if (!empty($pn)) {foreach ($pn as $v) {?>
                                        <tr>
                                            <td><?=$v['pn']?></td>
                                            <td><?=$v['partsGroup']?></td>
                                            <td><?=$v['en']?></td>
                                            <td><?=Crypter::decrypt($v['unitPrice'])?></td>
                                            <?php
                                            $rates = json_decode($v['rate'], true);
                                            for ($i = 1; $i <= 6; $i++) {
                                                echo '<td>';
                                                if (!empty($rates)) {
                                                    $month = date('Y-m', strtotime('+'.$i.' month', $from));
                                                    foreach ($rates as $rate) {
                                                        if ($rate['month'] == $month) {
                                                            echo $rate['rate'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo '</td>';
                                            }
                                            ?>
                                            <td><?=$v['poQty']?></td>
                                        </tr>
                                    <?php }}?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php }}?>
            </div>
        </div>
    </div>
</div>
<?php include tpl('footer');?>