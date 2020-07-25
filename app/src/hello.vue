<template>
    <div>
        {{JSON.stringify(result)}}
        <div class="block-wrapper" v-for="block in result">
            <component :is="block.type" v-model="block.data"></component>
        </div>
        <div class="select-wrapper">
            <select v-model="selected" style="display: block">
                <option v-for="block in availableBlock">{{block}}</option>
            </select>
            <button @click="addBlock">Добавить блок</button>
        </div>
    </div>
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
        data(){
            return {
                selected: '',
                result:[],
            }
        },
        methods:{
            addBlock(){
                let blueprint = {
                    type: this.selected,
                    data: {}
                }
                this.result.push(blueprint);
            }
        },
        computed:{
            availableBlock(){
                return Object.keys(blockComponent);
            }
        }
    }
</script>
