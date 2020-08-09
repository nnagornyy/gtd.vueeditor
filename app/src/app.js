import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import hello from './hello.vue';
import baseField from "./baseField";
import locale from 'element-ui/lib/locale/lang/ru-RU'
Vue.use(ElementUI, { locale });
Vue.mixin(baseField);


document.gtdEditor = ($value, input_name, app_id, allowBlocks = []) => {
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
    }).$mount('#' + app_id)                                                     // Если должен быть найден один элемент
}