<template>
  <div>
    <youtube :fit-parent="true" :resize="true" :video-id="editorData.videoId" ref="youtube"></youtube>
    <el-row :gutter="20" style="margin-top: 20px">
      <el-col :span="20">
        <el-input v-model="url"></el-input>
      </el-col>
      <el-col :span="4">
        <el-button @click="setIdFromUrl">ОК</el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
    export default {
      name: "youtubeVideo",
      methods:{
        setIdFromUrl(){
          this.editorData.videoId = this.$youtube.getIdFromUrl(this.url);
        }
      },
      data(){
        return {
          editorData:{
            videoId: "",
          },
          url:"",
        }
      },

    }
</script>

