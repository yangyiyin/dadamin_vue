import fetch from '@/config/fetch'
import { baseAdminUrl } from '@/config/env'
/**
 * 登陆
 */

export const login = data => fetch(baseAdminUrl+'/admin/login', data, 'POST');


