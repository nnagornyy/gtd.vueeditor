<template>
  <vue-editor v-model="editorData" :editorToolbar="customToolbar"></vue-editor>
</template>

<script>
    import { VueEditor } from "vue2-editor";
    export default {
      components:{VueEditor},
      name: "vquote",
      data(){
        return {
          editorData:"",
          customToolbar: [["bold", "underline"]]
        }
      }
    }
</script>

