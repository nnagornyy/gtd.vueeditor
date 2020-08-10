export default {
    props: ['blockValue'],
    data(){
        return {
            data:{

            }
        }
    },
    watch: {
        data: {
            deep: true,
            handler(){
                this.$emit('input', this.data)
            }
        }
    },
    created() {
        if(this.blockValue && Object.keys(this.blockValue).length !== 0){
            this.data = this.blockValue
        }
    }
}