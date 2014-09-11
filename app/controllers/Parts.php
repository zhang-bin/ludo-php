<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: Parts.php 375 2013-06-05 02:43:30Z zhangbin $
 */
class Parts extends LdBaseCtrl {
    public static $clusterType = array(1 => 'One Way', 2 => 'Double Way');

	public function __construct() {
		parent::__construct('Parts');
	}
	
	public function index() {
        $partsCategories = Api::getPartsCategories();
		$this->tpl->setFile('parts/parts')->assign('partsCategories', $partsCategories)->display();
	}
	
	public function partsList() {
		$dao = new PartsMaitroxDao();
		$condition = $and = '';
		$params = array();

		if (!empty($_POST['partsCategory'])) {
            $partsCategoryId = intval($_POST['partsCategory']);
			$condition .= 'partsGroupId = ?';
			$params[] = $partsCategoryId;
			$and = ' and ';
		}

        if (!empty($_POST['pn'])) {
            $pn = trim($_POST['pn']);
			$condition .= $and.'PartsMaitrox.pn = ?';
			$params[] = $pn;
			$and = ' and ';
		}

        if (!empty($_POST['name'])) {
			$name = Filter::str($_POST['name']);
			$condition .= $and.'(PartsMaitrox.en like ?)';
			$params[] = '%'.$name.'%';
		}
	
		$cnt = $dao->count($condition, $params);
		$pager = pager(array(
				'base' => 'parts/partsList',
				'cur'  => empty($_GET['id']) ? '1':intval($_GET['id']),
				'cnt'  => $cnt
		));
		if (empty($condition)) {
			$list = $dao->tbl()
					->leftJoin('PartsMaitroxPN2', 'PartsMaitrox.pn = PartsMaitroxPN2.pn', 'PartsMaitroxPN2.*')
					->leftJoin('RepairLevel', 'RepairLevel.id = PartsMaitrox.repairLevel', 'RepairLevel.level')
                    ->leftJoin('PartsGroup', 'PartsGroup.id = PartsMaitrox.partsGroupId', 'PartsGroup.partsGroupName')
					->limit($pager['rows'], $pager['start'])
					->fetchAll();
		} else {
			$list = $dao->tbl()
						->leftJoin('PartsMaitroxPN2', 'PartsMaitrox.pn = PartsMaitroxPN2.pn', 'PartsMaitroxPN2.*')
						->leftJoin('RepairLevel', 'RepairLevel.id = PartsMaitrox.repairLevel', 'RepairLevel.level')
                        ->leftJoin('PartsGroup', 'PartsGroup.id = PartsMaitrox.partsGroupId', 'PartsGroup.partsGroupName')
						->where($condition, $params)
						->limit($pager['rows'], $pager['start'])
						->fetchAll();
		}

        $bomDao = new PhoneBomDao();
        $partsSubstitutionDao = new PartsSubstitutionDao();
        foreach ($list as $k => $v) {
            $models = $bomDao->hasA('Model')->findAllUnique(array('PhoneBom.pn = ?', $v['pn']), 'Model.modeltype');
            if (!empty($models)) {
                $list[$k]['models'] = implode(',', $models);
            }
            $list[$k]['groupNo'] = $partsSubstitutionDao->findColumn('pn1 = ?', $v['pn'], 'groupNo');
        }

		$this->tpl->setFile('parts/list')
				  ->assign('list', $list)
				  ->assign('pager', $pager['html'])
				  ->display();
	}

    public function partsChecking() {
        $cron = new Cron();
        $cron->partsChecking();
        return 1;
    }

	public function partsReport() {
		$dao = new PartsMaitroxDao();
		$parts = $dao->tbl()
				->leftJoin('PartsMaitroxPN2', 'PartsMaitrox.pn = PartsMaitroxPN2.pn', 'PartsMaitroxPN2.*')
				->leftJoin('RepairLevel', 'RepairLevel.id = PartsMaitrox.repairLevel', 'RepairLevel.level')
				->fetchAll();
		
		$abcClassDao = new AbcClassDao();
        $partsSubstitutionDao = new PartsSubstitutionDao();
        $modelWarrantyDao = new ModelWarrantyDao();
        $lastMonth = date('Y-m-01', strtotime('-1 month', strtotime(date('F 1'))));

		foreach ($parts as $k => $part) {
			$parts[$k]['active'] = $part['active'] ? 'Y' : 'N';
			$parts[$k]['purchasable'] = $part['purchasable'] ? 'Y' : 'N';
			$parts[$k]['EOL'] = $part['EOL'] ? 'Y' : 'N';
			$parts[$k]['slowMoving'] = $part['slowMoving'] ? 'Y' : 'N';
			$parts[$k]['obsolete'] = $part['obsolete'] ? 'Y' : 'N';

            $models = self::getModel($part['pn']);
			$parts[$k]['model'] = $models;
			if (!empty($parts[$k]['model'])) $parts[$k]['model'] = implode(',', $parts[$k]['model']);
			
			$parts[$k]['class'] = $abcClassDao->findColumn('vendorId = 0 and pn = ? order by month desc', $part['pn'], 'abcClass');

            $parts[$k]['groupNo'] = $partsSubstitutionDao->findColumn('pn1 = ?', $part['pn'], 'groupNo');
            $parts[$k]['npiLog'] = $part['npiLog'] ? 'Y' : 'N';
		}
		
		$menu = array(
				'pn' => 'New PN',
				'pn2' => 'Order PN',
				'pn3' => 'Old PN',
				'factoryPN' => 'Factory PN',
				'en' => 'Parts Name',
				'partsGroup' => 'Parts Category',
				'model' => 'Model',
                'groupNo' => 'Parts Group',
				'class' => 'ABC Class',
                'npiLog' => 'NPI flag',
				'active' => 'Active',
				'purchasable' => 'Purchasable',
				'slowMoving' => 'SlowMoving',
				'obsolete' => 'Obsolete',
				'EOL' => 'EOL flag',
				'EOLDate' => 'EOL Time',
				'timestamp' => 'Introduction Time',
				'level' => 'Repair Level',
				'moq' => 'MOQ',
				'leadTime' => 'LT'
		);
		$excel = new Excel();
		$filename = $excel->write($menu, $parts);
		downloadLink($filename, 'Lenovo Mobile Phone Parts List('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}

    public function cluster() {
        $dao = new PartsClusterDao();
        $condition = '';
        $params = array();

        if (!empty($_GET['category'])) {
            $condition .= 'partsCategoryId = ?';
            $params[] = intval($_GET['category']);
            $and = ' and ';
        }

        if (!empty($_GET['type'])) {
            $condition .= $and.'type = ?';
            $params[] = intval($_GET['type']);
            $and = ' and ';
        }

        if (!empty($_GET['pn'])) {
            $condition .= $and.'masterPn = ? or slavePn like ?';
            $pn = trim($_GET['pn']);
            $params[] = $pn;
            $params[] = "%{$pn}%";
        }

        $pager = pager(array(
            'base' => 'parts/cluster'.$this->resetGet(),
            'cur'    => isset($_GET['pager']) ? intval($_GET['pager']) : 1,
            'cnt'    => $dao->count($condition, $params, 'cluster'),
        ));

        if (empty($condition)) {
            $clusters = $dao->fetchAll($pager['rows'], $pager['start']);
        } else {
            $clusters = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
        }
        $this->tpl->setFile('parts/cluster')
                  ->assign('clusters', $clusters)
                  ->assign('pager', $pager['html'])
                  ->assign('categories', Api::getPartsCategories())
                  ->display();
    }

    public function clusterAdd() {
        if (empty($_POST)) {
            $this->tpl->setFile('parts/clusterChange')
                      ->assign('partsCategories', Api::getPartsCategories())
                      ->display();
        } else {
            $dao = new PartsClusterDao();
            $add['partsCategoryId'] = intval($_POST['partsCategory']);
            $add['type'] = intval($_POST['type']);
            list($add['partsClusterSequenceId'], $add['cluster']) = $this->_getSeq($add['partsCategoryId'], $add['type']);
            $add['masterPn'] = trim($_POST['masterPn']);
            try {
                $dao->beginTransaction();
                $pns = $_POST['pn'];
                $add['slavePn'] = json_encode($pns);
                $add['id'] = $dao->insert($add);
                $this->_fillDetail($add['id'], $add['type'], $add['masterPn'], $pns);
                $dao->commit();
                return SUCCESS.'|'.url('parts/cluster');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|Add Parts Cluster Failed';
            }
        }
    }

    public function clusterChange() {
        $dao = new PartsClusterDao();
        if (empty($_POST)) {
            $id = trim($_GET['id']);
            $cluster = $dao->hasA('PartsGroup', 'PartsGroup.partsGroupName', 'partsCategoryId')->fetch($id);
            $pns = LdFactory::dao('partsMaitrox')->findAllUnique(array('partsGroupId = ?', $cluster['partsCategoryId']), 'pn');
            $this->tpl->setFile('parts/clusterChange')
                    ->assign('partsCategories', Api::getPartsCategories())
                    ->assign('cluster', $cluster)
                    ->assign('pns', $pns)
                    ->display();
        } else {
            $id = intval($_POST['id']);
            $add['masterPn'] = trim($_POST['masterPn']);
            try {
                $dao->beginTransaction();
                $cluster = $dao->fetch($id);
                $pns = $_POST['pn'];
                $add['slavePn'] = json_encode($pns);
                $dao->update($id, $add);
                $this->_fillDetail($cluster['id'], $cluster['type'], $add['masterPn'], $pns);
                $dao->commit();
                return SUCCESS.'|'.url('parts/cluster');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|Change Parts Cluster Failed';
            }
        }
    }

    public function clusterDel() {
        $id = intval($_GET['id']);
        $dao = new PartsClusterDao();
        $detailDao = new PartsClusterDetailDao();
        $seqDao = new PartsClusterSequenceDao();
        try {
            $dao->beginTransaction();
            $cluster = $dao->fetch($id);
            $dao->delete($id);
            $seqDao->update($cluster['partsClusterSequenceId'], array('occupy' => 0));
            $detailDao->deleteWhere('partsClusterId = ?', $id);
            $dao->commit();
            return SUCCESS.'|'.url('parts/cluster');
        } catch (SqlException $e) {
            $dao->rollback();
            return ALERT.'|Delete Parts Cluster Failed';
        }
    }

    public function clusterReport() {
        $dao = new PartsClusterDao();
        $clusters = $dao->fetchAll();
        $menu = array(
            'cluster' => 'Cluster Number',
            'masterPn' => 'Master PN',
        );
        for ($i = 1; $i <= 7; $i++) {
            $menu['slavePn'.$i] = 'Slave PN'.$i;
        }

        foreach ($clusters as $k => $cluster) {
            $slaves = json_decode($cluster['slavePn'], true);
            for ($i = 0; $i < 7; $i++) {
                $j = $i+1;
                $clusters[$k]['slavePn'.$j] = $slaves[$i];
            }
        }
        $excel = new Excel();
        $filename = $excel->write($menu, $clusters);
        downloadLink($filename, 'Lenovo Mobile Phone Parts Cluster List('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }

	public static function getModel($pn) {
		$phoneBomDao = new PhoneBomDao();
		$modelDao = new ModelDao();
		$models = array_filter($phoneBomDao->findAllUnique(array('pn = ?', $pn), 'modelId'));
		if (empty($models)) return null;
	
		$models = implode(',', $models);
		$modelName = $modelDao->findAllUnique('id in ('.$models.')', 'modeltype');
		$tmp = array();
		foreach ($modelName as $name) {
			$name = strtoupper($name);
			if (!in_array($name, $tmp)) $tmp[] = $name;
		}
		return $tmp;
	}
	
	public static function getPN3($pn) {
		$dao = new PartsMaitroxPN2Dao();
		return $dao->findColumn('pn = ?', $pn, 'pn3');
	}

    private function _fillDetail($id, $type, $masterPn, $pns) {
        $dao = new PartsClusterDetailDao();
        $dao->deleteWhere('partsClusterId = ?', $id);
        if ($type == 2) {//Double Way
            $pns[] = $masterPn;

            $tmp = array();
            foreach ($pns as $pn) {
                $tmp[$pn] = $pn;
            }
            $pns = $tmp;

            foreach ($pns as $pn) {
                if (empty($pn)) continue;
                $tmp = $pns;
                unset($tmp[$pn]);
                $dao->insert(array('masterPn' => $pn, 'slavePn' => json_encode(array_values($tmp)), 'partsClusterId' => $id));
            }
        } else if ($type == 1) {//One Way
            $dao->insert(array('masterPn' => $masterPn, 'slavePn' => json_encode($pns), 'partsClusterId' => $id));
        }
    }

    private function _getSeq($partsCategoryId, $type) {
        $seqDao = new PartsClusterSequenceDao();
        $partsCategoryDao = new PartsGroupDao();

        $partsCategory = $partsCategoryDao->fetchColumn($partsCategoryId, 'partsGroupName');
        //取得最小的未被占用的sequence
        list($id, $min) = $seqDao->find('partsCategoryId = ? and type = ? and occupy = 0', array($partsCategoryId, $type), 'id,min(sequence)', PDO::FETCH_NUM);
        if (empty($min)) {
            //取得最大的sequence
            $max = $seqDao->findColumn('partsCategoryId = ? and type = ?', array($partsCategoryId, $type), 'max(sequence)');
            if (empty($max)) {//还没有该category的数据
                $groupNo = $partsCategory.'001';
                $id = $seqDao->insert(array('partsCategoryId' => $partsCategoryId, 'sequence' => 1, 'type' => $type));
            } else {
                $sequence = $max + 1;
                $groupNo = $partsCategory.str_pad($sequence, 3, 0, STR_PAD_LEFT);
                $id = $seqDao->insert(array('sequence' => $sequence, 'partsCategoryId' => $partsCategoryId, 'type' => $type));
            }
        } else {
            $groupNo = $partsCategory.str_pad($min, 3, 0, STR_PAD_LEFT);
            $seqDao->updateWhere(array('occupy' => 1), 'partsCategoryId = ? and type = ? and sequence = ?', array($partsCategoryId, $type, $min));
        }
        if ($type == 1) $groupNo = 'S-'.$groupNo;
        return array($id, $groupNo);
    }
	
	function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
    }
}