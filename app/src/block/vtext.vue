<template>
    <div>
        <input type="text" v-model="data.value">
        <input type="text" v-model="data.description">
    </div>
</template>

<script>
    import baseField from "../baseField";
    export default {
        name: "vtext",
        mixins:[baseField],
        data(){
            return{
                data:{
                    description:"",
                    value:"",
                }
            }
        }
    }
</script>

<style scoped>

</style>