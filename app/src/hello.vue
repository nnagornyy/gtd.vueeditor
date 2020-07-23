<template>
    <component :is="current"></component>
</template>

<script>
    import components from './fieldLoader';
    import Vue from 'vue';
    const blockComponent = components();
    export default {
        name: "hello",
        components: Object.keys(blockComponent).reduce((obj, name) => {
            console.log(obj, name);
            return Object.assign(obj, { [name]: () => import(/* webpackChunkName: "field" */ blockComponent[name] + '.vue') })
        }, {}),
        // components:{
        //     number :() => import(/* webpackChunkName: "field" */'./block/number')
        // },
        data(){
            return {
                current: 'number'
            }
        }
    }
</script>
