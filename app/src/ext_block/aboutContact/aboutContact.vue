<template>
  <div>
      <el-row :gutter="20">
        <el-col class="block" :span="12" v-for="(row, i) in data.arValue">
          <el-row>
            <el-col class="block-title"  :span="24">
              <el-input type="text" size="mini" v-model="data.arValue[i].title"></el-input>
            </el-col>
            <el-col class="block-value"  :span="24">
              <el-input type="textarea" size="mini" v-model="data.arValue[i].value"></el-input>
            </el-col>
            <el-col :span="12">
              <el-checkbox v-model="data.arValue[i].float">обтекание</el-checkbox>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
    <el-button @click="add()">Добавить</el-button>
  </div>
</template>

<script>
    export default {
      name: "aboutContact",
      methods: {
        add: function (){
          this.data.arValue.push(
            {
              title:"",
              value: "",
              float: true
            }
          )
        }
      },
      data(){
        return{
          data:{
            arValue:[{
              title:"",
              value: "",
              float: true
            }]
          },
        }
      }
    }
</script>

<style scoped>
  .block{
    margin-bottom: 30px;
  }
  .block-title{
    margin-bottom: 10px;
  }
  .block-value{
    margin-bottom: 10px;
  }
</style>