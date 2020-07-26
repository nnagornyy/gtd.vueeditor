import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import hello from './hello.vue';
document.addEventListener('DOMContentLoaded', function(){ // Аналог $(document).ready(function(){
    Vue.use(ElementUI);
    new Vue({
        render: (h) => h(hello),
    }).$mount('#vue_editor')                                                     // Если должен быть найден один элемент
});
