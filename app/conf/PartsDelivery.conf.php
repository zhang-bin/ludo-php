<?php
return array(
	'factory' => array(
		'status' => array(
			PartsDelivery::STATUS_FACTORY_PROCESS => 'process',
			PartsDelivery::STATUS_FACTORY_SUBMIT => 'submited',
			PartsDelivery::STATUS_FACTORY_CLOSE => 'closed',
			PartsDelivery::STATUS_FACTORY_CANCEL => 'cancel',
		)
	),
	'warehouse' => array(
			'status' => array(
					PartsDelivery::STATUS_WAREHOUSE_PROCESS => 'process',
					PartsDelivery::STATUS_WAREHOUSE_SUBMIT => 'submited',
					PartsDelivery::STATUS_WAREHOUSE_CLOSE => 'closed',
					PartsDelivery::STATUS_WAREHOUSE_CANCEL => 'cancel',
			)
	)
);