<?php
return array(
		'product' 	=> array('name' => 'Product', 'submenu' => array(
				'product' => array('name' => 'Phone Bom', 'include' => array('product/index', 'product/phoneBom', 'product/phoneBomList')),
				'product/warranty' => array('name' => 'Phone Sales Volume', 'submenu' => array(
                    'product/forecastWarranty' => array('name' => 'FCST Phone Sales Volume', 'include' => array('product/forecastWarrantyReport', 'product/importForecastWarranty')),
                    'product/warranty' => array('name' => 'Historical Phone Sales Volume', 'include' => array('product/warrantyReport', 'product/autoWarranty', 'product/warrantyChart')),
                ))
		)),
		'basicData/partsGroup' => array('name' => 'Parts', 'submenu' => array(
				'basicData/partsGroup' => array('name' => 'Parts Category', 'include' => array('basicData/changePartsGroup', 'basicData/addPartsGroup', 'basicData/delPartsGroup')),
				'parts' => array('name' => 'Parts Specification', 'include' => array('parts/index', 'parts/partsList')),
//				'partsSubstitution/substitution' => array('name' => 'Parts Substitution', 'include' => array('partsSubstitution', 'partsSubstitution/index', 'partsSubstitution/import', 'partsSubstitution/report')),
                'parts/cluster' => array('name' => 'Parts Cluster', 'include' => array('parts/clusterAdd', 'parts/clusterChange'))
		)),
		'warehouse' => array('name' => 'Warehouse', 'submenu' => array(
				'warehouse' => array('name' => 'Warehouse List', 'include' => array('warehouse/index', 'warehouse/tbl')),
                'inventory' => array('name' => 'Inventory', 'include' => array('inventory/index'))
		)),
		'partsPrice/exchangeRate' => array('name' => 'Finance', 'submenu' => array(
				'partsPrice/exchangeRate' => array('name' => 'Exchange Rate', 'include' => array('partsPrice/changeExchangeRate')),
				'partsPrice/supplierPrice' => array('name' => 'Parts Price', 'include' => array('partsPrice/history', 'partsPrice/supplierPriceReport', 'partsPrice/modifySupplierPrice'))	
		)),
		'basicData' => array('name' => 'Planning Basic Data', 'submenu' => array(
				'basicData' => array('name' => 'Basic Parameter', 'include' => array('basicData/index', 'basicData/basic', 'basicData/changeBasic')),
				'basicData/pnLt' => array('name' => 'Standard Purchase L/T', 'include' => array('basicData/pnLtTbl', 'basicData/addPnLt', 'basicData/importPnLt', 'basicData/changePnLt')),
				'scPoint' => array('name' => 'Supply Chain TAT', 'submenu' => array(
						'scPoint' => array('name' => 'Depot', 'include' => array('scPoint/index', 'scPoint/tbl', 'scPoint/add', 'scPoint/change', 'scPoint/del')),
						'basicData/tat' => array('name' => 'Depot to Depot', 'include' => array('basicData/tatTbl', 'basicData/addTat', 'basicData/changeTat')),
						'scRoute' => array('name' => 'Supply Chain Route', 'include' => array('scRoute/add', 'scRoute/change', 'scRoute/del')),
				)),
				'basicData/mffr' => array('name' => 'Failure Rate Setting', 'submenu' => array(
                        'basicData/npiMffr' => array('name' => 'NPI Failure Rate', 'include' => array('basicData/addNpiMffr', 'basicData/changeNpiMffr', 'basicData/importNpiMffr')),
                        'basicData/mffr' => array('name' => 'Historical Failure Rate', 'include' => array('basicData/mffrTbl', 'basicData/changeMffr', 'basicData/syncMffr', 'basicData/uploadMffr'))
                )),
				'holiday' => array('name' => 'Country Holiday', 'include' => array('holiday/index', 'holiday/import')),
                'product/warrantySetting' => array('name' => 'Percentage of Country Sales Volume Setting', 'include' => array('product/warrantySettingList', 'product/addWarrantySetting', 'product/changeWarrantySetting'))
		)),
		'report/partsPlanning' => array('name' => 'Parts Planning', 'submenu' => array(
                'demand' => array('name' => 'Demand Forecast', 'submenu' => array(
                    'demand/failureRate' => array('name' => 'by Failure Rate', 'include' => array())
                )),
				'npi' => array('name' => 'NPI Plan', 'include' => array('npi/add', 'npi/change')),
				'psi' => array('name' => 'PSI Plan', 'include' => array('psi/report', 'psi/reset')),
				'#2' => array('name' => 'EOL Plan'),
				'replenish' => array('name' => 'Weekly Replenish Plan'),
				'warning' => array('name' => 'Risk Alert', 'include' => array('warning/index')),
		)),
		'purchaseOrder' => array('name' => 'Purchase', 'submenu' => array(
				'supplier' => array('name' => 'Supplier', 'include' => array('supplier/index', 'supplier/add', 'supplier/change')),
				'purchaseOrder' => array('name' => 'PO Claim', 'include' => array('purchaseOrder/index', 'purchaseOrder/add', 'purchaseOrder/change', 'purchaseOrder/pn', 'purchaseOrder/approve')),
				'purchaseOrder/parts' => array('name' => 'PO Query', 'include' => array('purchaseOrder/partsTbl', 'purchaseOrder/add', 'purchaseOrder/change')),

		)),
		'partsDelivery' => array('name' => 'PO IMTR', 'submenu' => array(
				'partsDelivery' => array('name' => 'Parts Delivery Operation', 'include' => array('partsDelivery/index', 'partsDelivery/compare', 'partsDelivery/compareConfirm')),
				'partsDelivery/factory' => array('name' => 'Delivery From Factory', 'include' => array('partsDelivery/factoryAdd', 'partsDelivery/factoryChange',
						'partsDelivery/factoryCancel', 'partsDelivery/factoryClose', 'partsDelivery/factoryDuplicate')),
				'partsDelivery/warehouse' => array('name' => 'Delivery From Warehouse', 'include' => array('partsDelivery/warehouseAdd', 'partsDelivery/warehouseChange',
						'partsDelivery/warehouseCancel', 'partsDelivery/warehouseClose', 'partsDelivery/warehouseDuplicate')),
				'partsDelivery/consignee' => array('name' => 'Parts Consignee', 'include' => array('partsDelivery/addConsignee')),
				'partsDelivery/shipper' => array('name' => 'Parts Shipper', 'include' => array('partsDelivery/addShipper'))
		)),
		'report' => array('name' => 'Report', 'submenu' => array(
				'report/index' => array('name' => 'Monthly Usage', 'submenu' => array(
                    'report/useNumber' => array('name' => 'By Vendor', 'include' => array('report/useNumberList', 'report/useNumberChart', 'report/inventoryChart')),
                    'report/model' => array('name' => 'By Model', 'include' => array('report/modelList', 'report/modelChart'))
                )),
				'report/inventory' => array('name' => 'Parts Inventory', 'include' => array('report/inventoryList')),
				'report/shipping' => array('name' => 'Shipping On Way', 'include' => array('report/shippingList')),
				'abcClass' => array('name' => 'Parts ABC Classify', 'include' => array('abcClass/index', 'abcClass/tbl')),
				'kpi' => array('name' => 'KPI Report', 'include' => array('kpi/index', 'kpi/chartIndex')),
				'failureRate' => array('name' => 'Failure Rate', 'include' => array('failureRate/index', 'failureRate/failureRateList', 'failureRate/chartByShipment')),
		)),
);
