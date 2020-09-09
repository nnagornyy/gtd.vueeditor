<template>
  <div>
    <el-row class="block-wrapper">
      <el-col class="table-wrapper" :span="20" style="position: relative">
        <div ref="delete-row" class="delete-row delete-btn" @click="deleteRow">-</div>
        <div ref="delete-col" class="delete-col delete-btn" @click="deleteCol">-</div>
        <table cellpadding="0" cellspacing="" class="vtable-body">
          <tr v-for="(row, ir) in editorData">
            <td @blur="onEdit($event, ir, ic)" @mouseover="onHover($event, ir, ic)" contenteditable v-html="editorData[ir][ic]" v-for="(col, ic) in row">{{ir}} - {{ic}}</td>
          </tr>
        </table>
      </el-col>
      <el-col class="table-right-col" :span="4">
        <div class="add-column" @click="addColumn()">+</div>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <div class="add-row" @click="addRow()">+</div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "vtable",
  data() {
    return {
      editorData:[
        ['']
      ],
      lastCol:0,
      lastRow:0
    };
  },
  methods: {
    onEdit(evt, r, c){
      let src = evt.target.innerHTML
      this.editorData[r][c] = src
    },
    onHover(evt, r, c){
      let curTarget = evt.currentTarget;
      let offsetCol = curTarget.offsetLeft + (curTarget.clientWidth / 2) - 10;
      let offsetRow = curTarget.offsetTop + (curTarget.clientHeight / 2) - 10;

      this.$refs['delete-row'].style.transform = `translate3d(0, ${offsetRow}px, 0)`;
      this.$refs['delete-col'].style.transform =`translate3d(${offsetCol}px ,0 ,0)`;

      this.lastCol = c;
      this.lastRow = r;
    },
    addRow(){
      this.editorData.push(Array(this.editorData[0].length ? this.editorData[0].length : 1))
    },
    addColumn(){
      this.editorData.forEach(r => r.push(''))
    },
    deleteRow(){
      this.editorData.splice(this.lastRow, 1);
    },
    deleteCol(){
      this.editorData.forEach(r => r.splice(this.lastCol,1));
    }
  }

}
</script>

<style scoped>
  .block-wrapper{
    display: flex;
  }
  .block-wrapper:hover .add-row,
  .block-wrapper:hover .add-column {
    display: block;
  }
  .table-wrapper:hover .delete-btn{
    display: block;
  }
  .table-right-col{
    display: flex;
    align-items: center;
  }
  .add-row{
    width: 20px;
    height: 20px;
    display: none;
    background: #8bb100;
    border-radius: 50%;
    text-align: center;
    color: #fff;
    cursor: pointer;
    margin: auto;
    font-size: 20px;
    line-height: 20px;
    margin-top: 12px;
    right: auto;
    transition: transform .3s ease;
  }
  .add-column{
    width: 20px;
    height: 20px;
    background: #8bb100;
    border-radius: 50%;
    text-align: center;
    display: none;
    color: #fff;
    cursor: pointer;
    font-size: 20px;
    line-height: 20px;
    margin-left: 20px;
    right: auto;
    transition: transform .3s ease;
  }
  .delete-btn{
    width: 20px;
    height: 20px;
    background: #d22525;
    border-radius: 50%;
    text-align: center;
    color: #fff;
    font-size: 30px;
    line-height: 15px;
    display: none;
    right: auto;
    transition: transform .3s ease;
  }
  .delete-row{
    position: absolute;
    left: -9px;
  }
  .delete-col{
    position: absolute;
    top: -9px;
  }
  .vtable-body{
    border: 1px solid #ebeef5;
    padding: 5px;
    min-width: 15px;
    border-collapse: collapse;
    width: 100%;
  }
  .vtable-body tr{}
  .vtable-body tr td{
    border: 1px solid #f3f3f3;
    padding: 5px;
  }
</style>