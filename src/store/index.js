import Vue from 'vue'
import Vuex from 'vuex'
import constant from '@/config/constant'
import {baseAdminUrl} from "../config/env";

Vue.use(Vuex)

const state = {
	APP_NAME:constant.APP_NAME,
	constant:constant,
	adminInfo: {
		avatar: ''
	},
	role:1,
	baseAdminUrl:baseAdminUrl,
	roles_index:{
		0:'fuwuxiezuo_list',
		1:'fuwuxiezuo',
		2:'fuwuxiezuo_list',
	},
	is_pc:true,
	keepalive_include:[],
	editableTabsValue: '',
	editableTabs: [],
	loading:{},
	edit_mixins:{

	},
	list_mixins:{

	},
	page_breadcrumb:'',
	menu_tips:{}
}

const mutations = {
	set_keepalive_include(state, data){
		state.keepalive_include = data;
	},
	newTab(state, {title, name, url}) {
		let hasOne = false;
		state.editableTabs.forEach((tab, index) => {
			if (tab.name === name) {
				state.editableTabsValue = name;
				tab.url = url;
				hasOne = true;
				return;
			}
		});
		if (hasOne) {
			return;
		}

		state.editableTabs.push({
			title: title,
			name: name,
			url: url,
			closable: name != 'manage'
		});
		state.editableTabsValue = name;
	},
	on_loading(state, data){
		Vue.set(state.loading, data.loadingname, true);
	},
	off_loading(state, data){
		Vue.set(state.loading, data.loadingname, false);
	},
	is_pc(state, data){
		state.is_pc = data.from_pc;
	},
	set_page_breadcrumb(state,data){
		// console.log(data)
		state.page_breadcrumb = data;
	},
	set_menu_tips(state,data){
		state.menu_tips = data;
	},
	set_role(state,data){
		state.role = data;
	}
}

const actions = {
}

export default new Vuex.Store({
	state,
	actions,
	mutations,
})