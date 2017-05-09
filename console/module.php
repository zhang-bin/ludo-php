<?php

/**
 * 这是基于table生成相应的controller,dao,view文件
 */
class Module
{
    private $upper;
    private $lower;
    private $fields = array();
    private $hasDeleted = false;
    private $daoName;
    private $moduleDescr;

    /**
     * 生成模块
     *
     * @param $module string
     * @param $descr string
     * @return bool
     */
    public function install($module, $descr)
    {
        $this->upper = ucfirst($module);
        $this->lower = lcfirst($module);
        $this->daoName = $this->upper . 'Dao';
        $this->moduleDescr = $descr;

        $sql = 'show full columns from ' . $this->upper;
        $dbh = \Ludo\Support\ServiceProvider::getInstance()->getDBHandler();
        try {
            $this->fields = $dbh->select($sql);
        } catch (QueryException $e) {
            return $e->getMessage();
        }

        foreach ($this->fields as &$field) {
            //分析字段描述，去掉括号和括号里的内容
            $field['Comment'] = preg_replace('/([^(]*)(\(.*\))/isU', '$1', $field['Comment']);

            //分析字段类型
            if (preg_match('/^((?:var)?char)\((\d+)\)/', $field['Type'], $matches)) {
                $row['Type'] = $matches[1];
            } elseif (preg_match('/^decimal\((\d+),(\d+)\)/', $field['Type'], $matches)) {
                $row['Type'] = 'decimal';
            } elseif (preg_match('/^float\((\d+),(\d+)\)/', $field['Type'], $matches)) {
                $row['Type'] = 'float';
            } elseif (preg_match('/^((?:big|medium|small|tiny)?int)\((\d+)\)/', $field['Type'], $matches)) {
                $row['Type'] = $matches[1];
            }

            if ($field['Field'] == 'deleted') {
                $this->hasDeleted = true;
                break;
            }
        }
        $this->controller();
        $this->dao();
        $this->tpl();
        return true;
    }

    /**
     * 删除模块
     *
     * @param $module string
     * @return bool
     */
    public function uninstall($module)
    {
        $this->upper = ucfirst($module);
        $this->lower = lcfirst($module);
        $this->daoName = $this->upper . 'Dao';

        $controllerFile = LD_CTRL_PATH . DIRECTORY_SEPARATOR . $this->upper . '.php';
        file_exists($controllerFile) && unlink($controllerFile);

        $daoFile = LD_DAO_PATH . DIRECTORY_SEPARATOR . $this->upper . 'Dao.php';
        file_exists($daoFile) && unlink($daoFile);

        $tplDir = TPL_ROOT . DIRECTORY_SEPARATOR . $this->lower;
        exec("rm -fr ".$tplDir);
        return true;
    }

    /**
     * 控制器
     *
     * @return bool
     */
    private function controller()
    {
        $controllerFile = LD_CTRL_PATH . DIRECTORY_SEPARATOR . $this->upper . '.php';
        if (file_exists($controllerFile)) return true;
        $condition = $this->hasDeleted ? '$condition = \'' . $this->upper . '.deleted = 0\';' : '$condition = \'\';';

        //id不需要赋值,deleted必须设置为0,其他字段根据前台输入的内容来进行赋值
        $ignore = array('id', 'deleted');
        $update = $add = '';
        if ($this->hasDeleted) {
            $update .= <<<'EOF'
            $add['deleted'] = 0;
EOF;
            $update .= NEW_LINE;
        }
        $add = $update;
        foreach ($this->fields as $field) {
            $column = $field['Field'];
            if (in_array($column, $ignore)) continue;
            switch ($column) {
                case 'createTime':
                    $add .= <<<'EOF'
            $add['createTime'] = date(TIME_FORMAT);
EOF;
                    break;
                case 'createDate':
                    $add .= <<<'EOF'
            $add['createDate'] = date(DATE_FORMAT);
EOF;
                    break;
                default:
                    $update .= <<<EOF
            \$add['$column'] = trim(\$_POST['$column']);
EOF;
                    $add .= <<<EOF
            \$add['$column'] = trim(\$_POST['$column']);
EOF;
                    break;
            }
            $update .= NEW_LINE;
            $add .= NEW_LINE;
        }


        $controller = <<<'EOF'
<?php
Class ludo_upper extends BaseCtrl {
    public function __construct() {
        parent::__construct('ludo_upper');
    }

    public function index() {
        %s
        $params = array();
        $dao = new module_dao_name();
        $pager = pager(array(
            'base' => 'ludo_lower/index'.$this->resetGet(),
            'cur'  => isset($_GET['pager']) ? intval($_GET['pager']) : 1,
            'cnt'  => $dao->count($condition, $params)
        ));

        $ludo_lowers = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
        $this->tpl->setFile('ludo_lower/index')
                ->assign('ludo_lowers', $ludo_lowers)
                ->assign('pager', $pager['html'])
                ->display();
    }

    public function add() {
		if (empty($_POST)) {
			$this->tpl->setFile('ludo_lower/change')
					->display();
		} else {
            $dao = new module_dao_name();
%s
            try {
				$dao->beginTransaction();
				$add['id'] = $dao->insert($add);
				Log::log(array(
                    'name' => 'add ludo_lower',
                    'new' => json_encode($add)
                ));
				$dao->commit();
				return array(STATUS => SUCCESS, URL => url('ludo_lower'));
			} catch (QueryException $e) {
				$dao->rollback();
				return array(STATUS => ALERT, MSG => OPERATION_FAILED);
			}
		}
    }

    public function change() {
		$dao = new module_dao_name();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$ludo_lower = $dao->fetch($id);
			$this->tpl->setFile('ludo_lower/change')
					->assign('ludo_lower', $ludo_lower)
					->display();
		} else {
		    $id = intval($_POST['id']);
%s

            try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				$dao->update($id, $add);
				Log::log(array(
                    'name' => 'update ludo_lower',
                    'new' => json_encode($add),
                    'old' => json_encode($old)
                ));
				$dao->commit();
				return array(STATUS => SUCCESS, URL => url('ludo_lower'));
			} catch (QueryException $e) {
				$dao->rollback();
				return array(STATUS => ALERT, MSG => OPERATION_FAILED);
			}
		}
    }

    public function view() {
		$id = intval($_GET['id']);
		$dao = new module_dao_name();

		$ludo_lower = $dao->fetch($id);
		$this->tpl->setFile('ludo_lower/view')
				->assign('ludo_lower', $ludo_lower)
				->display();
	}


EOF;
        if ($this->hasDeleted) {
            $controller .= <<<'EOF'
    public function del() {
		$id = intval($_GET['id']);
		$dao = new module_dao_name();
		$old = $dao->fetch($id);
		$add['deleted'] = 1;
		try {
			$dao->beginTransaction();
			$dao->update($id, $add);
			Log::log(array(
                'name' => 'delete ludo_lower',
                'old' => json_encode($old)
            ));
			$dao->commit();
			return array(STATUS => SUCCESS, URL => url('ludo_lower'));
		} catch (QueryException $e) {
			$dao->rollback();
			return array(STATUS => ALERT, MSG => OPERATION_FAILED);
		}
	}
EOF;

        } else {
            $controller .= <<<'EOF'
    public function del() {
		$id = intval($_GET['id']);
		$dao = new module_dao_name();
		$old = $dao->fetch($id);
		try {
			$dao->beginTransaction();
			$dao->delete($id);
			Log::log(array(
                'name' => 'delete ludo_lower',
                'old' => json_encode($old)
            ));
			$dao->commit();
			return array(STATUS => SUCCESS, URL => url('ludo_lower'));
		} catch (QueryException $e) {
			$dao->rollback();
			return array(STATUS => ALERT, MSG => OPERATION_FAILED);
		}
	}
EOF;
        }
        $controller .= <<<'EOF'

}
EOF;

        $controller = str_replace(
            array('ludo_upper', 'ludo_lower', 'module_dao_name'),
            array($this->upper, $this->lower, $this->daoName),
            $controller);
        $controller = sprintf($controller, $condition, $add, $update);
        file_put_contents($controllerFile, $controller);
        return true;
    }

    /**
     * dao文件
     *
     * @return bool
     */
    private function dao()
    {
        $daoFile = LD_DAO_PATH . DIRECTORY_SEPARATOR . $this->upper . 'Dao.php';
        if (file_exists($daoFile)) return true;
        $dao = <<<'EOF'
<?php
Class ludo_upperDao extends BaseDao {
    public function __construct() {
        parent::__construct('ludo_upper');
    }
}
EOF;
        $dao = str_replace(array('ludo_upper'), array($this->upper),  $dao);
        file_put_contents($daoFile, $dao);
        return true;
    }

    /**
     * tpl文件
     */
    private function tpl()
    {
        $tplDir = TPL_ROOT . DIRECTORY_SEPARATOR . $this->lower;
        !is_dir($tplDir) && mkdir($tplDir);
        $this->index($tplDir);
        $this->change($tplDir);
        $this->view($tplDir);
    }

    /**
     * tpl列表页面
     *
     * @param $dir string
     * @return bool
     */
    private function index($dir)
    {
        $tplIndexFile = $dir . DIRECTORY_SEPARATOR . 'index.php';
        if (file_exists($tplIndexFile)) return true;

        $index = <<<'EOF'
<?php
$gTitle = 'ludo_module_descr列表';
$gToolbox = '<a href="'.url('ludo_lower/add').'" class="add">添加ludo_module_descr</a>';
include tpl('header');
?>
<form class="form form-inline" action="<?=url('ludo_lower/index')?>">
    <input type="submit" class="btn btn-small btn-primary" value="<?=SEARCH?>" />
</form>
<table class="table table-hover table-bordered">
    <thead>
    	<tr>

EOF;
        $ignore = array('id', 'deleted', 'createDate');
        foreach ($this->fields as $field) {
            if (in_array($field['Field'], $ignore)) continue;
            $index .= <<<EOF
            <th>{$field['Comment']}</th>

EOF;
        }
        $index .= <<<'EOF'
            <th><?=ACTION?></th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($ludo_lowers)) {foreach($ludo_lowers as $ludo_lower) {?>
    <tr>

EOF;
        foreach ($this->fields as $field) {
            if (in_array($field['Field'], $ignore)) continue;
            $index .= <<<EOF

        <td><?=\$ludo_lower['{$field['Field']}']?></td>
EOF;
        }

    $index .= <<<'EOF'

        <td>
            <div class="btn-group">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?=ACTION?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li>
                        <a href="<?=url('ludo_lower/change/'.$ludo_lower['id'])?>">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <?=MODIFY?>
                        </a>
                    </li>
                    <li>
                        <a name="del" data-title="删除ludo_module_descr" data-body="<?=CONFIRM_DELETE?>" href="<?=url('ludo_lower/del/'.$ludo_lower['id'])?>">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            <?=DELETE?>
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    <?php }}?>
    <?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
    </tbody>
</table>
<?php include tpl('footer');?>
EOF;


        $index = str_replace(
            array('ludo_lower', 'ludo_module_descr'),
            array($this->lower, $this->moduleDescr),
            $index);
        file_put_contents($tplIndexFile, $index);
        return true;
    }

    /**
     * tpl添加/修改页面
     *
     * @param $dir string
     * @return bool
     */
    private function change($dir)
    {
        $tplChangeFile = $dir . DIRECTORY_SEPARATOR . 'change.php';
        if (file_exists($tplChangeFile)) return true;

        $change = <<<'EOF'
<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? '修改ludo_module_descr' : '添加ludo_module_descr';
include tpl('header');
?>
<form method="post" class="form-horizontal form" action="<?=$change ? url('ludo_lower/change') : url('ludo_lower/add')?>">

EOF;
        $ignore = array('id', 'deleted', 'createDate', 'createTime');
        foreach ($this->fields as $field) {
            if (in_array($field['Field'], $ignore)) continue;
            switch ($field['Type']) {
                case 'tinytext':
                case 'mediumtext':
                case 'text':
                case 'longtext':
                  $change .= <<<EOF
    <div class="form-group">
        <label for="{$field['Field']}" class="col-sm-2 control-label">{$field['Comment']}</label>
        <div class="col-sm-4">
            <textarea class="form-control" style="width:400px;height:100px;" name="{$field['Field']}" id="{$field['Field']}"><?=\$ludo_lower['{$field['Field']}']?></textarea>
        </div>
    </div>

EOF;
                    break;
                default:
                    $change .= <<<EOF
    <div class="form-group">
        <label for="{$field['Field']}" class="col-sm-2 control-label">{$field['Comment']}</label>
        <div class="col-sm-4">
            <input type="text" id="{$field['Field']}" class="form-control" name="{$field['Field']}" value="<?=\$ludo_lower['{$field['Field']}']?>" />
        </div>
    </div>

EOF;
                    break;
            }
        }

        $change .= <<<'EOF'
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <input type="hidden" id="id" name="id" value="<?=$ludo_lower['id']?>"/>
            <input id="submitBtn" type="submit" value="<?=SUBMIT?>" class="btn btn-success" />
            <input type="button" onclick="javascript:history.back();" value="<?=CANCEL?>" class="btn btn-default" />
        </div>
    </div>
</form>
<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>
EOF;
        $change = str_replace(
            array('ludo_lower', 'ludo_module_descr'),
            array($this->lower, $this->moduleDescr),
            $change);
        file_put_contents($tplChangeFile, $change);
        return true;
    }

    /**
     * tpl查看页面
     *
     * @param $dir string
     * @return bool
     */
    private function view($dir)
    {
        $tplViewFile = $dir . DIRECTORY_SEPARATOR . 'view.php';
        if (file_exists($tplViewFile)) return true;

        $view = <<<'EOF'
<?php
$gTitle = 'ludo_module_descr信息';
include tpl('header');
?>
<div class="form-horizontal">

EOF;
        $ignore = array('id', 'deleted', 'createDate', 'createTime');
        foreach ($this->fields as $field) {
            if (in_array($field['Field'], $ignore)) continue;
            $view .= <<<EOF
    <div class="form-group">
        <label for="{$field['Field']}" class="col-sm-2 control-label">{$field['Comment']}</label>
        <div class="col-sm-10">
            <p class="form-control-static"><?=\$ludo_lower['{$field['Field']}']?></p>
        </div>
    </div>

EOF;
        }

        $view .= <<<'EOF'
</div>
<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>
EOF;
        $view = str_replace(
            array('ludo_lower', 'ludo_module_descr'),
            array($this->lower, $this->moduleDescr),
            $view);
        file_put_contents($tplViewFile, $view);
        return true;
    }
}
