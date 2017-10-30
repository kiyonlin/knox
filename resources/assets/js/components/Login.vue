<template>
    <el-form :model="form" :rules="rules" ref="form">
        <el-form-item prop="username">
            <el-input v-model="form.username" placeholder="手机号/用户名/邮箱"></el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password" v-model="form.password" placeholder="密码"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button style="width:100%" plain type="primary" @click="login">登录</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    username: '',
                    password: '',
                },
                rules: {
                    username: [
                        {required: true, message: '请输入手机号/用户名/邮箱'}
                    ],
                    password: [
                        {required: true, message: '请输入密码'},
                        {min: 6, message: '密码至少6位'}
                    ],
                }
            };
        },
        methods: {
            login() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        let username = this.form.username;
                        let password = this.form.password;

                        axios.post('/login', {username, password})
                            .then(() => {
                                window.location.reload();
                            })
                            .catch(() => {
                                this.$message.error('用户名密码不匹配!');
                            });
                    }
                });
            }
        }
    }
</script>