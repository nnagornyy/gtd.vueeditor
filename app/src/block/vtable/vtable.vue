<template>
  <div>
    <el-row>
      <el-col :span="20" style="position: relative">
        <div id="vtdr" @click="deleteRow">del row</div>
        <div id="vtdc">del col</div>
        <table cellpadding="0" cellspacing="" class="vtable-body">
          <tr v-for="(row, ir) in data">
            <td @blur="onEdit($event, ir, ic)" @mouseover="onHover($event, ir, ic)" contenteditable v-html="data[ir][ic]" v-for="(col, ic) in row">{{ir}} - {{ic}}</td>
          </tr>
        </table>
      </el-col>
      <el-col :span="4">
        <div class="add-column" @click="addColumn()">+</div>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <div class="add-column" @click="addRow()">+</div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "vtable",
  data() {
    return {
      data:[
        [['']]
      ],
      lastCol:0,
      lastRow:0
    };
  },
  methods: {
    onEdit(evt, r, c){
      let src = evt.target.innerHTML
      this.data[r][c] = src
    },
    onHover(evt, r, c){
      let curTarget = evt.currentTarget;
      let offsetCol = curTarget.offsetLeft + (curTarget.clientWidth / 2);
      let offsetRow = curTarget.offsetTop + (curTarget.clientHeight / 2);
      document.getElementById('vtdr').style.top = offsetRow + "px";
      document.getElementById('vtdc').style.left = offsetCol + "px";
      this.lastCol = c;
      this.lastRow = r;
    },
    addRow(){
      this.data.push(Array(this.data[0].length ? this.data[0].length : 1))
    },
    addColumn(){
      this.data.forEach(r => r.push(''))
    },
    deleteRow(){
      this.data.splice(this.lastRow, 1);
    },
    deleteCol(){}
  }

}
</script>

<style scoped>
  #vtdr{
    position: absolute;
  }
  #vtdc{
    position: absolute;
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