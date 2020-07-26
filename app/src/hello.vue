<template>
    <div>
        {{JSON.stringify(result)}}
        <div class="block-wrapper" v-for="block in result">
            <component :is="block.type" v-model="block.data"></component>
        </div>
        <div class="select-wrapper">
            <el-select v-model="selected" style="display: block">
                <el-option :value="block.value" :label="block.label" v-for="block in availableBlock"></el-option>
            </el-select>
            <el-button type="primary" @click="addBlock" icon="el-icon-edit" circle></el-button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "hello",
        components: BLOCK.reduce((obj, block) => {
            console.log(block);
            return Object.assign(obj, { [block.componentName]: () => import(/* webpackChunkName: "field" */ './'+block.filePath + '.vue') })
        }, {}),
        data(){
            return {
                selected: '',
                result:[],
            }
        },
        methods:{
            config(block){

            },
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
                let blocks = [];
                BLOCK.forEach(function (b) {
                    blocks.push({
                        label: b.config.name,
                        value: b.componentName
                    })
                })
                return blocks;
            }
        }
    }
</script>
