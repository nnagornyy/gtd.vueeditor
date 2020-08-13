<template>
  <div>
    <el-row :gutter="20">
      <draggable v-model="data" group="gallery" @start="drag=true" @end="drag=false">
        <el-col :span="8" v-for="(item, i) in data">
          <el-card class="image-block" shadow="hover">
            <div class="image-wrapper">
              <div class="image-hover"><i @click="deleteItem(i)" class="el-icon-delete"></i></div>
            </div>
            <el-image
                style="width: 100%; height: 100%"
                :src="item.src"
                fit="cover">
            </el-image>
            <el-input placeholder="Название" v-model="data[i].title"></el-input>
            <el-input placeholder="Описание" v-model="data[i].description"></el-input>
          </el-card>
        </el-col>
      </draggable>
      <el-col :span="8">
        <el-upload
            action="/local/modules/gtd.vueeditor/service/upload_image.php"
            list-type="picture-card"
            :on-success="addItem"
            :show-file-list="false"
        >
          <i class="el-icon-plus"></i>
        </el-upload>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
  name: "gallery",
  components:{draggable},
  data() {
    return {
      data:[],
    };
  },
  methods: {
    addItem(res, file, fileList){
      let item = {
        imageId: res.id,
        src: res.src,
        title: "",
        description: ""
      };
      this.data.push(item);
    },
    deleteItem(i){
      this.data.splice(i, 1);
    }
  }

}
</script>

<style scoped>
  .image-block{
    margin-bottom: 20px;
  }
</style>