<template>
    <el-dialog title="添加用户" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form">
            <el-form-item label="用户名">
                <el-input v-model="form.username" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="名称">
                <el-input v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="手机号码">
                <el-input v-model="form.phone_number"></el-input>
            </el-form-item>
            <el-form-item label="邮箱">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="初始密码" v-if="isAdd">
                <el-input type="password" v-model="form.password"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button @click="show = false">取 消</el-button>
            <el-button type="primary" @click="add" v-if="isAdd">添 加</el-button>
            <el-button type="primary" @click="update" v-else>更 新</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['visiable', 'user'],
        data() {
            return {
                form: {},
                show: false,
                isAdd: true
            }
        },
        watch: {
            /**
             * 由父组件控制是否显示对话框
             * 需要显示时，根据是否传入旧项目判断是新增还是修改
             */
            visiable() {
                this.show = this.visiable;
                if (this.user) {
                    Object.assign(this.form, this.user);
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
                    this.$emit('update:user', null);
                }
            }
        },
        methods: {
            add() {
                axios.post('users', this.form)
                    .then(response => {
                        this.$emit('created', response.data);
                        this.show = false;
                        this.$message.success('添加成功');
                    })
                    .catch((error) => this.$message.error(error.data.message));
            },
            update() {
                let patch = this.getDirty(this.user, this.form);
                
                axios.patch(`users/${this.user.id}`, patch)
                    .then(response => {
                        this.$emit('updated', this.form);
                        this.show = false;
                        this.$message.success('更新成功');
                    })
                    .catch((error) => this.$message.error(error.data.message));
            }
        }
    }
</script>
