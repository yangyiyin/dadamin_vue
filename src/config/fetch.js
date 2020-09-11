import { baseAdminUrl } from './env'
import {getStore} from '@/config/mUtils'
import { Message } from 'element-ui';
import vueInstance from '@/main';
export default async(url = '', data = {}, type = 'GET', method = 'fetch') => {
	type = type.toUpperCase();
	var reg = new RegExp('/', "g");
	let loadingname = url.replace(reg, "");
	if (url.indexOf('//') == -1) {
		url = baseAdminUrl + url;
	}

	if (type == 'GET') {
		let dataStr = ''; //数据拼接字符串
		Object.keys(data).forEach(key => {
			dataStr += key + '=' + data[key] + '&';
		})

		if (dataStr !== '') {
			dataStr = dataStr.substr(0, dataStr.lastIndexOf('&'));
			url = url + '?' + dataStr;
		}
	}

	if (window.fetch && method == 'fetch') {
		let requestConfig = {
			method: type,
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			mode: "cors",
			cache: "force-cache"
		}

		if (type == 'POST') {
			data = data ? data : {};
			data.token = getStore('token') ? getStore('token') : '';
			Object.defineProperty(requestConfig, 'body', {
				value: JSON.stringify(data)
			})
		}
		
		try {
			vueInstance.$store.commit('on_loading', {loadingname:loadingname});
			//console.log(vueInstance.$store.state.loading[loadingname]);
			const response = await fetch(url, requestConfig);
			const responseJson = await response.json();
			vueInstance.$store.commit('off_loading', {loadingname:loadingname});
			//console.log(vueInstance.$store.state.loading[loadingname]);
			return responseJson
		} catch (error) {
			//提示
			console.log(error);
			Message({
				message: '错误:'+error,
				type: 'warning'
			});
			//throw new Error(error)
		}
	} else {
		Message({
			message: '当前浏览器不支持，建议使用chrome、火狐、ie9等现代浏览器',
			type: 'error'
		});
		// return new Promise((resolve, reject) => {
		// 	let requestObj;
		// 	if (window.XMLHttpRequest) {
		// 		requestObj = new XMLHttpRequest();
		// 	}
		//
		// 	let sendData = '';
		// 	if (type == 'POST') {
		// 		sendData = JSON.stringify(data);
		// 	}
		//
		// 	requestObj.open(type, url, true);
		// 	requestObj.setRequestHeader("Content-type", "application/json");
		// 	requestObj.send(sendData);
		//
		// 	requestObj.onreadystatechange = () => {
		// 		if (requestObj.readyState == 4) {
		// 			if (requestObj.status == 200) {
		// 				let obj = requestObj.response
		// 				if (typeof obj !== 'object') {
		// 					obj = JSON.parse(obj);
		// 				}
		// 				resolve(obj)
		// 			} else {
		// 				reject(requestObj)
		// 			}
		// 		}
		// 	}
		// })
	}
}