<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class SupplierDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('Supplier');
	}
	
	public function getInfo($id) {
		return $this->hasA('File a', 'a.filename as businessLicenseName, a.path as businessLicensePath', 'businessLicenseId')
					->hasA('File b', 'b.filename as taxRegistrationCertName, b.path as taxRegistrationCertPath', 'taxRegistrationCertId')
					->hasA('File c', 'c.filename as organCodeCertName, c.path as organCodeCertPath', 'organCodeCertId')
					->fetch($id);
	} 
}