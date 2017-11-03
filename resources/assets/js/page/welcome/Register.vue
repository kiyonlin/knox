<template>
    <el-form :model="form" :rules="rules" ref="form">
        <el-form-item prop="name">
            <el-input v-model="form.name" placeholder="姓名"></el-input>
        </el-form-item>
        <el-form-item prop="mobile">
            <el-input v-model="form.mobile" placeholder="手机号"></el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password" v-model="form.password" placeholder="密码"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button style="width:100%" plain type="primary" @click="register">立即注册</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: '',
                    mobile: '',
                    password: '',
                },
                rules: {
                    name: [
                        {required: true, message: '请输入姓名'},
                    ],
                    mobile: [
                        {required: true, message: '请输入手机号'}
                    ],
                    password: [
                        {required: true, message: '请输入密码'},
                        {min: 6, message: '密码至少6位'}
                    ],
                }
            };
        },
        methods: {
            register() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        alert('submit!');
                    }
                });
            }
        }
    }
</script>