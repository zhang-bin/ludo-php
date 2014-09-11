<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class ScPoint extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('ScPoint');
	}
	
	public function index() {
		$this->tpl->setFile('scPoint/index')->display();
	}
	
	public function tbl() {
		$condition = 'ScPoint.deleted = 0';
		$params = array();
		
		if (!empty($_POST['name'])) {
			$condition .= ' and point like ?';
			$params[] = '%'.trim($_POST['name']).'%';
		}
		
		$dao = new ScPointDao();
		$pager = pager(array(
				'base' => 'ScPoint/tbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params)
		));
		
		$points = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'createTime desc');
		$this->tpl->setFile('scPoint/tbl')
		->assign('points', $points)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function add() {
		if (empty($_POST)) {
			$this->tpl->setFile('scPoint/change')->display();
		} else {
			$dao = new ScPointDao();
			try {
				$dao->beginTransaction();
				$add['point'] = trim($_POST['point']);
				$add['createTime'] = gmdate(TIME_FORMAT);
				$add['id'] = $dao->insert($add);
				Logger::log(array(
					'name' => 'add sc point',
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('scPoint');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SC_POINT_ADD_FAILED;
			} 
		}
	}
	
	public function change() {
		$dao = new ScPointDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$point = $dao->fetch($id);
			$this->tpl->setFile('scPoint/change')
					->assign('point', $point)
					->display();
		} else {
			$id = intval($_POST['id']);
			try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				$add['point'] = trim($_POST['point']);
				$add['id'] = $id;
				$dao->update($id, $add);
				Logger::log(array(
					'name' => 'change sc point',
					'new' => print_r($add, true),
					'old' => print_r($old, true),
				));
				$dao->commit();
				return SUCCESS.'|'.url('scPoint');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SC_POINT_CHANGE_FAILED;
			}
		}
	}
	
	public function del() {
		$dao = new ScPointDao();
		$id = intval($_GET['id']);
		try {
			$dao->beginTransaction();
			$old = $dao->fetch($id);
			$dao->update($id, array('deleted' => 1));
			Logger::log(array(
				'name' => 'delete sc point',
				'old' => print_r($old, true),
			));
			$dao->commit();
			return SUCCESS.'|'.url('scPoint');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_SC_POINT_DELETE_FAILED;
		}
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}