<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PartsSubstitution extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('PartsSubstitution');
	}
	
	public function index() {
		$this->substitution();
	}
	
	public function substitution() {
		$dao = new PartsSubstitutionDao();
		$condition = '';
		$params = array();
		
		if (!empty($_GET['pn'])) {
			$pn = trim($_GET['pn']);
			$condition .= 'pn1 = ?';
			$params[] = $pn;
		}
		$pager = pager(array(
				'base' => 'partsSubstitution/substitution',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		if (empty($condition)) {
			$list = $dao->fetchAll($pager['rows'], $pager['start']);
		} else {
			$list = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);	
		}
		$this->tpl->setFile('partsSubstitution/substitution')
				->assign('list', $list)
				->assign('pager', $pager['html'])
				->assign('pn', $pn)
				->display();
	}

	public function import() {
		if (empty($_POST)) {
			$dao = new ModelDao();
			$modelTypes = $dao->fetchAllUnique('modeltype');
			$this->tpl->setFile('partsSubstitution/import')
					->assign('modelTypes', $modelTypes)
					->display();
		} else {
			$dao = new PartsSubstitutionDao();
			
			try {
				$dao->beginTransaction();
				foreach ($_POST['model'] as $k => $model) {
					if (empty($model)) continue;
					for ($i = 2; $i <= 5; $i++) {
						if (empty($_POST['pn'.$i][$k])) break;
					}
					if ($i <= 2) continue;
					$i--;
					
					$pn1 = trim($_POST['pn1'][$k]);
					$groupNo = $this->_getSeq($pn1);
					$substitution = $dao->find('model = ? and pn1 = ?', array($model, $pn1));
					$add['model'] = $model;
					$add['remark'] = trim($_POST['remark'][$k]);
					$add['groupNo'] = $groupNo;
					
					if ($_POST['replaceType'][$k] == '2') {
						if (empty($substitution)) {//完全新增加的PN
							$pns = array();
							for ($j = 1; $j <= $i; $j++) {
								$pns['pn'.$j] = trim($_POST['pn'.$j][$k]);
							}
							$tmp = array_merge($add, $pns);
							$dao->insert($tmp);
						} else {
							for ($m = 1; $m <= 10; $m++) {
								if (empty($substitution['pn'.$m])) break;
							}
							
							for ($j = 2; $j <= $i; $j++) {
								$n = $j+$m-2;
								$pns['pn'.$n] = trim($_POST['pn'.$j][$k]);
							}
							$dao->update($substitution['id'], $pns);
						}
					} else {
						if (empty($substitution)) {//完全新增加的PN 
							$pns = array();
							for ($j = 1; $j <= $i; $j++) {
								$pns[] = trim($_POST['pn'.$j][$k]);
							}
							
							for ($j = 1; $j <= $i; $j++) {
								$pn = array();
								$n = 1;
								foreach ($pns as $v) {
									$pn['pn'.$n] = $v;
									$n++;
								}
								$tmp = array_merge($add, $pn);
								$dao->insert($tmp);
								array_push($pns, array_shift($pns));
							}
						} else {//缺少一部分PN
							$pns = array();
							for ($m = 1; $m <= 10; $m++) {
								if (empty($substitution['pn'.$m])) {
									break;
								} else {
									$pns[] = $substitution['pn'.$m];
								}
							}
							
							$lack = array();
							$exist = $pns;
							
							for ($j = 1; $j <= $i; $j++) {
								$pn = trim($_POST['pn'.$j][$k]);
								if (!in_array($pn, $pns)) {
									$lack[] = $pn;		
								}
							}
							
							foreach ($exist as $v) {
								$id = $dao->findColumn('model = ? and pn1 = ?', array($model, $v), 'id');
								$tmp = array();
								$n = $m;
								foreach ($lack as $vv) {
									$tmp['pn'.$n] = $vv;
									$n++;
								}
								$dao->update($id, $tmp);
							}
							$cnt = count($lack);
							for ($k = 1; $k <= $cnt; $k++) {
								$n = 1;
								$tmp = array();
								foreach ($lack as $v) {
									$tmp['pn'.$n] = $v;
									$n++;
								}
								foreach ($exist as $v) {
									$tmp['pn'.$n] = $v;
									$n++;
								}
								$tmp = array_merge($tmp, $add);
								$dao->insert($tmp);
								array_push($lack, array_shift($lack));
							}
						}
					}
				}
				Logger::log(array(
					'name' => 'add parts substitution',
					'new'  => print_r($_POST, true),
				));
				$dao->commit();
				return SUCCESS.'|'.url('PartsSubstitution');				
			} catch (PDOException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_SUBSTITUTION_IMPORT_FAILED;
			}
		}
	}
	
	public function report() {
		$dao = new PartsSubstitutionDao();
		$parts = $dao->tbl()->leftJoin('PartsMaitroxPN2', 'PartsSubstitution.pn1 = PartsMaitroxPN2.pn', 'PartsMaitroxPN2.en')->fetchAll();
		
		$menu['model'] = 'model';
		for ($i = 1; $i <= 10; $i++) {
			$menu['pn'.$i] = 'pn'.$i;
		}
		$menu['en'] = 'description';
		$menu['remark'] = 'remark';
		$menu['groupNo'] = 'group';
		
		$excel = new Excel();
		$filename = $excel->write($menu, $parts);
		downloadLink($filename, 'Lenovo Mobile Phone Parts Substitution List('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	private function _getSeq($pn) {
		$dao = new PartsSubstitutionDao();
		$seqDao = new PartsGroupSequenceDao();
		$partsDao = new PartsMaitroxDao();
		
		$substitution = $dao->find('pn1 = ?', $pn);
		if (empty($substitution)) {
			$group = $partsDao->findColumn('pn = ?', $pn, 'partsGroup');
			if (empty($group)) $group = 'None';
			list($exist, list($seq)) = $seqDao->existsRow('partsGroup = ?', $group, 'sequence');
			if ($exist) {
				$groupNo = $group.($seq+1);
				$seqDao->updateWhere(array('sequence' => strval($seq+1)), 'partsGroup = ?', $group);
			} else {
				$groupNo = $group.'100';
				$seqDao->insert(array('partsGroup' => $group, 'sequence' => 100));
			}
		} else {
			$groupNo = $substitution['groupNo'];
		}
		return $groupNo;
	}
	
	function getSubstitutionParts() {
		$model = trim($_GET['model']);
		$originalPn = trim($_GET['pn']);
		$model = explode('_', $model);
		$model = $model[0];
		
		$dao = new PartsSubstitutionDao();
		$condition = 'pn1 = ?';
		$params[] = $originalPn;
		if (!empty($model)) {
			$condition .= ' and model = ?';
			$params[] = $model;
		}
		
		$pns = $dao->findAll(array($condition, $params));
		$tmp = array($originalPn => $originalPn);
		foreach ($pns as $pn) {
			for ($i = 1; $i <= 10; $i++) {
				if (empty($pn['pn'.$i])) continue;
				if ($pn['pn'.$i] == $originalPn) continue;
				$tmp[$pn['pn'.$i]] = $pn['pn'.$i];
			}
		}
		$pns = $tmp;
		
		$dao = new PartsMaitroxPN2Dao();
		$tmp = array();
		foreach ($pns as $pn) {
			$tmp[$pn] = $dao->find('pn = ?', $pn, 'pn2,pn3,en');
		}
		
		$pns = $tmp;
		$tmp = array();
		foreach ($pns as $pn => $v) {
			$label = $pn;
			if (!empty($v['pn2'])) $label .= '--'.$v['pn2'];
			if (!empty($v['pn3'])) $label .= '--'.$v['pn3'];
			$label .= '--'.$v['en'];
			$tmp[] = array(
					'value' => $pn,
					'label' => $label,
					'en' => $v['en']
			);
		}
		return json_encode($tmp);
	}
	
	function beforeAction($action) {
		if ($action == 'import') return;
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}