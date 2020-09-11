import Vue from 'vue'
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

var size;
if (window.from_pc) {
    size = 'mini';
} else {
    size = 'mini';
}
Vue.use(Element);
