<?php
class Common extends BaseCtrl {
    public function __construct() {
        parent::__construct('Index');
    }

    public function menu() {
        $menu = Load::conf('Menu');
        $id = 1;
        foreach ($menu as $level1Key => $level1Value) {//一级
            foreach ($level1Value['children'] as $level2Key => $level2Value) {//二级
                if (!empty($level2Value['children'])) {//如果有三级
                    foreach ($level2Value['children'] as $level3Key => $level3Value) {
                        if (!UserModel::canMenu($level3Value['href'])) {
                            unset($menu[$level1Key]['children'][$level2Key]['children'][$level3Key]);
                            continue;
                        }
                        $menu[$level1Key]['children'][$level2Key]['children'][$level3Key]['id'] = $id;
                        $id++;
                    }

                    if (empty($menu[$level1Key]['children'][$level2Key]['children'])) {
                        unset($menu[$level1Key]['children'][$level2Key]);
                        continue;
                    }
                } else { //无三级
                    if (!UserModel::canMenu($level2Value['href'])) {
                        unset($menu[$level1Key]['children'][$level2Key]);
                        continue;
                    }


                }
                $menu[$level1Key]['children'][$level2Key]['id'] = $id;
                $id++;
            }

            if (empty($menu[$level1Key]['children'])) {
                unset($menu[$level1Key]);
                continue;
            }
        }
        return $menu;
    }

    public function beforeAction($action) {
        $this->login();
    }
}