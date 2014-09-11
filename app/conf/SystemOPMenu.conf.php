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
			LG_THIRDMENU_NOTICE  => 'notice',
		))
	)),
	'process' => array('name'=>LG_MENU_PROCESS, 'submenu'=>array(
		'query' => array('name'=>LG_SUBMENU_QUERY, 'submenu'=>array(
			LG_THIRDMENU_ENTITLEMENTS => 'Warrantylookup',
			LG_THIRDMENU_BOM => 'Bomsearch',
			LG_THIRDMENU_PN => 'Parts/pn'
		)),
		'service' => array('name'=>LG_SUBMENU_SERVICE_MANAGE, 'submenu'=>array(
			LG_THIRDMENU_SERVICE_ORDER => 'serviceManage',
			LG_THIRDMENU_SERVICE_ORDER_PICKUP => 'serviceManage/serviceOrderPickupList',
			LG_THIRDMENU_SERVICE_ORDER_APPLY => 'serviceManage/serviceOrderApply',
			LG_THIRDMENU_SERVICE_ORDER_TURN => 'serviceManage/serviceOrderTurn',
			LG_THIRDMENU_LAS_SERVICE_ORDER => 'LASServiceOrder',
		)),
		'doa' => array('name'=>LG_SUBMENU_DOA_MANAGE, 'submenu'=>array(
			LG_THIRDMENU_DOA_ORDER => 'DOAManage'
		)),
// 		'logistics' => array('name'=>LG_SUBMENU_LOGISTICS_MANAGE, 'submenu'=>array(
// 		    LG_THIRDMENU_LOGISTICS_ORDER=> 'logisticsOrder'
// 		)),	
		'spare' => array('name'=>LG_SUBMENU_SPARE_MANAGE, 'submenu'=>array(
			LG_THIRDMENU_WAREHOUSE=>'warehouse',
			LG_THIRDMENU_INVENTROY=>'inventory',
			LG_THIRDMENU_INVENTROY_SN=>'inventory/inventorySN',
			LG_THIRDMENU_SHIPPING_ORDER=>'shippingOrder',
			LG_THIRDMENU_RECEIVED_ORDER=>'shippingOrder/receivedOrder',
			LG_THIRDMENU_SCRAP_MANAGEMENT=>'Scrap',
			LG_THIRDMENU_SHIPPING_AUTHORIZATION=>'shippingAuthorization',
		)),
		'repair' => array('name'=>LG_SUBMENU_L3_REPAIR, 'url'=>'repair/mainboard'),
		'finance'=>array('name'=>LG_SUBMENU_FINANCE_MANAGE, 'submenu'=>array(
			LG_THIRDMENU_SERVICE_ORDER_REPORT => 'serviceManage/serviceOrderReport',
			LG_THIRDMENU_CUSTOMER_COMPLAINS_REPORT=>'complain/report',
			LG_THIRDMENU_DOA_ORDER_REPORT=>'DOAManage/report',
			LG_THIRDMENU_SERVICE_ORDER_PARTS_REPORT=>'serviceManage/serviceOrderPartsReport',
			LG_THIRDMENU_SHIPPING_ORDER_REPORT=>'shippingOrder/report',
			LG_THIRDMENU_LAS_SERVICE_ORDER_REPORT => 'LASServiceOrder/report',
// 			LG_THIRDMENU_INVOICE=>'invoice/invoicelist',
// 			LG_THIRDMENU_PARTS=>'invoice/partslist',			
// 			LG_THIRDMENU_PAYMENT=>'Payment'
		)),
	)),	
	'CRM' => array('name'=>LG_MENU_CRM,'submenu'=>array(
		'customerService' => array('name'=>LG_SUBMENU_CUSTOMER_SERVICE_MANAGE, 'submenu'=>array(
			LG_THIRDMENU_CUSTOMER_COMPLAINS=>'Complain',
		)),
	)),
	'planning' => array('name' => LG_MENU_PARTS_PLANNING, 'submenu' => array(
			'basicData' => array('name' => LG_SUBMENU_PARTS_PLANNING_BASIC_DATA, 'submenu' => array(
					LG_THIRDMENU_PARTS_PLANNING_SETTING => 'BasicData/index',
					LG_THIRDMENU_PARTS_PLANNING_PURCHASE => 'BasicData/purchase',
					LG_THIRDMENU_PARTS_PLANNING_FAILURE_RATE => 'BasicData/failure',
					LG_THIRDMENU_PARTS_PLANNING_WARRANTY => 'BasicData/warranty',
			)),
			'report' => array('name' => LG_SUBMENU_PARTS_PLANNING_REPORT, 'submenu' => array(
					LG_THIRDMENU_PARTS_PLANNING_USE_NUMBER => 'report/useNumber',
					LG_THIRDMENU_PARTS_PLANNING_INVENTORY => 'report/inventory',
					LG_THIRDMENU_PARTS_PLANNING_SHIPPING => 'report/shipping',
					LG_THIRDMENU_PARTS_PLANNING_PN => 'report/pnPlanning',
					LG_THIRDMENU_PARTS_PLANNING_PARTS => 'report/partsPlanning',
			)),
			'failure' => array('name' => LG_SUBMENU_FAILURE_RATE, 'submenu' => array(
					LG_THIRDMENU_FAILURE_RATE => 'failureRate/index',
			)),
			'history' => array('name' => LG_SUBMENU_PARTS_HISTORY, 'submenu' => array(
					LG_THIRDMENU_PARTS_HISTORY => 'parts/history'
			)),
			'warning' => array('name' => LG_SUBMENU_TO_WARNNING, 'submenu' => array(
					LG_THIRDMENU_TO_WARNING => 'warning'
			))
	)),
	'purchase' => array('name' => LG_MENU_PURCHASE, 'submenu' => array(
			'report' => array('name' => LG_SUBMENU_PURCHASE_REPORT, 'submenu' => array(
					LG_THIRDMENU_PARTS_APPLY => 'purchaseReport/apply'
			))
	)),
);
?>