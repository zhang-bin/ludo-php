<?php
/**
 * 权限相关的资源与操作的配置文件
 * 
 * 调用方法:
 * $permConf = Load::conf('Permission');
 * echo $permConf['Host']['actions']['read']; //所有服务器查看权限相关的ctrl/action
 */
return array(
	'product' => array( //Resource
		'name' => '商品',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('product' => array('index', 'tbl','view', 'suggest'))),
			'create' 	=> array('name' => '添加', 'url' => array('product' => array('add', 'upload', 'import', 'sort'))),
			'update'	=> array('name' => '修改', 'url' => array('product' => array('change', 'upload', 'import', 'sort'))),
			'delete'	=> array('name' => '删除', 'url' => array('product' => 'del')),
			'category'  => array('name' => '商品分类管理' ,'url' => array('productCategory' => '*')),
		),
	),
	'brand' => array( //Resource
		'name' => '品牌',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('brand' => array('index','tbl','view','suggest'))), 
			'create' 	=> array('name' => '添加', 'url' => array('brand' => 'add')),
			'update'	=> array('name' => '修改', 'url' => array('brand' => 'change')),
			'delete'	=> array('name' => '删除', 'url' => array('brand' => 'del')),
			'recommend' => array('name' => '推荐品牌管理', 'url' => array('recommendBrand' => '*'))
		),
	),
	'promotion' => array(
		'name' => '促销',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('promotion' => array('index', 'tbl', 'view'))),
			'create' => array('name' => '添加', 'url' => array('promotion' => array('add', 'product', 'productTbl', 'show'))),
			'update' => array('name' => '修改', 'url' => array('promotion' => array('change', 'product', 'productTbl', 'prolong', 'show'))),
			'delete' => array('name' => '删除', 'url' => array('promotion' => 'del'))
		)
	),
	'groupBuy' => array(
		'name' => '团购',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('groupBuy' => array('index', 'tbl', 'view', 'suggest', 'progress'))),
			'create' => array('name' => '添加', 'url' => array('groupBuy' => array('add', 'product', 'productTbl', 'suggestProduct'))),
			'update' => array('name' => '修改', 'url' => array('groupBuy' => array('change', 'product', 'productTbl', 'prolong', 'off', 'suggestProduct'))),
			'delete' => array('name' => '删除', 'url' => array('groupBuy' => 'del')),
			'start' => array('name' => '发起团购管理', 'url' => array('startGroupBuy' => '*'))
		)
	),
	
	'supplier' => array( //Resource
		'name' => '供应商',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('supplier' => array('index','tbl','view','suggest', 'suggestBrand'))), 
			'create' 	=> array('name' => '添加', 'url' => array('supplier' => array('add', 'upload'))),
			'update'	=> array('name' => '修改', 'url' => array('supplier' => array('change', 'upload'))),
			'delete'	=> array('name' => '删除', 'url' => array('supplier' => 'del')),
		),
	),
	'customer' => array(
		'name' => '客户',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('customer' => array('index', 'tbl', 'view', 'suggest', 'suggestUser', 'info','account','accountTbl', 'suggestProduct', 'brandDiscount'))),
			'create' 	=> array('name' => '添加', 'url' => array('customer' => array('add', 'resetPwdApply'))),
			'update'	=> array('name' => '修改', 'url' => array('customer' => array('change', 'resetPwdApply'))),
			'delete'	=> array('name' => '删除', 'url' => array('customer' => 'del')),
			'verify'    => array('name' => '审核', 'url' => array('customer' => 'verify')),
			'type'		=> array('name' => '客户类型管理', 'url' => array('customerType' => '*')),
			'group' => array('name' => '客户分组管理', 'url' => array('customerGroup' => array('index', 'tbl', 'add', 'modify', 'del', 'suggestProduct', 'view', 'info', 'brandDiscount'))),
			'discount'	=> array('name' => '折扣设置', 'url' => array('customer' => 'discount')),
			'customerCount' => array('name' => '客户统计', 'url' => array('customerCount' => '*'))	,
			'customerGroupDiscount' => array('name' => '客户分组折扣设置', 'url' => array('customerGroup' => 'discount'))
		),
	),
	'salesOrder' => array(
		'name' => '销售订单',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('salesOrder' => array('index', 'tbl', 'suggest', 'view', 'salesProduct', 'salesProductTbl', 'suggestCustomer', 'suggestProduct', 'suggestUser'))),
			'create' 	=> array('name' => '添加', 'url' => array('salesOrder' => array(
				'add', 'save', 'change', 'suggestProduct', 'suggestCustomer', 'customer', 'customerTbl', 'product', 'productTbl'
			))),
			'cancel'	=> array('name' => '取消', 'url'=>array('salesOrder' => array('cancel', 'cancelProduct'))),
			'return'	=> array('name' => '退货', 'url'=>array('salesOrder' => array(
				'salesReturnAdd', 'salesReturn', 'salesReturnTbl'
			))),
			'returnView' => array('name' => '退货单', 'url' => array('salesOrder' => array('returnIndex', 'returnView'))),
			'salesOrderCount' => array('name' => '订单统计', 'url' => array('salesOrderCount' => '*'))
		),
	),
	'enquiryOrder' => array(
		'name' => '询价单',
		'operations' => array(
			'read'	 	=> array('name' => '查看', 'url' => array('enquiryOrder' => array('index', 'tbl', 'view'))),
			'reply'		=> array('name' => '回复', 'url' => array('enquiryOrder' => 'reply'))
		),
	),
	'purchaseOrder' => array(
	    'name' => '采购订单',
	    'operations' => array(
	        'read'	 	=> array('name' => '查看', 'url' => array('purchaseOrder' => array('index', 'tbl', 'view', 'suggest', 'purchaseProduct', 'purchaseProductTbl'))),
	        'create'    => array('name' => '添加', 'url' => array('purchaseOrder' => array(
	        	'add', 'product', 'productTbl', 'suggestProduct'
			))),
			'update'	=> array('name' => '修改', 'url' => array('purchaseOrder' => array(
	        	'change', 'product', 'productTbl', 'suggestProduct'
			))),
			'confirm'	=> array('name' => '确认', 'url' => array('purchaseOrder' => 'confirm')),
			'stockIn' 	=> array('name' => '入库', 'url' => array('purchaseOrder' => 'stockIn', 'InventorySerial' => '*'))
	    ),
	),
	'shippingOrder' => array(
		'name' => '发货订单',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('shippingOrder' => array('index', 'tbl', 'suggest', 'view', 'suggestProduct', 'suggestCustomer', 'my'))),
			'send' => array('name' => '发货', 'url' => array('shippingOrder' => array('send'))),
			'receive' => array('name' => '收货', 'url' => array('receivings' => '*'))
		),
	),
	'businessShipping' =>array(
		'name' => '商务发货',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('businessShipping' => array('index', 'tbl', 'view'))),
			'send' => array('name' => '发货', 'url' => array('businessShipping' => array('send', 'unifySend')))
		)
	),
	'businessReadyShipping' => array(
		'name' => '商务准备发货',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('businessReadyShipping' => array('index', 'view'))),
			'send' => array('name' => '发货', 'url' => array('businessReadyShipping' => array('send'))),
			'print' => array('name' => '打印', 'url' => array('businessReadyShipping' => array('stamp')))
		)
	),
	'integralOrder' => array(
		'name' => '积分订单',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('integralOrder' => array('index', 'tbl', 'view'))),
			'confirm' => array('name' => '确认', 'url' => array('integralOrder' => 'confirm'))
		)
	),
	'customerFinance' => array(
		'name' => '客户账户信息',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('customerFinance' => '*'))
		)
	),
	'customerPayment' => array(
		'name' => '客户到款信息',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('customerPayment' => array('index', 'tbl', 'suggestCustomer', 'view', 'unPayed', 'unPayedTbl','unifyPayment'))),
			'oper' => array('name' => '操作', 'url' => array('customerPayment' => array('add', 'customer', 'customerTbl', 'getShippingOrder', 'pay', 'unifyPay', 'unifyPayAdd')))
		)
	),
	'supplierPayment' => array(
		'name' => '供应商付款信息',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('supplierPayment' => array('index', 'tbl', 'suggest', 'suggestSupplier', 'view', 'unPayed', 'unPayedTbl'))),
			'oper' => array('name' => '操作', 'url' => array('supplierPayment' => array('add', 'supplier', 'supplierTbl', 'getPurchaseOrder', 'pay')))
		)
	),
	'inventory' => array(
		'name' => '库存管理',
		'operations' => array(
			'read' => array('name' => '库存查看', 'url' => array('inventory' => array('index', 'tbl'))),
			'create' => array('name' => '库存添加', 'url' => array('inventory' => array('add', 'transfer', 'product', 'productTbl'))),
			'warehouse' => array('name' => '仓库管理', 'url' => array('warehouse' => '*'))
		)
	),
	'comment' => array(
		'name' => '商品评论',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('support' => array('index', 'comment', 'commentTbl', 'commentView'))),
			'create' => array('name' => '添加', 'url' => array('support' => array('commentAdd', 'product', 'productTbl'))),
			'reply' => array('name' => '回复', 'url' => array('support' => 'commentReply')),
			'delete' => array('name' => '删除', 'url' => array('support' => 'commentDel'))
		)
	),
	'suggest' => array(
		'name' => '投诉与建议',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('support' => array('suggest', 'suggestTbl', 'suggestView'))),
			'reply' => array('name' => '回复', 'url' => array('support' => 'suggestReply')),
			'delete' => array('name' => '删除', 'url' => array('support' => 'suggestDel'))
		)
	),
	'letter' => array(
		'name' => '站内信',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('letter' => array('index', 'tbl', 'view'))),
			'create' => array('name' => '发送', 'url' => array('letter' => array('sendLetter', 'reply')))
	)
	),
	'systemEmail' => array(
		'name' => '邮件',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('systemEmail' => array('index', 'view'))),
			'create' => array('name' => '发送', 'url' => array('systemEmail' => 'sendMail')),
		)
	),
	'sms' => array(
		'name' => '短信',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('sms' => array('index', 'tbl', 'view'))),
			'create' => array('name' => '发送', 'url' => array('sms' => 'sendMessage')),
			'smsTpl' => array('name' => '短信模板管理', 'url' => array('sms' => array('smsTpl', 'change', 'modifySmsTpl', 'add', 'addSmsTpl', 'del', 'delSmsTpl')))
		)
	),
	'news' => array(
		'name' => '新闻',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('news' => array('index', 'view'))),
			'create' 	=> array('name' => '添加', 'url' => array('news' => 'add')),
			'update'	=> array('name' => '修改', 'url' => array('news' => 'change')),
			'delete'	=> array('name' => '删除', 'url' => array('news' => 'del')),
	)
	),
	'movement' => array(
		'name' => '活动',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('movement' => array('index', 'view', 'viewExercise', 'suggest'))),
			'create' => array('name' => '添加', 'url' => array('movement' => array('add', 'carriage'))),
			'update' => array('name' => '修改', 'url' => array('movement' => array('change', 'carriage'))),
			
		)
	),
	'literature' => array(
		'name' => '技术资料',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('technical' => array('index', 'literature', 'literatureTbl'))),
			'create' => array('name' => '添加', 'url' => array('technical' => array('literatureAdd', 'literatureUpload'))),
			'update' => array('name' => '修改', 'url' => array('technical' => array('literatureChange', 'literatureUpload'))),
			'delete' => array('name' => '删除', 'url' => array('technical' => 'literatureDel')),
			'category' => array('name' => '分类管理', 'url' => array('technical' => array('literatureCategory', 'literatureCategoryAdd', 'literatureCategoryChange', 'literatureCategoryDel')))
		)
	),
	'experiment' => array(
		'name' => '实验心得',
		'operations' => array(
			'read' => array('name' => '查看', 'url' => array('technical' => array('experiment', 'experimentTbl', 'experimentView'))),
			'verify' => array('name' => '审核', 'url' => array('technical' => array('experimentVerify', 'experimentKey'))),
			'delete' => array('name' => '删除', 'url' => array('technical' => 'experimentDel')),
		)
	),
// 	'printingExpress' => array(
// 		'name' => '面单设置',
// 		'operations' => array(
// 			'read' => array('name' => '查看', 'url'=> array('printingExpress' => array('index'))),
// 			'update' => array('name' => '修改', 'url' => array('printingExpress' => array('change')))
// 		)		
// 	)
);
?>