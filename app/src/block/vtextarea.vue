<template>
    <el-input type="textarea" v-model="data">
        введи в меня!
    </el-input>
</template>

<script>
    import baseField from "../baseField";
    export default {
        mixins:[baseField],
        name: "vtextarea"
    }
</script>

<style scoped>

</style>