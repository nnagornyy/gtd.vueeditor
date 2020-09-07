<template>
  <vue-editor v-model="data" :editorToolbar="customToolbar"></vue-editor>
</template>

<script>
    import { VueEditor } from "vue2-editor";
    export default {
      components:{VueEditor},
      name: "vtext",
      data(){
        return {
          data:"",
          customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["code-block"]]
        }
      }
    }
</script>

