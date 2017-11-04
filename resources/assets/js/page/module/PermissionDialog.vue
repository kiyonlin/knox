<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false" width="35%">
        <el-form label-width="80px" :model="form">
            <el-form-item label="已有权限">
                <el-tag v-for="(perm, index) in perms" :key="perm.id" @close="remove(index)" closable>
                    {{perm.display_name}}
                </el-tag>
            </el-form-item>
            <el-form-item label="权限名">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="显示名称">
                <el-input v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="描述">
                <el-input v-model="form.description"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button @click="add">添加</el-button>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button @click="show = false">取 消</el-button>
            <el-button type="primary" @click="show = false">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['visiable', 'record', 'path'],
        data() {
            return {
                perms: [],
                show: false,
                title: '',
                form: {}
            }
        },
        
        watch: {
            /**
             * 由父组件控制是否显示对话框
             * 需要显示时，根据是否传入旧项目判断是新增还是修改
             */
            visiable() {
                if(this.visiable) {
                    this.show = this.visiable;
                    this.perms = this.record.perms;
                    this.perms.push(...this.record.perms);
                    this.title = `查看【${this.record.name}】权限`;
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
        methods: {
            add(){
                axios.post(`/modules/${this.record}/permissions`, this.form)
                    .then(response => {
                        this.perms.push(response.data);
                        this.$message.success('添加成功');
                    })
                    .catch(error => this.$message.error(error.data.message));
            },
            remove(index) {
                // TODO: 调用接口删除权限
                this.perms.splice(index, 1);
            },
        }
    }
</script>

<style>
    .el-tag+.el-tag {
        margin-left: 10px;
    }
</style>
