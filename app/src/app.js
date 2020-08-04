import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import hello from './hello.vue';
import baseField from "./baseField";
import locale from 'element-ui/lib/locale/lang/ru-RU'
Vue.use(ElementUI, { locale });
Vue.mixin(baseField);


document.gtdEditor = (value, input_id, container) => {
    let val = value || [];
    new Vue({
        data(){
            return {
                val: val,
                input: input_id
            }
        },
        render: (h) => h(hello),
    }).$mount('#' + container)                                                     // Если должен быть найден один элемент
}