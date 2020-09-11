import fetch from '@/config/fetch'
import router from '@/router'
import {removeStore} from '@/config/mUtils'
import { baseAdminUrl } from '@/config/env'
var check_login = function(res){
    return new Promise(function(resolve){
        if (res.code == 999) {
            removeStore('token');
            router.push('/');
        } else {
            resolve(res);
        }

    });
}

export const getconfigvalue = data => fetch(baseAdminUrl+'/config/valuebykey', data, 'POST').then(check_login);
export const config_list = data => fetch(baseAdminUrl+'/config/index', data, 'POST').then(check_login);
export const config_edit = data => fetch(baseAdminUrl+'/config/edit', data, 'POST').then(check_login);
export const admin_log_list = data => fetch(baseAdminUrl+'/adminlog/index', data, 'POST').then(check_login);
export const admin_user_list = data => fetch(baseAdminUrl+'/adminuser/index', data, 'POST').then(check_login);
export const admin_user_all_list = data => fetch(baseAdminUrl+'/adminuser/get_all', data, 'POST').then(check_login);
export const admin_user_edit = data => fetch(baseAdminUrl+'/adminuser/edit', data, 'POST').then(check_login);
export const admin_user_verify = data => fetch(baseAdminUrl+'/adminuser/verify', data, 'POST').then(check_login);
export const admin_user_del = data => fetch(baseAdminUrl+'/adminuser/del', data, 'POST').then(check_login);
export const admin_user_info = data => fetch(baseAdminUrl+'/adminuser/info', data, 'POST').then(check_login);
export const admin_group_list = data => fetch(baseAdminUrl+'/admingroup/index', data, 'POST').then(check_login);
export const admin_group_all_list = data => fetch(baseAdminUrl+'/admingroup/all_list', data, 'POST').then(check_login);
export const admin_group_edit = data => fetch(baseAdminUrl+'/admingroup/edit', data, 'POST').then(check_login);
export const admin_group_verify = data => fetch(baseAdminUrl+'/admingroup/verify', data, 'POST').then(check_login);
export const admin_group_del = data => fetch(baseAdminUrl+'/admingroup/del', data, 'POST').then(check_login);
export const admin_group_info = data => fetch(baseAdminUrl+'/admingroup/info', data, 'POST').then(check_login);
export const admin_group_sort = data => fetch(baseAdminUrl+'/admingroup/sort', data, 'POST').then(check_login);
export const admin_purview_tree = data => fetch(baseAdminUrl+'/adminpurview/tree', data, 'POST').then(check_login);
export const admin_purview_all_list = data => fetch(baseAdminUrl+'/adminpurview/all_list', data, 'POST').then(check_login);
export const admin_purview_edit = data => fetch(baseAdminUrl+'/adminpurview/edit', data, 'POST').then(check_login);
export const admin_purview_verify = data => fetch(baseAdminUrl+'/adminpurview/verify', data, 'POST').then(check_login);
export const admin_purview_del = data => fetch(baseAdminUrl+'/adminpurview/del', data, 'POST').then(check_login);
export const admin_purview_info = data => fetch(baseAdminUrl+'/adminpurview/info', data, 'POST').then(check_login);
export const admin_purview_sort = data => fetch(baseAdminUrl+'/adminpurview/sort', data, 'POST').then(check_login);
export const get_menu = data => fetch(baseAdminUrl+'/adminpurview/get_menu', data, 'POST').then(check_login);

//公共请求方法 如果path 带有http://则表示直接地址，如果path不带http://则默认在前面加上baseAdminUrl
export const common_api = (path, data) => fetch('/'+path, data, 'POST').then(check_login);





