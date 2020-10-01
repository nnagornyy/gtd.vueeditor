<template>
    <el-card style="margin: auto">
      <el-row class="main-row">
        <el-col class="content-wrapper" :span="18">
          <input type="hidden" :value='formData' :name="inputName">
          <draggable handle=".handle" :key="uniqueKey" v-model="result" group="block" @start="drag=true" @end="drag=false">
            <el-card shadow="hover" class="block-wrapper" v-for="(block, i) in result">
              <el-row class="block-header">
                <el-col :span="20">
                  <el-divider class="block-header-name" content-position="left">{{block.name}}</el-divider>
                </el-col>
                <el-col :span="4" class="block-actions">
                  <div class="block-action">
                    <span class="handle"> <i class="el-icon-rank"></i></span>
                  </div>
                  <div class="block-action">
                    <el-dropdown @command="handleBlockCommand">
                      <span class="el-dropdown-link"><i class="el-icon-setting el-icon--right"></i></span>
                      <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item :command="{'action':'save', 'index':i}">Сохранить пресет</el-dropdown-item>
                        <el-dropdown-item :command="{'action':'load', 'index':i}">Загрузить пресет</el-dropdown-item>
                      </el-dropdown-menu>
                    </el-dropdown>
                  </div>
                  <div class="block-action">
                    <span class="delete-btn" @click="deleteBlock(i)"><i class="el-icon-delete"></i></span>
                  </div>
                </el-col>
              </el-row>
              <component :is="block.type" v-model="block.data" :blockValue="block.data"></component>
            </el-card>
          </draggable>
        </el-col>
        <el-col class="add-block-wrapper" :span="6">
          <div  v-for="block in availableBlock">
            <el-popover v-if="block.about" :open-delay="1000" placement="right" width="400" trigger="hover">
              <el-image
                  style="width: 400px; height: 400px"
                  :src="block.about.img"
                  fit="fill"></el-image>
              <div slot="reference" class="add-block-btn" @click="addBlock(block.value, block.label)">
                <span class="add-block-text">{{block.label}}</span>
              </div>
            </el-popover>
            <div v-else slot="reference" class="add-block-btn" @click="addBlock(block.value, block.label)">
              <span class="add-block-text">{{block.label}}</span>
            </div>
          </div>
        </el-col>
      </el-row>
      <el-dialog v-loading="preset.loading" title="Выбор пресета" :visible.sync="preset.openDialog">
        <el-table @row-click="presetSelect" :data="preset.presetList">
          <el-table-column property="title" label="Заголовок"></el-table-column>
          <el-table-column property="created" label="Создана"></el-table-column>
          <el-table-column property="user" label="Создал"></el-table-column>
        </el-table>
      </el-dialog>
    </el-card>
</template>

<script>
  import draggable from 'vuedraggable';
  import axios from 'axios';
  export default {
    name: "hello",
    components: BLOCK.reduce((obj, block) => {
        return Object.assign(obj, { [block.componentName]: () =>  import(block.filePath + '.vue') })
    }, {draggable}),
    data(){
        return {
            uniqueKey: 100,
            preset:{
              loading: false,
              openDialog: false,
              currentIndex:0,
              presetList:[],
            },
            selected: '',
            inputName: '',
            result:[],
            allowBlock:[],
        }
    },
    methods:{
      config(block){},
      handleBlockCommand(a){
        if(a.action == 'save'){
          this.savePreset(a.index);
        }
        if(a.action == 'load'){
          this.loadPresetList(a.index);
        }
      },
      loadPresetList(i){
        this.preset.currentIndex = i;
        let block = this.result[i].type
        this.preset.loading = true;
        axios.get('/local/modules/gtd.vueeditor/service/preset.php?action=list&block='+block)
        .then(res => {
          this.preset.presetList = res.data.result;
          this.preset.openDialog = true;
          this.preset.loading = false;
        })
      },
      presetSelect(r,c,e){
        this.result[this.preset.currentIndex].data = r.data;
        this.uniqueKey = new Date().getMilliseconds();
        this.preset.openDialog = false;
      },
      savePreset(i){
        this.$prompt('Введите название', 'Запись', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          inputErrorMessage: 'не верное название'
        }).then(({ value }) => {
          axios.post('/local/modules/gtd.vueeditor/service/preset.php?action=save', {
            'title': value,
            'block': this.result[i].type,
            'data': this.result[i].data
          }).then(res => {
            this.$message({
              type: 'success',
              message: 'Пресет записан:' + value
            });
          })
        });
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
                    value: b.componentName,
                    about: b.config.conf.about,
                })
            })
            return blocks;
        }
    }
  }
</script>
<style>
  .block-actions{
    display: flex;
    justify-content: flex-end;
    margin-top: -6px;
  }
  .block-action{
    cursor: pointer;
    margin-left: 12px;
    align-self: center;
  }
  .block-header{
    opacity: 1;
    transition: opacity 600ms;
  }
  .block-header-name{
    margin-top: 0px;
  }
  .block-wrapper:hover .block-header{
    opacity: 1;
  }
  .delete-btn{
    display: block;
    right: 8px;
    font-size: 16px;
    top: -5px;
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