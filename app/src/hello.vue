<template>
    <div>
        <input type="hidden" :value='formData' :name="inputName">
        <div class="block-wrapper" v-for="block in result">
            <component :is="block.type" :blockValue="block.data"></component>
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
            return Object.assign(obj, { [block.componentName]: () =>  import(block.filePath + '.vue') })
        }, {}),
        data(){
            return {
                selected: '',
                inputName: '',
                result:[],
                allowBlock:[],
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
        mounted() {
            this.result = this.$root.$data.val || [];
            this.allowBlock = this.$root.$data.allowBlocks;
            this.inputName = this.$root.$data.inputName;
        },
        computed:{
            formData(){
              return JSON.stringify(this.result);
            },
            availableBlock(){
                let blocks = [];
                BLOCK.forEach(b => {
                    if(this.allowBlock.length === 0 || this.allowBlock.includes(b.componentName))
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
