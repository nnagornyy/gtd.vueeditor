<template>
    <div>
        <input type="text" v-model="data.value">
        <input type="text" v-model="data.description">
    </div>
</template>

<script>
    export default {
        name: "vtext",
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