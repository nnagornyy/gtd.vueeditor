<template>
    <el-row :gutter="20">
      <el-col :span="18">
        <el-input v-model="data.value"></el-input>
      </el-col>
      <el-col :span="6" v-if="selectSize">
        <el-select v-model="data.type" placeholder="Select">
          <el-option
              v-for="type in headerType"
              :key="type"
              :label="type"
              :value="type">
          </el-option>
        </el-select>
      </el-col>
    </el-row>
</template>

<script>
    export default {
      name: "vheader",
      props: {
        selectSize: {
          type: Boolean,
          default: true
        }
      },
      data(){
        return{
          data:{
              type:"",
              value:"",
          },
          headerType:['h1', 'h2', 'h3', 'h4', 'h5']
        }
      }
    }
</script>

<style scoped>

</style>