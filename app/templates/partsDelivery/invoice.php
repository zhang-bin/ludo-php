<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lenovo Service Record</title>
<style type="text/css">
*{margin:0 auto;padding:0}
body {font-family:  Verdana, Helvetica, Arial, sans-serif;font-size: 12px;line-height: 1.6em;color:#888;}
.mainbody{width:850px;height:auto;}
table{border-collapse:collapse;width:100%;}
table td {padding:3px 5px;text-align: left;font-weight:bold;color:#000;}
table td.center {padding:3px 5px;text-align: center;font-weight:bold;color:black;}
table.table th{padding: 10px;text-align:left;border: 1px solid #555;color:#000;}
table.table td{padding: 3px 5px;border: 1px solid #555;line-height: 22px;width:100px;color:#666;}
</style>
</head>
<body>
<div class="mainbody">
	<table>
		<tr><td colspan="5" style="font-size:26px;font-style:italic;line-height:40px;" class="center"><?=$order['shipperInfo']['companyShortName']?></td></tr>
		<tr><td colspan="5" style="font-size:20px;" class="center"><?=$order['shipperInfo']['companyName']?></td></tr>
		<tr><td colspan="5" class="center"><?=$order['shipperInfo']['address']?></td></tr>
		<tr>
			<td colspan="5" class="center">
				<span>TEL:<?=$order['shipperInfo']['telphone']?></span>
				<span>FAX:<?=$order['shipperInfo']['fax']?></span>
			</td>
		</tr>
		<tr><td colspan="5" style="font-size:18px;text-decoration:underline;line-height:50px;" class="center">COMMERCIAL INVOICE</td></tr>
		<tr>
			<td>Sold To:</td>
			<td><?=$order['consigneeInfo']['company']?></td>
			<td>Invoice No.:</td>
			<td><?=$order['code']?></td>
		</tr>
		<tr>
			<td></td>
			<td><?=$order['consigneeInfo']['address']?></td>
			<td>Contract No.:</td>
			<td><?=$order['code']?></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Contact:</td>
			<td><?=$order['consigneeInfo']['contact']?></td>
			<td>Date:</td>
			<td><?=date(DATE_FORMAT, strtotime($order['createTime']))?></td>
		</tr>
		<tr>
			<td>Tel:</td>
			<td><?=$order['consigneeInfo']['tel']?></td>
		</tr>
		<tr>
			<td>Fax:</td>
			<td><?=$order['consigneeInfo']['fax']?></td>
		</tr>
		<tr>
			<td colspan="4">SHIPMENT FROM:<?=$order['consigneeInfo']['shipmentFrom']?></td>
		</tr>
		<tr>
			<td colspan="4">FOR TRANSPORTATION TO:<?=$order['consigneeInfo']['transTo']?></td>
		</tr>
		<tr>
			<td colspan="4">PRICE TERM:<?=$order['consigneeInfo']['priceTerm']?></td>
		</tr>
	</table>
	<br />
	<table class="table">
		<tr>
			<th width="15%">PN</th>
			<th>Description of goods</th>
			<th width="10%">QTY<br />(PCS)</th>
			<th width="15%">Unit Price<br />(US$)</th>
			<th width="15%">Amount<br />(US$)</th>
		</tr>
		<?php 
		$total = $totalAmount = 0; 
		foreach ($details as $detail) {
			$unitPrice = Crypter::decrypt($detail['unitPrice']);
			$total += $detail['deliveryQty'];
			$amount = round($detail['deliveryQty'] * $unitPrice, 2);
			$totalAmount += $amount;
		?>
		<tr>
			<td><?=$detail['pn']?></td>
			<td><?=$detail['en']?></td>
			<td><?=$detail['deliveryQty']?></td>
			<td><?=round($unitPrice, 2)?></td>
			<td><?=$amount?></td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="2" style="text-align: right;color:#000;border:0 none;">Total</td>
			<td colspan="2" style="color:#000;border:0 none;"><?=$total?>PCS</td>
			<td style="color:#000;border:0 none;">US$<?=$totalAmount?></td>
		</tr>
	</table>
</div>
</body>
</html>