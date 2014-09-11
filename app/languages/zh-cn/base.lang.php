<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    
 */

/* Global */
define('SITE_TITLE',		'Lenovo Mobile Service System');
define('LG_ADD', 'Add');
define('LG_DELETE', 'Delete');
define('LG_MODIFY', 'Modify');
define('LG_UPDATE', 'Update');
define('LG_DUPLICATE', 'Duplicate');
define('LG_REMOVE', 'Remove');
define('LG_YES', 'Yes');
define('LG_NO', 'No');
define('LG_INDEX', 'Index');
define('LG_HOME', 'Home');
define('LG_FIRST_PAGE', 'First');
define('LG_PREVIOUS_PAGE', 'Previous');
define('LG_NEXT_PAGE', 'Next');
define('LG_LAST_PAGE', 'Last');
define('LG_SAVE', 'Save');
define('LG_CANCEL', 'Cancel');
define('LG_STATUS', '状态');
define('LG_CURR_LOCATION', 'Location');
define('LG_OP', 'Operation');
define('LG_REUPLOAD', 'Reupload');
define('LG_RELLY_DELETE', 'Are you sure to delete this item? \n(Note this operation cannot reverse)');
define('LG_WELCOME', 'Welcome');
define('LG_LOGIN', 'Login');
define('LG_LOGOUT', 'Logout');
define('LG_CANNOT_BE_EMPTY', 'cannot be empty!');
define('LG_USER_OR_PW_WRONG', 'Error: Wrong username or password');

/* Error Message */
define('LG_CONNECT_DB_FAILED', 'There may be some problems when connect to the DB Server.');
define('LG_QUERY_FAILED', 'Query database failed, Pls check your SQL clause.');
define('LG_EXECUTE_FAILED', 'Failed to update the database record.');
define('LG_DBTABLE_NOT_EXIST', 'The database table doesn not exist');
define('LG_FIELD_NOT_EXIST', 'Field seems doesn not exist in the table');
define('LG_DSN_NOT_DEFINED', 'We didn\'t find the DSN which leads to connect to DB failed');

define('LG_CONFIG_FILE_NOT_EXIST', 'We didn\'t find the config file: config.inc'.php);
define('LG_CTRL_NOT_FOUND', 'Sorry, the module [<strong>%s</strong>] you requested does not exist');
define('LG_ACTION_NOT_FOUND', 'Sorry, the action [<strong>%s</strong>] you requested does not exist');
define('LG_ACTION_CANNOT_BE_STATIC', 'Sorry, access denied for static action [<strong>%s</strong>]');
define('LG_TEMPLATE_FILE_NOT_FOUND', 'Sorry, Template file [<strong>%s</strong>] does not exist');
define('LG_MISSING_ID', 'Sorry, the page you request does not exist or have been removed.');


/*--[menu]-----*/
//基础信息
define('LG_MENU_STANDARD_INFO', '基础信息');
define('LG_TIMEZONE', '时区');
 
define('LG_SUBMENU_SERVICE_BOM', 'BOM信息');
define('LG_THIRDMENU_PHONE_BOM', '手机BOM');
define('LG_THIRDMENU_MODEL', '机型');
define('LG_THIRDMENU_FAILURECODE', 'FailureCode');
define('LG_THIRDMENU_L3_REPIAR', 'L3维修');

define('LG_SUBMENU_WARRANTY_INFO', '保修信息');
define('LG_THIRDMENU_PHONE_WARRANTY', '手机保修');

define('LG_SUBMENU_VENDOR_INFO', '服务商信息');
define('LG_THIRDMENU_VENDOR', '服务商');
define('LG_THIRDMENU_STATION', '服务站');
define('LG_THIRDMENU_CONTACT', '联系人');

define('LG_SUBMENU_METERIAL_INFO', '物料信息');
define('LG_THIRDMENU_MAITROX_PARTS', '物料');
define('LG_THIRDMENU_VENDOR_PARTS', '物料价格');

define('LG_SUBMENU_AUTH', '权限设置');
define('LG_THIRDMENU_USER_AUTH', '用户权限');

define('LG_SUBMENU_SYSTEM_LOG', '系统记录');
//流程
define('LG_MENU_PROCESS', '流程');
define('LG_THIRDMENU_ENTITLEMENTS', '保修查询');
define('LG_THIRDMENU_BOM', 'BOM查询');
define('LG_SUBMENU_QUERY', '查询');
define('LG_SUBMENU_SERVICE_MANAGE', '维修管理');
define('LG_THIRDMENU_SERVICE_ORDER', '维修单');
define('LG_THIRDMENU_SERVICE_ORDER_PICKUP', '客户取机');
define('LG_THIRDMENU_SERVICE_ORDER_APPLY', '维修站申请备件');
define('LG_THIRDMENU_SERVICE_ORDER_APPLY_UPDATE', '更新备件申请');
define('LG_THIRDMENU_SERVICE_ORDER_TURN', '转上级维修站维修');
define('LG_THIRDMENU_SERVICE_ORDER_TURN_UPDATE', '更新转站维修');

define('LG_SUBMENU_DOA_MANAGE', 'DOA');
define('LG_THIRDMENU_DOA_ORDER', 'DOA单据');

define('LG_SUBMENU_LOGISTICS_MANAGE', '物流管理');
define('LG_THIRDMENU_LOGISTICS_ORDER', '物流单');

define('LG_SUBMENU_SPARE_MANAGE', '备件运作');
define('LG_THIRDMENU_WAREHOUSE', '仓库');
define('LG_THIRDMENU_INVENTROY', '库存清单');
define('LG_THIRDMENU_REPLENISH_ORDER', '调拨订单');
define('LG_THIRDMENU_SHIPPING_ORDER', '调拨运输单');
define('LG_THIRDMENU_RECEIVED_ORDER', '收货');

define('LG_THIRDMENU_RMA_ORDER', 'RMA订单');
define('LG_THIRDMENU_SCRAP_MANAGEMENT', '废料管理');
define('LG_SUBMENU_L3_REPAIR', '高级别维修');

define('LG_SUBMENU_CUSTOMER_SERVICE_MANAGE', '客服管理');
define('LG_THIRDMENU_CUSTOMER_COMPLAINS', '电话热线记录');

define('LG_SUBMENU_FINANCE_MANAGE', '财务管理');
define('LG_THIRDMENU_SERVICE_ORDER_REPORT', '维修单报告');
define('LG_THIRDMENU_CUSTOMER_COMPLAINS_REPORT', '电话热线报告');
define('LG_THIRDMENU_DOA_ORDER_REPORT', 'DOA维修报告');

define('LG_THIRDMENU_PARTS', '备件月报');
define('LG_THIRDMENU_INVOICE', '维修月报');
define('LG_THIRDMENU_PAYMENT', '付款审批');
define('LG_SUBMENU_PAYMENT_INFO', '付款信息');
define('LG_THIRDMENU_PAYMENT_COMPLAIN', '付款列表');
define('LG_PAYMENT_ADD', '增加新付款申请');

//系统备份
define('LG_MENU_SYSTEM_BACKUP', '系统管理');
define('LG_SUBMENU_USERS', '用户管理');
define('LG_THIRDMENU_ADDMODIFY_USER', '增加调整User');
define('LG_SUBMENU_LOGS', '日志');
define('LG_THIRDMENU_VIEW_LOGS', '查看日志');
define('LG_MENU_CRM', 'CRM');
define('LG_THIRDMENU_NEW_CASE_ENTRY', '新客户');


//公用常用
define('LG_SELECT_CHOOSE',  '请选择...');
define('LG_BTN_IMPORT',    '导入');
define('LG_BTN_ADD',    '添加');
define('LG_OPERATION',  '修改/删除');
define('LG_BTN_EDIT', '修改');
define('LG_BTN_DEL', '删除');
define('LG_BTN_SEARCH', '搜索');
define('LG_BTN_SAVE', '保存');
define('LG_BTN_CLOSE', '关闭');
define('LG_BTN_CANCEL', '取消');
define('LG_POSITION', '当前位置');
define('LG_BTN_CLOSE', 'Close');
?>