<template>
    <el-dialog title="添加用户" :visible.sync="show">
        <el-form label-width="80px" :model="form">
            <el-form-item label="用户名">
                <el-input v-model="form.username"></el-input>
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
            <el-form-item label="初始密码">
                <el-input type="password" v-model="form.password"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button @click="show = false">取 消</el-button>
            <el-button type="primary" @click="add">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['visiable'],
        data() {
            return {
                form: {
                    username: '',
                    display_name: '',
                    phone_number: '',
                    email: '',
                    password: ''
                },
                show: false
            }
        },
        watch: {
            visiable() {
                this.show = this.visiable;
                this.form = {};
            },
            show() {
                // 同步visiable数据，因为是props属性，需要使用事件触发
                this.show ? 0 : this.$emit('update:visiable', false);
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
            }
        }
    }
</script>
