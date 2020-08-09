export default {
    props: ['blockValue'],
    data(){
        return {
            data:{

            }
        }
    },
    methods: {
        foo: function () {
            console.log('foo')
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
    mounted() {
        if(this.blockValue){
            this.data = this.blockValue
        }
    },
    computed:{
        test(){
            return 'я из миксины';
        }
    }
}