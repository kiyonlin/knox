export default {
    props: ['visiable', 'record', 'path'],
    data() {
        return {
            form: {},
            show: false,
            isAdd: true,
        }
    },
    
    watch: {
        /**
         * 由父组件控制是否显示对话框
         * 需要显示时，根据是否传入旧项目判断是新增还是修改
         */
        visiable() {
            this.show = this.visiable;
            if (this.record) {
                Object.assign(this.form, this.record);
                this.isAdd = false;
            } else {
                this.isAdd = true;
                this.form = {};
            }
        },
        /**
         * 同步sync数据，根据Vue2.3以后的版本，因为是props属性，需要使用事件触发
         * 详见：https://cn.vuejs.org/v2/guide/components.html#sync-修饰符
         * 当关闭对话框时，更新父组件信息
         */
        show() {
            if(!this.show) {
                this.$emit('update:visiable', false);
                this.$emit('update:record', null);
            }
        }
    },
    computed: {
        title() {
            return this.isAdd ? '添加' : '编辑';
        }
    },
    methods: {
        add() {
            axios.post(this.path, this.form)
                .then(response => {
                    this.$emit('created', response.data);
                    this.show = false;
                    this.$message.success('添加成功');
                })
                .catch((error) => this.$message.error(error.data.message));
        },
        update() {
            let patch = this.getDirty(this.record, this.form);
            if(Object.keys(patch).length ==0) {
                this.show = false;
                this.$message.success('更新成功');
                return;
            }
            axios.patch(`${this.path}/${this.record.id}`, patch)
                .then(response => {
                    this.$emit('updated', this.form);
                    this.show = false;
                    this.$message.success('更新成功');
                })
                .catch((error) => this.$message.error(error.data.message));
        }
    }
}