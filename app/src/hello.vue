<template>
    <el-card style="margin: auto">
      <el-row class="main-row">
        <el-col class="content-wrapper" :span="18">
          <input type="hidden" :value='formData' :name="inputName">
          <draggable v-model="result" group="block" @start="drag=true" @end="drag=false">
            <el-card shadow="hover" class="block-wrapper" v-for="(block, i) in result">
              <el-row class="block-header">
                <el-col :span="20">
                  <el-divider class="block-header-name" content-position="left">{{block.name}}</el-divider>
                </el-col>
                <el-col :span="4">
                  <span class="delete-btn" @click="deleteBlock(i)"><i class="el-icon-delete"></i></span>
                </el-col>
              </el-row>
              <component :is="block.type" v-model="block.data" :blockValue="block.data"></component>
            </el-card>
          </draggable>
        </el-col>
        <el-col class="add-block-wrapper" :span="6">
          <div class="add-block-btn" @click="addBlock(block.value, block.label)" v-for="block in availableBlock">
            <span class="add-block-text">{{block.label}}</span>
          </div>
        </el-col>
      </el-row>
    </el-card>
</template>

<script>
  import draggable from 'vuedraggable'
  export default {
    name: "hello",
    components: BLOCK.reduce((obj, block) => {
        return Object.assign(obj, { [block.componentName]: () =>  import(block.filePath + '.vue') })
    }, {draggable}),
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
        deleteBlock(i){
          this.result.splice(i, 1);
        },
        addBlock(type, name = ""){
            let blueprint = {
                name: name,
                type: type,
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
<style>
  .block-header{
    opacity: 0;
    transition: opacity 600ms;
  }
  .block-header-name{
    margin-top: 0px;
  }
  .block-wrapper:hover .block-header{
    opacity: 1;
  }
  .delete-btn{
    position: absolute;
    display: block;
    right: 8px;
    font-size: 16px;
    top: -5px;
    cursor: pointer;
  }
  .delete-btn:hover{
    color: red;
  }
  .block-wrapper{
    margin-bottom: 20px;
  }
  .add-block-wrapper{
    padding-left: 35px;
    padding-right: 35px;
  }
  .content-wrapper{
    min-height: 200px;
    border-right: 1px solid #f3f3f3;
    padding-right: 36px;
  }
  .adm-workarea textarea.el-textarea__inner{
    box-shadow: none;
    font-size: inherit;
    color: #606266;
    background-color: #FFF;
    background-image: none;
    border: 1px solid #DCDFE6;
    border-radius: 4px;
    -webkit-transition: border-color .2s cubic-bezier(.645,.045,.355,1);
    transition: border-color .2s cubic-bezier(.645,.045,.355,1);
  }
  .adm-workarea .el-input__inner{
    -webkit-appearance: none !important;
    background-color: #FFF !important;
    background-image: none !important;
    border-radius: 4px !important;
    border: 1px solid #DCDFE6 !important;
    -webkit-box-sizing: border-box !important;
    box-sizing: border-box !important;
    color: #606266 !important;
    display: inline-block !important;
    font-size: inherit !important;
    height: 40px !important;
    line-height: 40px !important;
    outline: 0 !important;
    padding: 0 15px !important;
    -webkit-transition: border-color .2s cubic-bezier(.645,.045,.355,1) !important;
    transition: border-color .2s cubic-bezier(.645,.045,.355,1) !important;
    width: 100% !important;
    box-shadow: none !important;
    opacity: 1!important;
  }
  adm-workarea .el-input--mini  input.el-input__inner{
    height: 28px;
    line-height: 28px;
  }

  .main-row{
    width: 100%;
  }
  .add-block-btn{
    border: 1px solid #c9e7f9;
    border-radius: 3px;
    padding: 7px;
    cursor: pointer;
    position: relative;
    margin-bottom: 8px;
    box-shadow: 0px 2px 3px 0px #efebeb6b;
    transition: transform 300ms ease-out 100ms;
  }
  .add-block-btn:hover{
    transform: translateX(-5px);
  }
  .add-block-btn:after{
    content: '+';
    position: absolute;
    right: 10px;
  }
  .add-block-text{

  }
  .el-upload .adm-input-file{
    display: none!important;
  }
</style>