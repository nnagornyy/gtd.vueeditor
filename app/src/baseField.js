export default {
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
    computed:{
        test(){
            return 'я из миксины';
        }
    }
}