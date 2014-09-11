<?php
return array(
	'index' => array('name'=>LG_MENU_STANDARD_INFO, 'submenu'=>array(	
		'bom'		=> array('name'=>LG_SUBMENU_SERVICE_BOM, 'submenu'=>array(
			LG_THIRDMENU_PHONE_BOM => 'bom/phoneBom',
			LG_THIRDMENU_MODEL => 'bom/model',
			LG_THIRDMENU_FAILURECODE=>'bom/failure',
		)),
		'warranty'  => array('name'=>LG_SUBMENU_WARRANTY_INFO, 'submenu'=>array(
			LG_THIRDMENU_PHONE_WARRANTY => 'warranty',
		)),
		'vendor'    => array('name'=>LG_SUBMENU_VENDOR_INFO, 'submenu'=>array(
			LG_THIRDMENU_VENDOR => 'service/vendor',
			LG_THIRDMENU_STATION => 'service/station',
			LG_THIRDMENU_CONTACT => 'service/contact',
		)),
		'meterial'  => array('name'=>LG_SUBMENU_METERIAL_INFO, 'submenu'=>array(
			LG_THIRDMENU_MAITROX_PARTS => 'parts',
			LG_THIRDMENU_VENDOR_PARTS  => 'partsPrice',
			LG_THIRDMENU_SUPPLIER_PARTS  => 'partsPrice/supplierPrice',
			LG_THIRDMENU_NOTICE  => 'notice',
		)),
		'support' => array('name' => LG_SUBMENU_SUPPORT, 'submenu' => array(
			LG_THIRDMENU_PARTS_SUBSTITUTION => 'PartsSubstitution',
			LG_THIRDMENU_ADD_PN => 'parts/addPartsMulti',
			LG_THIRDMENU_ADD_PARTS_PRICE => 'partsPrice/addPriceMulti',
			LG_THIRDMENU_ADD_MODEL => 'bom/modelMultiAdd',
			LG_THIRDMENU_ADD_BOM => 'bom/phoneBomMultiAdd',
			LG_THIRDMENU_SUPPLIER => 'supplier',
			LG_THIRDMENU_SC_POINT => 'scPoint',
			LG_THIRDMENU_SC_ROUTE => 'scRoute'
		))
	)),
	'planning' => array('name' => LG_MENU_PARTS_PLANNING, 'submenu' => array(
		'basicData' => array('name' => LG_SUBMENU_PARTS_PLANNING_BASIC_DATA, 'submenu' => array(
				LG_THIRDMENU_PARTS_PLANNING_SETTING => 'BasicData/index',
		)),
	)),
	'purchase' => array('name' => LG_MENU_PURCHASE, 'submenu' => array(
		'report' => array('name' => LG_SUBMENU_PURCHASE_REPORT, 'submenu' => array(
			LG_THIRDMENU_PARTS_APPLY => 'purchaseReport/apply'
		)),
		'order' => array('name' => LG_SUBMENU_PURCHASE_ORDER, 'submenu' => array(
			LG_THIRDMENU_PURCHASE_ORDER_CLAIM => 'purchaseOrder/index',
			LG_THIRDMENU_PURCHASE_ORDER_PN => 'purchaseOrder/parts'
		))
	))
);
?>