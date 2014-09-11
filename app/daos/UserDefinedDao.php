<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: UserDefinedDao.php 153 2013-02-20 06:35:57Z zhangbin $
 */
class UserDefinedDao extends LdBaseDao {
	public function __construct($name) {
		parent::__construct(ucfirst($name));
	}
}