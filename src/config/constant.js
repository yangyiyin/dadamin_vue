/**
 * Created by yyy on 18/6/25.
 */
import { baseUrl,baseAdminUrl } from './env'
import {getStore} from '@/config/mUtils'
export default {
    APP_NAME:'DADADMIN后台管理',
    status_success : 100,
    upload_url:baseAdminUrl+'/qiniuupload/upload_img?bucket=cilitang&token='+getStore('token'),
    enable_tabs:false

}