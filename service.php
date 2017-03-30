<?php
/**
 * User: hidehalo
 * Mail: tianchen@g-i.asia
 * DateTime: 2016/11/21 16:45
 */
return array(

	'OrderServer'=>[
		'DesignOrder',
		'WalletOrder',
		'Wx',
		'Alipay',
		'ProcessOrder',
		'CrowdFundingOrder',
	],
	
	'CrowdFundingServer'=>[
		'CrowdFunding'
	],

	'UserServer'=>[
		'User',
		'UserAddress',
		'UserWallet',
		'UserFavorites',
		'UserFollow',
		'UserBankcard',
		'UserNotice',
		'UserDesigner',
		'UserDesignerWallet',
		'UserDesignerScore',
		'UserDesignerBankcard',
		'UserDesignerNotice',
		'UserAdmin',
		'UserAdminPageAcl',
		'Cash'
	],

	'PublicServer'=>[
		'Product',
		'Comment',
		'DesignPost',
		'MyShow',
		'Subject',
		'Report',
	],

	'LogServer'=>[
		'AliPayLog',
	],
	
);