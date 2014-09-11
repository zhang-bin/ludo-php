<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: PhoneBomDao.php 289 2013-05-13 01:45:33Z zhangbin $
 */
class PhoneBomDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PhoneBom');
	}

    public function getSearchCnt($condition, $params) {
        return $this->tbl['s']
                    ->leftJoin('Model', 'Model.id = PhoneBom.modelId')
                    ->where($condition, $params)
                    ->recordsCount();
    }

	public function getSearchList($condition, $params, $pager = array('rows' => 0, 'start' => 0)) {
		return $this->tbl['s']
					->leftJoin('Model', 'Model.id = PhoneBom.modelId', 'Model.name as modelName,Model.pn as modelPN,Model.pn2 as modelPN2,Model.pn3 as modelPN3,Model.pn4 as modelPN4,Model.country')
					->leftJoin('PartsMaitrox', 'PartsMaitrox.id = PhoneBom.partsMaitroxId', 'PartsMaitrox.en as en')
					->leftJoin('PartsMaitroxPN2', 'PartsMaitrox.pn = PartsMaitroxPN2.pn', 'PartsMaitroxPN2.pn as partsPN1, PartsMaitroxPN2.pn2 as partsPN2,PartsMaitroxPN2.pn3 as partsPN3')
					->where($condition, $params)
                    ->limit($pager['rows'], $pager['start'])
                    ->orderby('Model.country,Model.name')
					->fetchAll();	
	}
}