export default {
    props: ['blockValue'],
    data(){
        return {
            editorData:{

            }
        }
    },
    watch: {
        editorData: {
            deep: true,
            handler(){
                this.$emit('input', this.editorData)
            }
        }
    },
    created() {
        if(this.blockValue && Object.keys(this.blockValue).length !== 0){
            this.editorData = this.blockValue
        }else if(Object.keys(this.editorData).length !== 0){
            this.$emit('input', this.editorData)
        }
    }

}