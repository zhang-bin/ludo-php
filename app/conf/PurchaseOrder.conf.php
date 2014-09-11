<?php
return array(
		'type' => array(
				PurchaseOrder::TYPE_NPI => 'NPI',
				PurchaseOrder::TYPE_PSI => 'PSI',
				PurchaseOrder::TYPE_EOL => 'EOL',
				PurchaseOrder::TYPE_OMP => 'OMP',
		),
		'status' => array(
				PurchaseOrder::STATUS_PROCESS => 'unapply',
				PurchaseOrder::STATUS_COMMIT => 'approving',
				PurchaseOrder::STATUS_APPROVE => 'approved',
				PurchaseOrder::STATUS_BACK => 'reject',
				PurchaseOrder::STATUS_CLOSED => 'close',
		),
		'warranty' => array(
				PurchaseOrder::WARRANTY_IN => 'IW',
				PurchaseOrder::WARRANTY_OUT => 'OOW'
		),
		'currency' => array(
				PartsPrice::CURRENCY_RMB => 'RMB',
				PartsPrice::CURRENCY_USD => 'USD'
		),
		'pnStatus' => array(
				PurchaseOrder::PN_STATUS_OPEN => 'open',
				PurchaseOrder::PN_STATUS_CLOSE => 'close',
				PurchaseOrder::PN_STATUS_CANCEL => 'cancel'
		),
		'closeReason' => array(
				PurchaseOrder::PN_CLOSE_VENDOR_NOT_ETD => 'Vendor No ETD',
				PurchaseOrder::PN_CLOSE_LOGISTICS_BROKEN => 'Logistics Broken',
				PurchaseOrder::PN_CLOSE_MISOPERATION => 'Misoperation',
				PurchaseOrder::PN_CLOSE_PO_MODIFICATION => 'PO Modification',
				PurchaseOrder::PN_CLOSE_EXCESS_OF_RECEIPT => 'Excess of Receipt'
		)
);