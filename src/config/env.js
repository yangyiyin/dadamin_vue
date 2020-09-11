/**
 * 配置编译环境和线上环境之间的切换
 * 
 * baseUrl: 域名地址
 * routerMode: 路由模式
 * baseImgPath: 图片存放地址
 * 
 */
let baseUrl = ''; 
let baseAdminUrl = '';
let baseWebSokectHost = '';
let routerMode = 'hash';
let baseImgPath;


if (process.env.NODE_ENV == 'development') {
	 baseUrl = 'http://dadadmin.myweb.com/backapp/index.php/api';
	 baseAdminUrl = 'http://dadadmin.myweb.com/backapp/index.php/api';

	//baseUrl = 'http://www.myweb.com/git-res/weapp_shop_php/apiback/index.php/apiback';
	//baseAdminUrl = 'http://www.myweb.com/git-res/weapp_shop_php/vendor/yyy/base_admin/app1/index.php/apiback';

} else{
	baseUrl = 'http://police_cooperation.mycej.com/api/app1/index.php/apiback';
	baseAdminUrl = 'http://police_cooperation.mycej.com/api/vendor/yyy/base_admin/app1/index.php/apiback';
}

export {
	baseUrl,
	routerMode,
	baseImgPath,
	baseAdminUrl,
	baseWebSokectHost
}