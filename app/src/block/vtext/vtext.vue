<template>
  <vue-editor v-model="editorData" :editorToolbar="customToolbar"></vue-editor>
</template>

<script>
    import { VueEditor } from "vue2-editor";
    export default {
      components:{VueEditor},
      name: "vtext",
      data(){
        return {
          editorData:"",
          customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["code-block"]]
        }
      }
    }
</script>

