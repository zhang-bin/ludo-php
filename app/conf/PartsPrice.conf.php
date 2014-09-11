<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
return array(
		'type' => array(
				PartsPrice::TYPE_PURCHASE => 'Purchase Price',
				PartsPrice::TYPE_OOW => 'Sales Price',
				PartsPrice::TYPE_COST => 'Cost Price'
		),
		'currency' => array(
				PartsPrice::CURRENCY_RMB => 'RMB',
				PartsPrice::CURRENCY_USD => 'USD'
		)
);