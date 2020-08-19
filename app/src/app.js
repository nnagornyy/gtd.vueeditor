import Vue from 'vue';
import ElementUI from 'element-ui';
import VueYoutube from 'vue-youtube'
import 'element-ui/lib/theme-chalk/index.css';
import hello from './hello.vue';
import baseField from "./baseField";
import locale from 'element-ui/lib/locale/lang/ru-RU'

Vue.use(ElementUI, { locale });
Vue.mixin(baseField);
Vue.use(VueYoutube);

document.gtdEditor = ($value, input_name, app_id, allowBlocks = [], propId=0) => {
    let value = JSON.parse($value) || [];
    let block = allowBlocks.length > 0 ? JSON.parse(allowBlocks) : []
    new Vue({
        data(){
            return {
                val: value,
                inputName: input_name,
                allowBlocks: block
            }
        },
        render: (h) => h(hello),
        mounted() {
            let appTr = document.getElementById('tr_PROPERTY_'+propId);
            if(appTr){
                appTr.childNodes[3].colSpan = "2";
                appTr.childNodes[3].width = "100%";
                appTr.childNodes[1].remove();
            }
        }
    }).$mount('#' + app_id)                                                     // Если должен быть найден один элемент
}