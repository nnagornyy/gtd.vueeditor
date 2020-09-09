<template>
  <div>
    <el-upload
        class="avatar-uploader"
        action="/local/modules/gtd.vueeditor/service/upload_image.php"
        :show-file-list="false"
        :on-success="handleAvatarSuccess"
    >
      <img v-if="editorData.src" :src="editorData.src" class="avatar">
      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
    </el-upload>
  </div>
</template>

<script>
import axios from 'axios';
import draggable from 'vuedraggable'
export default {
  props:['value'],
  name: "vimage",
  components:{draggable},
  data() {
    return {
      editorData:{
        id: 0,
        src:""
      }
    };
  },
  watch:{
    value(){
      this.editorData = this.value;
    }
  },
  methods: {
    handleAvatarSuccess(res, file) {
      this.editorData.id = res.id;
      this.editorData.src = res.src;
    },
    deleteItem(i){
      let fileId = this.editorData.images[i].imageId;
      axios.get('/local/modules/gtd.vueeditor/service/delete_file.php?id=' + fileId)
      .then(res => {
        this.editorData.images.splice(i, 1);
      })
    }
  }

}
</script>

<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
</style>