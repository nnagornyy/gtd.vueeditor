<template>
  <div>
    <el-select v-if="selectType" v-model="data.viewType" placeholder="Select">
      <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value">
      </el-option>
    </el-select>
    <el-row :gutter="20">
      <draggable v-model="data.images" group="gallery" @start="drag=true" @end="drag=false">
        <el-col :span="8" v-for="(item, i) in data.images">
          <el-card class="image-block" shadow="hover">
            <div class="image-wrapper">
              <div class="image-hover"><i @click="deleteItem(i)" class="el-icon-delete"></i></div>
            </div>
            <el-image
                style="width: 100%; height: 100%"
                :src="item.src"
                fit="cover">
            </el-image>
            <el-input v-if="showImageHeader" placeholder="Название" v-model="data.images[i].title"></el-input>
            <el-input v-if="showImageDescription" placeholder="Описание" v-model="data.images[i].description"></el-input>
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
import axios from 'axios';
import draggable from 'vuedraggable'
export default {
  name: "gallery",
  components:{draggable},
  props:{
    selectType: {
      type: Boolean,
      default: true
    },
    showImageHeader:{
      type: Boolean,
      default: true
    },
    showImageDescription:{
      type: Boolean,
      default: true
    },
  },
  data() {
    return {
      data:{
        viewType:"",
        images:[]
      },
      options:[
        {
          label: 'Плитка',
          value: 'tile'
        },
        {
          label: 'Слайдер 1',
          value: 'slider'
        }
      ]
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
      this.data.images.push(item);
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

<style scoped>
  .image-block{
    margin-bottom: 20px;
  }
</style>