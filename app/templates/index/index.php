<?php
$gTitle = 'Home';
include tpl('header');
?>
<br>
<br>
<h3 align="center">Welcome to Lenovo Mobile Service System(CRM)</h3>
<h3 align="center">(Version 0.990b)</h3>
<br>
<br>
<br>
<h4 align="center"><a style="color:#FF0000">Update on Oct 11st, 2013</a></h4>
<h5 align="center">SR status 'Parts available or in transit' replaces old status 'Parts availble now, need repair or transfer' for better understanding<h5>
<br>
<h4 align="center"><a style="color:#FF0000">Update on Sep 23rd, 2013</a></h4>
<h5 align="center">Add remark field for Service Order for service center record nessesary information<h5>
<br>
<h4 align="center"><a style="color:#FF0000">OOW price now can be queried in CRM</a></h4>
<h5 align="center">Using Menu: Processes > Query > PN Query<h5>
<br>
<h4 align="center"><a style="color:#FF0000">Important Notice on 28th July, 2013</a></h4>
<h5 align="center">1. New SR status introduced: "Parts available now, need repair or transfer". When you see this status after applying spare parts, please check with the central warehouse in your country. The SR is re-opened, click on "Repair" button when you received the applied parts<h5>
<h5 align="center">2. SR status can be automatically changed back to "Apply for parts" by CRM each morning, if all available parts were used up from other service centers. Try to book the applied parts from your central warehouse as early as possible when you see above new SR status.<h5>
<h5 align="center">3. As a system hint, stock available parts with substitution PN will be displayed in SR List, you can request substitution parts to repair and remember to key-in or choose substitution PN in SR replacment PN fields<h5>
<br>
<h4 align="center"><a style="color:#FF0000">Notice on 2nd July, 2013</a></h4>
<h5 align="center">1. Part Subsitituion PN enabled in both PN Query(Beta) and SR Replacement PN fields <h5>
<h5 align="center">2. DOA Repair Beta function activated <h5>
<h5 align="center">3. Shipping Order search by SO# enabled <h5>
<br>
<h4 align="center"><a style="color:#FF0000">Important Notice on 15 May, 2013</a></h4>
<h5 align="center">1. Lenovo Main IT system has been migrated to a new one on 6th May, 2013 <h5>
<h5 align="center">2. In CRM, you will find<a style="color:#FF0000"> New PN </a>has been implemented for both spare parts and phone models<h5>
<h5 align="center">3. Using 'PN Query' tool under Menu Processes->Query, you can check every existing Old PN and New PN<h5>
<h5 align="center">4. In most of the functions and reports, we have provided comparion columns for New PN and Old PN<h5>
<h5 align="center">5. Contact Lenovo if you need an Excel version of New PN/Old PN mapping list<h5>
<h5 align="center">6. Write to support@lenovomobileservice.com immediately when you encounter a problem.<h5>

<br>
<br>
<?php include tpl('footer');?>