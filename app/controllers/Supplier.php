<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Supplier extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Supplier');
	}
	
	public function index() {
		$this->tpl->setFile('supplier/index')->display();
	}
	
	public function tbl() {
		$condition = 'deleted = 0';
		$params = array();
		
		
		$_SESSION[USER]['page'] = empty($_GET['id']) ? 1 : intval($_GET['id']);
		$dao = new SupplierDao();
		$pager = pager(array(
				'base' => 'supplier/tbl',
				'cur'  => $_SESSION[USER]['page'],
				'cnt'  => $dao->count($condition, $params)
		));
		
		$suppliers = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'createTime desc');
		$this->tpl->setFile('supplier/tbl')
		->assign('suppliers', $suppliers)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function add() {
		if (empty($_POST)) {
			$this->tpl->setFile('supplier/change')->display();
		} else {
			$dao = new SupplierDao();
			$add['supplier'] = trim($_POST['supplier']);
			if (isset($_POST['isDefault'])) {
                $add['isDefault'] = 1;
            } else {
                $add['isDefault'] = 0;
            }
			$add['createTime'] = date(TIME_FORMAT);
			try {
				$dao->beginTransaction();
				if (isset($_POST['isDefault'])) $dao->updateWhere(array('isDefault' => 0), 'isDefault = 1');
				$id = $dao->insert($add);
				
				$add['id'] = $id;
				Logger::log(array(
					'name' => 'add supplier',
					'new' => json_encode($add),
				));
				$dao->commit();
				return SUCCESS.'|'.url('supplier');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SUPPLIER_ADD_FAILED;
			}
		}
	}
	
	public function change() {
		$dao = new SupplierDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$supplier = $dao->fetch($id);
			$this->tpl->setFile('supplier/change')
			->assign('supplier', $supplier)
			->display();
		} else {
			$add = $_POST;
			$id = intval($_POST['id']);
			$add['supplier'] = trim($_POST['supplier']);
			if (isset($_POST['isDefault'])) {
                $add['isDefault'] = 1;
            } else {
                $add['isDefault'] = 0;
            }
			try {
				$dao->beginTransaction();
				if (isset($_POST['isDefault'])) $dao->updateWhere(array('isDefault' => 0), 'isDefault = 1');
				$old = $dao->fetch($id);
				$dao->update($id, $add);
				Logger::log(array(
					'name' => 'change supplier',
					'new' => json_encode($add),
					'old' => json_encode($old),
				));
				$dao->commit();
				return SUCCESS.'|'.url('supplier');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SUPPLIER_CHANGE_FAILED;
			}
		}
	}
	
	public function del() {
		$id = intval($_GET['id']);
		$dao = new SupplierDao();
		try {
			$dao->beginTransaction();
			$old = $dao->fetch($id);
			$dao->update($id, array('deleted' => 1));
			Logger::log(array(
				'name' => 'del supplier',
				'old' => json_encode($old),
			));
			$dao->commit();
			return SUCCESS.'|'.url('supplier');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_SUPPLIER_DEL_FAILED;
		}
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
	}
}