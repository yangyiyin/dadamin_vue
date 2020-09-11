// eslint-disable-next-line
/* eslint-disable */

import Vue from 'vue'
import './plugins/autosize.js'
import './plugins/scroller.js'
import App from './App'
import router from './router'
import store from './store/'
import '@/assets/css/main.css'

import './plugins/element.js'
import './plugins/checkclose.js'
import './plugins/echarts.js'

import {setStore, getStore} from '@/config/mUtils'


router.afterEach((to, from) => {
    //加入keepalive 数组
    var url = to.fullPath.replace('/', '');
    var name = to.path.replace('/', '');
    if (store.state.constant.enable_tabs && name && to.meta.path) {
        if (store.state.keepalive_include.indexOf(name) == -1) {
            store.state.keepalive_include.push(name);
        }
        //设置标签页数组
        store.commit('newTab', {title:to.meta.path.join('-'),name:name, url:url});
    }
})

store.commit('is_pc', {from_pc:window.from_pc});

var user_info = JSON.parse(getStore('user_info'));

if (user_info) {
    store.commit('set_role', parseInt(user_info.group_id))
}

export default new Vue({
	router,
	store,
	render: h => h(App),
 }).$mount('#app');