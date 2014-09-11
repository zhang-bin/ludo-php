<?php
/**
 * Ludo BillGo Platform
 *
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
//数据库字段名称
define('LG_SERVICEMANAGE_ID', 'id');
define('LG_SERVICEMANAGE_USERID', '用户Id');
define('LG_SERVICEMANAGE_USERNAME', '用户');

define('LG_SERVICEMANAGE_PN', 'MTM');
define('LG_SERVICEMANAGE_MODEL', '手机机型');
define('LG_SERVICEMANAGE_COLOR', '手机颜色');
define('LG_SERVICEMANAGE_IMEI', 'IMEI号');
define('LG_SERVICEMANAGE_RECEIVEDTIME', '接收时间');
define('LG_SERVICEMANAGE_FAILUREID', '故障代码');
define('LG_SERVICEMANAGE_TESTRESULT', '测试结果');
define('LG_SERVICEMANAGE_REPAIREDTIME', '维修时间');
define('LG_SERVICEMANAGE_CHARGE', '是否收费');
define('LG_SERVICEMANAGE_WAREHOUSEID', '仓库id');
define('LG_SERVICEMANAGE_VENDORID', 'SP');
define('LG_SERVICEMANAGE_CHARGECURRENCY', '收费币种');
define('LG_SERVICEMANAGE_CHARGEAMOUNT', '收费金额');
define('LG_SERVICEMANAGE_PURCHASE_DATE', '购买日期');
define('LG_SERVICEMANAGE_KEY_ACCOUNT_NAME', '大客户名称');
define('LG_SERVICEMANAGE_CUSTOMER_REPORT', '客户描述问题');

define('LG_SERVICEMANAGE_STATUS', '维修单状态');
define('LG_SERVICEMANAGE_RECOVER_METHOD', '修复方法');
define('LG_SERVICEMANAGE_WAREHOUSEID', '仓库');
define('LG_SERVICEMANAGE_SWAP_PART1', '更换备件 1');
define('LG_SERVICEMANAGE_SWAP_PART2', '更换备件 2');
define('LG_SERVICEMANAGE_SWAP_PART3', '更换备件 3');
define('LG_SERVICEMANAGE_DEFECTIVE_PART1', '坏件 1 PN');
define('LG_SERVICEMANAGE_DEFECTIVE_PART2', '坏件 2 PN');
define('LG_SERVICEMANAGE_DEFECTIVE_PART3', '坏件 3 PN');
define('LG_SERVICEMANAGE_REPLACEMENT_PART1', '更换备件 1 PN');
define('LG_SERVICEMANAGE_REPLACEMENT_PART2', '更换备件 2 PN');
define('LG_SERVICEMANAGE_REPLACEMENT_PART3', '更换备件 3 PN');
define('LG_SERVICEMANAGE_REPAIR_LEVEL','维修等级');
define('LG_SERVICEMANAGE_SYMPTON_DISCRIPTION','故障描述');
define('LG_SERVICEMANAGE_LIABILITY','责任');
define('LG_SERVICEMANAGE_NEED_TURN','是否需要转到上级维修站维修');
define('LG_SERVICEMANAGE_WARRANTY_STATUS','保修状态');
define('LG_SERVICEMANAGE_EXPIRED_DATE','保修到期日');
define('LG_SERVICEMANAGE_NEW_IMEI', '新的IMEI');
define('LG_SERVICEMANAGE_ORIGINAL_HW', '初始硬件版本');
define('LG_SERVICEMANAGE_ORIGINAL_SW', '初始软件版本');
define('LG_SERVICEMANAGE_NEW_HW', '新硬件版本');
define('LG_SERVICEMANAGE_NEW_SW', '新软件版本');
define('LG_SERVICEMANAGE_PROBLEM_CATEGORY', '问题分类');
define('LG_SERVICEMANAGE_SWAPPARTSID', '替换零件PN');

define('LG_SERVICEMANAGE_STATUS', '维修状态');
//other
define('LG_SERVICEMANAGE_ORDER_ADD', '添加维修订单');
define('LG_CUSTOMER_NAME', '客户姓名');
define('LG_SERVICEMANAGE_CUSTOMER', '客户信息');
define('LG_CUSTOMER_TEL', '客户电话');
define('LG_CUSTOMER_CELL', '客户手机号');
define('LG_CUSTOMER_EMAIL', '客户EMAIL');
define('LG_CUSTOMER_ADDRESS', '客户地址');
define('LG_SERVICEMANAGE_ORDERINFO', '维修单信息');
define('LG_SERVICEMANAGE_CHARGEINFO', '收费信息');

//error msg
define('LG_SERVICEMANAGE_ORDER_ADD_ERROR', '添加服务订单失败');
define('LG_SERVICEMANAGE_ORDER_UPDATE_ERROR', '跟新服务订单失败');
define('LG_SERVICEMANAGE_ORDER_UNNULL_ERROR', '请检查无法为空的项目');
define('LG_SERVICEMANAGE_ORDER_DELETE_ERROR', '删除订单失败');
define('LG_SERVICEMANAGE_ORDER_DELETEID_ERROR', '获取订单id错误');
define('LG_SERVICEMANAGE_ORDER_ADD_DBERROR', '数据库更新错误');

//confirm msg
define('LG_SERVICEMANAGE_ORDER_DELETE_CONFIRM', '确认删除该维修单？');
define('LG_SERVICEMANAGE_ORDER_CLOSE_CONFIRM', '请确认维修单可以关闭');
define('LG_SERVICEMANAGE_ORDER_UPDATE_CONFIRM', '请确认更新维修单');

//section name
define('LG_SERVICEMANAGE_SERVICE_ORDER_LIST', '维修单列表');
define('LG_SERVICEMANAGE_SERVICE_ORDER_ADD', '维修单添加');
define('LG_SERVICEMANAGE_SERVICE_ORDER_MODIFY', '维修单修改');
define('LG_SERVICEMANAGE_CARRYIN_DATE', '客户上门时间');
define('LG_SERVICEMANAGE_FAILURECODE_NOT_EXIST', '故障码不存在');
define('LG_SERVICEMANAGE_IMEI_EMPTY', '您必须输入一个有效的IMEI');
define('LG_SERVICEMANAGE_MODEL_EMPTY', '不能找到IMEI记录，请核对');
define('LG_SERVICEMANAGE_CANNOT_REPAIR', '请将手机寄到上一级维修站维修');
define('LG_SERVICEMANAGE_SWAPPN_NOT_EXIST', '您申请的备件暂时没有库存，备件申请会自动发给联想备件服务商');
define('LG_SERVICEMANAGE_CHARGE_CURRENCY_EMPTY', 'Charge Currency can\'t empty');
define('LG_SERVICEMANAGE_CHARGE_AMOUNT_EMPTY', 'Charge Amount can\'t empty');

//service order apply
define('LG_SERVICEMANAGE_SERVICE_ORDER_ID', '维修单Id');
define('LG_SERVICEMANAGE_APPLY_PN', '备件PN');
define('LG_SERVICEMANAGE_APPLY_TIME', '申请时间');
define('LG_SERVICEMANAGE_STATUS', '申请状态');
define('LG_SERVICEMANAGE_DELIVER_TIME', '交付日期');

//service order turn
define('LG_SERVICEMANAGE_TURN_TIME', '转上级维修时间');