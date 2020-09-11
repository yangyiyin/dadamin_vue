/* eslint-disable */
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const login = r => require.ensure([], () => r(require('@/page/login')), 'login');
const manage = r => require.ensure([], () => r(require('@/page/manage')), 'manage');
const error = r => require.ensure([], () => r(require('@/page/error')), 'error');

const admin_user = r => require.ensure([], () => r(require('@/page_biz/admin_user')), 'admin_user');
const add_admin_user = r => require.ensure([], () => r(require('@/page_biz/add_admin_user')), 'add_admin_user');
const admin_group = r => require.ensure([], () => r(require('@/page_biz/admin_group')), 'admin_group');
const add_admin_group = r => require.ensure([], () => r(require('@/page_biz/add_admin_group')), 'add_admin_group');
const admin_purview = r => require.ensure([], () => r(require('@/page_biz/admin_purview')), 'admin_purview');
const add_admin_purview = r => require.ensure([], () => r(require('@/page_biz/add_admin_purview')), 'add_admin_purview');
const group_purview = r => require.ensure([], () => r(require('@/page_biz/group_purview')), 'group_purview');
const admin_log = r => require.ensure([], () => r(require('@/page_biz/admin_log')), 'admin_log');
const config = r => require.ensure([], () => r(require('@/page_biz/config')), 'config');

//{#replace1#}

var children = [
	{
		path: '/error',
		component: error,
		meta: {path:['错误', '错误']},
	},

	{
		path: '/admin_user',
		component: admin_user,
		meta: {
			path:['系统管理', '用户管理'],
			_menu:'admin_user'
		},
	},
	{
		path: '/add_admin_user',
		component: add_admin_user,
		meta: {
			path:['系统管理', '添加/编辑用户'],
			_menu:'admin_user'
		},
	},
	{
		path: '/admin_group',
		component: admin_group,
		meta: {
			path:['系统管理', '角色组管理'],
			_menu:'admin_group'
		},
	},
	{
		path: '/add_admin_group',
		component: add_admin_group,
		meta: {
			path:['系统管理', '添加/编辑角色组'],
			_menu:'admin_group'
		},
	},
	{
		path: '/admin_purview',
		component: admin_purview,
		meta: {
			path:['系统', '权限节点管理'],
			_menu:'admin_purview'
		},
	},
	{
		path: '/add_admin_purview',
		component: add_admin_purview,
		meta: {
			path:['系统', '添加/编辑权限节点'],
			_menu:'admin_purview'
		},
	},
	{
		path: '/group_purview',
		component: group_purview,
		meta: {
			path:['系统管理', '部门/单位权限'],
			_menu:'admin_group'
		},
	},


	{ path: '/admin_log',component: admin_log,meta: {path:['用户管理', '操作日志'],_menu:'admin_log'},},
	{ path: '/config',component: config,meta: {path:['系统', '参数设置'],_menu:'config'},},

//{#replace2#}
];

if (window.is_in_dingding) {
	if (window.from_pc) {
		var routes = [
			{
				path: '/',
				component: autologin
			},
			{
				path: '/manage',
				component: manage,
				name: 'manage',
				meta: {
					path:['首页'],
					_menu:'manage'
				},
				children: children,
			}
		];
	} else {
		var routes = [
			{
				path: '/',
				component: autologin
			}
		];
	}
} else {
	if (window.from_pc) {
		var routes = [
			{
				path: '/',
				component: login
			},
			{
				path: '/manage',
				component: manage,
				name: 'manage',
				meta: {
					path:['首页'],
					_menu:'manage'
				},
				children: children,
			}
		];
	} else {
		var routes = [
			{
				path: '/',
				component: login
			},
			{
				path: '/manage_mobile',
				component: manage_mobile,
				name: 'manage_mobile',
				meta: {
					path:['首页'],
					_menu:'manage_mobile'
				},
				children: children,
			}
		];
	}
}
// console.log(routes);
var router =  new Router({
	routes,
	strict: process.env.NODE_ENV !== 'production',
});
export default router;
