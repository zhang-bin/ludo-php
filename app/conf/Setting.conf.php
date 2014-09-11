<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
return array(
		'transport' => array(
				BasicData::TAT_TRANS_WAY_AIR => 'Air Transport',
				BasicData::TAT_TRANS_WAY_SHIP => 'Ship Transport',
				BasicData::TAT_TRANS_WAY_CAR => 'Truck Transport'
		),
		'poType' => array(
				BasicData::LT_PSI => 'PSI',
				BasicData::LT_NPI => 'NPI',
				BasicData::LT_EOL => 'EOL',
		),
		'tatType' => array(
				BasicData::TAT_TYPE_IN_HOUSE => 'In House',
				BasicData::TAT_TYPE_OUTSIDE => 'Outside'
		)
);