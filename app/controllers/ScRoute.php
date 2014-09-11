<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class ScRoute extends LdBaseCtrl {
	public function __construct() {
		parent::__construct ( 'ScRoute' );
	}
	
	public function index() {
		$this->tpl->setFile('scRoute/index')->display();
	}
	
	public function tbl() {
		$condition = 'ScRoute.deleted = 0';
		$params = array ();
		
		if (!empty($_POST['name'])) {
			$condition .= ' and ScRoute.name like ?';
			$params[] = '%'.trim($_POST['name']).'%';
		}
		if (!empty($_POST['poType'])) {
			$condition .= ' and ScRoute.poType = ?';
			$params[] = intval($_POST['poType']);
		}
		
		$dao = new ScRouteDao ();
		$pager = pager ( array (
				'base' => 'scRoute/tbl',
				'cur' => empty ( $_GET ['id'] ) ? 1 : intval ( $_GET ['id'] ),
				'cnt' => $dao->count ( $condition, $params ) 
		) );
		
		$routes = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'country,poType');
		foreach ( $routes as $k => $route ) {
			$routes[$k]['route'] = $this->_getComposition($route['route']);
		}
		
		$this->tpl->setFile('scRoute/tbl')
                ->assign('routes', $routes)
                ->assign('pager', $pager['html'])
                ->display();
	}
	
	public function add() {
		if (empty ( $_POST )) {
			$this->tpl->setFile('scRoute/change')
					->assign('countries', Api::psiPlan())
					->display();
		} else {
			$tatDao = new LogisticsRoutesInfoDao();
			$ltDao = new PurchaseLeadTimeDao();
			$dao = new ScRouteDao();
			$add['name'] = trim($_POST['name']);
			$add['country'] = trim($_POST['country']);
			$add['poType'] = intval($_POST['poType']);
			$add['remark'] = trim($_POST['remark']);
			$route = array();
			if (!empty($_POST['tat'])) {
				foreach ($_POST['tat'] as $tatId) {
					$route['tat'][] = $tatId;
					$add['totalDays'] += $tatDao->fetchColumn($tatId, 'consumeDays');
				}
			}
			if (!empty($_POST['lt'])) {
				foreach ($_POST['lt'] as $ltId) {
					$route['lt'][] = $ltId;
					$add['totalDays'] += $ltDao->fetchColumn($ltId, 'leadTime');
				}
			}
			$add['route'] = json_encode($route);
			$add['createTime'] = gmdate(TIME_FORMAT);
			
			try {
				$dao->beginTransaction();
				$add['id'] = $dao->insert($add);
				$dao->commit();
				return SUCCESS.'|'.url('scRoute');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SC_ROUTE_ADD_FAILED;
			}
		}
	}
	
	public function change() {
		$dao = new ScRouteDao();
		
		if (empty ( $_POST )) {
			$id = intval($_GET['id']);
			$route = $dao->fetch($id);

			$this->tpl->setFile('scRoute/change')
					->assign('route', $route)
					->assign('countries', Api::psiPlan())
					->display();
		} else {
			$tatDao = new LogisticsRoutesInfoDao();
			$ltDao = new PurchaseLeadTimeDao();
			$add['id'] = intval($_POST['id']);
			$add['name'] = trim($_POST['name']);
			$add['country'] = trim($_POST['country']);
			$add['remark'] = trim($_POST['remark']);
			$add['poType'] = intval($_POST['poType']);
			$route = array();
			if (!empty($_POST['tat'])) {
				foreach ($_POST['tat'] as $tatId) {
					$route['tat'][] = $tatId;
					$add['totalDays'] += $tatDao->fetchColumn($tatId, 'consumeDays');
				}
			}
			if (!empty($_POST['lt'])) {
				foreach ($_POST['lt'] as $ltId) {
					$route['lt'][] = $ltId;
					$add['totalDays'] += $ltDao->fetchColumn($ltId, 'leadTime');
				}
			}
			$add['route'] = json_encode($route);
			
			try {
				$dao->beginTransaction();
				$dao->update($add['id'], $add);
				$dao->commit();
				return SUCCESS.'|'.url('scRoute');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_SC_ROUTE_CHANGE_FAILED;
			}
		}
	}
	
	public function del() {
		$dao = new ScRouteDao();
		$id = intval($_GET['id']);
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
			$dao->commit();
			return SUCCESS.'|'.url('scRoute');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_SC_ROUTE_DELETE_FAILED;
		}
	}
	
	
	private function _getComposition($route) {
		if (empty($route)) return null;
		$ltDao = new PurchaseLeadTimeDao();
		$tatDao = new LogisticsRoutesInfoDao();
		$routes = json_decode ( $route, true );
		$composition = '';
		foreach ($routes as $type => $route) {
			foreach ($route as $routeId) {
				switch ($type) {
					case 'lt' :
						$lt = $ltDao->hasA ( 'Supplier', 'Supplier.supplier' )->fetch ($routeId);
						$composition .= $lt ['supplier'] . ':' . $lt ['leadTime'] . ' Days<br />';
						break;
					case 'tat' :
						$tat = $tatDao->getInfo($routeId);
						$composition .= $tat ['fromPoint'] . '->' . $tat ['toPoint'] . ':' . intval($tat['consumeDays']) . ' Days<br />';
						break;
					default :
						break;
				}
			}
		}
		return $composition;
	}
	
	public function getTAT() {
		$dao = new LogisticsRoutesInfoDao();
		$tats = $dao->getList('LogisticsRoutesInfo.deleted = 0');
		$id = intval($_POST['id']);
		if (!empty($id)) {
			$route = LdFactory::dao('scRoute')->fetchColumn($id, 'route');
			$route = json_decode($route, true);
			foreach ($tats as $k => $tat) {
				if (isset($route['tat']) && in_array($tat['id'], $route['tat'])) $tats[$k]['checked'] = true;
			}
		}		
		$this->tpl->setFile('scRoute/tat')->assign('tats', $tats)->display();
	}
	
	public function getLT() {
		$type = intval($_POST['type']);
		$dao = new PurchaseLeadTimeDao();
		$lts = $dao->hasA('Supplier', 'Supplier.supplier')->findAll(array('poType = ?', $type));
		$id = intval($_POST['id']);
		if (!empty($id)) {
			$route = LdFactory::dao('scRoute')->fetchColumn($id, 'route');
			$route = json_decode($route, true);
			foreach ($lts as $k => $lt) {
				if (isset($route['lt']) && in_array($lt['id'], $route['lt'])) $lts[$k]['checked'] = true;
			}		
		}
		$this->tpl->setFile('scRoute/lt')->assign('lts', $lts)->display();
	}

    public function reset() {
        $dao = new ScRouteDao();
        $logisticsRoutesInfoDao = new LogisticsRoutesInfoDao();
        $routes = $dao->findAll('deleted = 0');
        foreach ($routes as $route) {
            $route['route'] = json_decode($route['route'], true);
            if (!empty($route['route']['tat'])) {
                $day = 0;
                $newTat = array();
                foreach ($route['route']['tat'] as $tat) {
                    $tatInfo = $logisticsRoutesInfoDao->fetch($tat);
                    if ($tatInfo['deleted'] == '1') continue;
                    $newTat[] = $tat;
                    $day += $tatInfo['consumeDays'];
                }
            }
            $dao->update($route['id'], array('totalDays' => $day, 'route' => json_encode(array('tat' => $newTat))));
        }
    }
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}