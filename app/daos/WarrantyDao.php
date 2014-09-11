<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: WarrantyDao.php 153 2013-02-20 06:35:57Z zhangbin $
 */
class WarrantyDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PhoneWarranty');
	}
	
	
	public function getList($pager, $condition = null, $params = null){
		if(empty($condition)){
			return $this->tbl
                    ->setField('PhoneWarranty.*, Model.name as modelName')
	                ->leftJoin('Model','PhoneWarranty.pn=Model.pn')
	                ->limit($pager['rows'],$pager['start'])
	                ->fetchAll();
		} else {
			return $this->tbl
                    ->setField('PhoneWarranty.*, Model.name as modelName')
	                ->leftJoin('Model','PhoneWarranty.pn=Model.pn')
	                ->where($condition, $params)
	                ->limit($pager['rows'],$pager['start'])
	                ->fetchAll();
		}
	}
}