<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class LogisticsRoutesInfoDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('LogisticsRoutesInfo');
	}
	
	public function getList($condition = '', $params = array(), $pager = array()) {
		return $this->tbl['s']
					->leftJoin('LogisticsRoutes', 'LogisticsRoutes.id = LogisticsRoutesInfo.logisticsRoutesId', 'LogisticsRoutes.from,LogisticsRoutes.to')
					->leftJoin('ScPoint b', 'LogisticsRoutes.from = b.id', 'b.point as fromPoint')
					->leftJoin('ScPoint c', 'LogisticsRoutes.to = c.id', 'c.point as toPoint')
					->where($condition, $params)
					->limit($pager['rows'], $pager['start'])
					->orderby('LogisticsRoutesInfo.createTime desc')
					->fetchAll();
	}
	
	public function getInfo($id) {
		return $this->tbl['s']
					->leftJoin('LogisticsRoutes', 'LogisticsRoutes.id = LogisticsRoutesInfo.logisticsRoutesId', 'LogisticsRoutes.from,LogisticsRoutes.to')
					->leftJoin('ScPoint b', 'LogisticsRoutes.from = b.id', 'b.point as fromPoint')
					->leftJoin('ScPoint c', 'LogisticsRoutes.to = c.id', 'c.point as toPoint')
					->where('LogisticsRoutesInfo.id = ?', $id)
					->fetch();
	}
}