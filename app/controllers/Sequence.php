<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: Sequence.php 153 2013-02-20 06:35:57Z zhangbin $
 */
class Sequence extends LdBaseCtrl {
	public static function getSequence($tblname) {
		$ssDao = new SequenceDao($tblname);
		$maxId = $ssDao->maxId();
		$seq = $ssDao->fetch($maxId);
		if (empty($seq)) {
			$ssDao->insert(array(
					'sequence' => 1,
					'currentDate' => gmdate(DATE_FORMAT)
			));
			$seq = '00001';
		} else {
			if (gmdate(DATE_FORMAT) != $seq['currentDate']) {//不是同一天
				$ssDao->update($seq['id'], array(
						'sequence' => 1,
						'currentDate' => gmdate(DATE_FORMAT)
				));
				$seq = '00001';
			} else {
				$ssDao->update($seq['id'], array('sequence' => $seq['sequence'] + 1));
				$seq = str_pad($seq['sequence']+1, 5, 0, STR_PAD_LEFT);
			}
		}	
		return $seq;
	}
}