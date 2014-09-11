<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lenovo Service Record</title>
<style type="text/css">
*{margin:0 auto;padding:0}
body {font-family:  Verdana, Helvetica, Arial, sans-serif;font-size: 12px;line-height: 1.6em;color:#888;}
.mainbody{width:650px;height:auto;}
table{border-collapse:collapse;width:100%;}
table.small_table td{border-top:0!important;border-left:0!important;border-right:0!important;}
table.small_table td.F{border-bottom:0!important;}
table.table caption{font-size:20px;font-weight:600;color:#555;padding-top:10px;}
table.table th{padding: 10px;text-align:left;}
table.table th.B{text-align:left;font-weight:100;}
table.table td{padding: 3px 5px;border: 1px solid #555;line-height: 22px;width:100px;}
table.table tr.C td{text-align:center;}
table.table .T{font-weight:600;color:#555;}
table.table .D{height:40px;vertical-align:top;}
dd{margin-left:50px;}
</style>
</head>
<body>
<div class="mainbody">
    <div>Appendix 1 <br />
    	<span>Number of Customer：100105725 </span>                        <span style="float: right"><font color="red">Customer’s Order No.：<?=$order['code']?></font></span>
    </div>
    <br />
    <div style="float:left">Lenovo Mobile Communication (Wuhan) Company Limited  Add.：No 19,<br /> 
	    High-Tech 4 Road, East Lake High-tech Development Zone, Hubei Province,<br /> 
	    P.R.China
    </div>
    <div style="float:left"><img src="<?=timg('lsr.jpg')?>"  alt="联想"/></div>
	<table class="table">
    	<caption>Purchase Agreement</caption>
      	<tr>
	        <td></td>
	        <td>Buyer</td>
	        <td>Consignee</td>
	        <td>Final Destination</td>
      	</tr>
      	<tr>
	        <td>Company Name:</td>
	        <td>Shanghai Maitrox Electronics Co., Ltd.</td>
	        <td>TOZAI TRADE(GROUP)CO.,LIMITED</td>
	        <td>TOZAI TRADE(GROUP)CO.,LIMITED</td>
      	</tr>
      	<tr>
	        <td>Address:</td>
	        <td>ROOM 309 TOMSON COMMERCIAL BUILDING NO.710 DONGFANG ROAD PUDONG</td>
	        <td>Unit 18, 5/FLOOR, INTERNATIONAL PLAZA, NO.20 Sheung Yuet Road, Kowloon, Hong Kong</td>
	        <td>Unit 18, 5/FLOOR, INTERNATIONAL PLAZA, NO.20 Sheung Yuet Road, Kowloon, Hong Kong</td>
      	</tr>
      	<tr>
	        <td>Contact Person:</td>
	        <td>Lecsia</td>
	        <td>何鑫</td>
	        <td>何鑫</td>
		</tr>
      	<tr>
      		<td>Tel:</td>
	        <td>86-21-58207770-190</td>
	        <td>00852-39960709</td>
	        <td>00852-39960709</td>
      	</tr>
      	<tr>
        	<td>Fax:</td>
	        <td>86-21-50580551</td>
	        <td></td>
	        <td></td>
      	</tr>
       	<tr>
        	<td colspan="4">Detail Information of the Goods ordered by buyer from supplier(Currency unit:<?=$order['currency']?>)</td>
      	</tr>
	</table>
	<table class="table">
      	<tr>
	        <td>Product Coding</td>
	        <td>Product Name/Color</td>
	        <td>Quantity</td>
	        <td>Unit Price</td>
	        <td>Amount</td>
	        <td>TOTAL</td>
      	</tr>
      	<?php foreach($details as $v){?>
      		<tr>
      			<td><?=$v['pn']?></td>
      			<td><?=$v['en']?></td>
      			<td><?=$v['qty']?></td>
      			<td><font color="red"><?=$order['currency'].$v['unitPrice']?></font></td>
      			<td><?=$order['currency'].$v['amount']?></td>
      			<td></td>
      		</tr>
      	<?php }?>
      	<tr>
	        <td>Sum Total</td>
	        <td>——</td>
	        <td><?=$sum['qty']?></td>
	        <td></td>
	        <td><?=$order['currency'].$sum['amount']?></td>
	        <td></td>
      	</tr>
    </table>  	
    <table class="table">
      	
      	<tr>
	        <td>Terms Of Delivery</td>
	        <td>Means of Transportation</td>
	        <td colspan="4" ><font color="red">Partial Shipment </font></td>
	        <td>Transshipments</td>
      	</tr>
      	<tr>
	        <td>Ex-work Lenovo XMP</td>
	        <td>Part Number</td>
	        <td colspan="4">Prohibited</td>
	        <td>Allowed</td>
      	</tr>
      	<tr>
	        <td colspan="7">
	        <dl>
	        	<dt>1、Payment Term:</dt>
		        <dd><h3>■T/T  :</h3>
		        	<p> 
		        	<font color="red">
				        Full name of the Payee：Lenovo Mobile Communication (Wuhan) Company Limited<br />
				        Name of Banker: INDUSTRIAL AND COMMERCIAL BANK OF CHINA WUHAN EAST LAKE BRANCH <br />
				        Address：LENGSHUIPU 1# LUOYU  ROAD WUCHANG，WUHAN CHINA <br />
				        Account Number：3202009019200421534 (USD)<br />
				        SWIFT Code：ICBKCNBJHUB
				    </font>    
			        </p>
		        </dd>
		       <dt> 2、Required Documents:</dt>
		       <dd>
			        <p>
				        □Commercial Invoice in 1 □ Packing List one in 1<br />
				        □Certificate of Origin  ( China or Chamber of Commerce ) china <br />
				        □Other Documents:<br />
		            </p>
	            </dd>
	            <dt>
	            <font color="red">
				        3、This purchase order shall be the effective appendix of the Terms of Product agreement of Lenovo for Cooperation on Service parts in 2012<br />
				        Fiscal Year and General Terms of Lenovo for Cooperation on Service parts in 2012 Fiscal Year signed by both sides. For those <br />
				        matter unexhausted here, the relevant provisions in the Terms of Product agreement of Lenovo for Cooperation on Service parts in 2012<br />
			            Fiscal Year and General Terms of Lenovo for Cooperation on service parts in 2012 Fiscal Year shall be referred to.<br />
			    </font>        
			    </dt>
			    <dt>
			            4、Buyer shall fill in this purchase order form carefully. This purchase order shall be effective after the signature of authorized person of both parties.<br />
            	</dt>	
	        </dl>       
	        <br />
			    <span style="float: left">Supplier：<u>Lenovo Mobile Communication (Wuhan) Company Limited.</u><br />Signature/Seal:                     　</span>
			    <span style="float: right"><u>Buyer:  Shanghai Maitrox Electronics Co., Ltd.</u><br />Signature/Seal:             </span>
			    <br />
			    <br />
			    <br />
			    <br />
			    <br />
	        </td>
      	</tr>
	</table>
	<br />
</div>
</body>
</html>