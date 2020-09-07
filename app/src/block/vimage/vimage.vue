<template>
  <div>
    <el-upload
        class="avatar-uploader"
        action="/local/modules/gtd.vueeditor/service/upload_image.php"
        :show-file-list="false"
        :on-success="handleAvatarSuccess"
    >
      <img v-if="data.src" :src="data.src" class="avatar">
      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
    </el-upload>
  </div>
</template>

<script>
import axios from 'axios';
import draggable from 'vuedraggable'
export default {
  name: "vimage",
  components:{draggable},
  data() {
    return {
      data:{
        id: "",
        src:""
      }
    };
  },
  methods: {
    handleAvatarSuccess(res, file) {
      this.data.id = res.id;
      this.data.src = res.src;
    },
    deleteItem(i){
      let fileId = this.data.images[i].imageId;
      axios.get('/local/modules/gtd.vueeditor/service/delete_file.php?id=' + fileId)
      .then(res => {
        this.data.images.splice(i, 1);
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