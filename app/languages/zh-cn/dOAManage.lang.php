<?php
/**
 * Ludo BillGo Platform
 *
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
//数据库字段名�?
define('LG_DOAMANAGE_ID', 'ID');
define('LG_DOAMANAGE_USERID', 'User Id');
define('LG_DOAMANAGE_USERNAME', 'User');

define('LG_DOAMANAGE_MODEL', 'Product Model');
define('LG_DOAMANAGE_COLOR', 'Color');
define('LG_DOAMANAGE_PN', 'PN');
define('LG_DOAMANAGE_IMEI', 'IMEI');
define('LG_DOAMANAGE_PURCHASE_DATE', 'Purchase Date');
define('LG_DOAMANAGE_CARRYIN_DATE', 'Carry-in Date');
define('LG_DOAMANAGE_KEY_ACCOUNT_NAME', 'Key Account Name');
define('LG_DOAMANAGE_CUSTOMER_REPORT', 'Problem Description by User');
define('LG_DOAMANAGE_FAILUREID', 'Fault Code');
define('LG_DOAMANAGE_TESTRESULT', 'Detailed Problem Description');
define('LG_DOAMANAGE_REPAIREDTIME', 'Repaired Time');
define('LG_DOAMANAGE_CHARGE', 'Charge to Customer?');
define('LG_DOAMANAGE_CHARGECURRENCY', 'ChargeCurrency');
define('LG_DOAMANAGE_CHARGEAMOUNT', 'ChargeAmount');
define('LG_DOAMANAGE_STATUS', 'DOA Order Status');
define('LG_DOAMANAGE_RECOVER_METHOD', 'Recover Method');
define('LG_DOAMANAGE_WAREHOUSEID', 'Warehouse');
define('LG_DOAMANAGE_SWAP_PART1', 'Swap Part 1');
define('LG_DOAMANAGE_SWAP_PART2', 'Swap Part 2');
define('LG_DOAMANAGE_SWAP_PART3', 'Swap Part 3');
define('LG_DOAMANAGE_DEFECTIVE_PART1', 'Defective Part 1 PN');
define('LG_DOAMANAGE_DEFECTIVE_PART2', 'Defective Part 2 PN');
define('LG_DOAMANAGE_DEFECTIVE_PART3', 'Defective Part 3 PN');
define('LG_DOAMANAGE_REPLACEMENT_PART1', 'Replacement Part 1 PN');
define('LG_DOAMANAGE_REPLACEMENT_PART2', 'Replacement Part 2 PN');
define('LG_DOAMANAGE_REPLACEMENT_PART3', 'Replacement Part 3 PN');
define('LG_DOAMANAGE_REPAIR_LEVEL','Repair Level');
define('LG_DOAMANAGE_SYMPTON_DISCRIPTION','Symptom Discription');
define('LG_DOAMANAGE_LIABILITY','Liability');
define('LG_DOAMANAGE_WARRANTY_STATUS','Warranty Status');
define('LG_DOAMANAGE_NEW_IMEI', 'New IMEI');
define('LG_DOAMANAGE_ORIGINAL_HW', 'Origianl HW version');
define('LG_DOAMANAGE_ORIGINAL_SW', 'Original SW version');
define('LG_DOAMANAGE_NEW_HW', 'New HW version');
define('LG_DOAMANAGE_NEW_SW', 'New SW version');
define('LG_DOAMANAGE_PROBLEM_CATEGORY', 'Problem Category');



//other
define('LG_DOAMANAGE_ORDER_ADD', 'Add DOA Order');
define('LG_CUSTOMER_NAME', 'Customer Name');
define('LG_DOAMANAGE_CUSTOMER', 'Customer Info');
define('LG_CUSTOMER_TEL', 'Customer Telphone');
define('LG_CUSTOMER_CELL', 'Customer Cellphone');
define('LG_CUSTOMER_EMAIL', 'Customer Email');
define('LG_CUSTOMER_ADDRESS', 'Customer Address');
define('LG_DOAMANAGE_ORDERINFO', 'DOA Info');
define('LG_DOAMANAGE_CHARGEINFO', 'Charge Info');

//error msg
define('LG_DOAMANAGE_ORDER_ADD_ERROR', 'Add DOA Order Fail');
define('LG_DOAMANAGE_ORDER_UPDATE_ERROR', 'DOA Order Update Fail');
define('LG_DOAMANAGE_ORDER_UNNULL_ERROR', 'DOA Order Unnull');
define('LG_DOAMANAGE_ORDER_DELETE_ERROR', 'DOA Order Delete Fail');

//confirm msg
define('LG_DOAMANAGE_ORDER_DELETE_CONFIRM', 'Please Confirm to Delete');
define('LG_DOAMANAGE_ORDER_CLOSE_CONFIRM', 'Please Confirm to Close');

//section name
define('LG_DOAMANAGE_DOA_ORDER_LIST', 'DOA Order List');
define('LG_DOAMANAGE_DOA_ORDER_ADD', 'Add DOA Order');
define('LG_DOAMANAGE_DOA_ORDER_MODIFY', 'Modify DOA Order');
define('LG_DOAMANAGE_FAILURECODE_NOT_EXIST', 'Failure Code not exist');
define('LG_DOAMANAGE_CANNOT_REPAIR', 'Please send the defect phone to %s center for furthur repairing.');
define('LG_DOAMANAGE_SWAPPN_NOT_EXIST', 'The DOA part is not available now, and part application will be submitted to Lenovo Mobile Parts Partner');
define('LG_DOAMANAGE_CHARGE_CURRENCY_EMPTY', 'Charge Currency can\'t empty');
define('LG_DOAMANAGE_CHARGE_AMOUNT_EMPTY', 'Charge Amount can\'t empty');
