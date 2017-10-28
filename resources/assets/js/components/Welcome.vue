<template>
    <el-row type="flex" justify="center" class="welcome">
        <el-tabs v-model="activeName" tab-position="right">
            <el-tab-pane label="注册" name="register">
                <el-form :model="ruleForm" :rules="rules" ref="registerForm">
                    <el-form-item prop="name">
                        <el-input v-model="ruleForm.name" placeholder="姓名"></el-input>
                    </el-form-item>
                    <el-form-item prop="mobile">
                        <el-input v-model="ruleForm.mobile" placeholder="手机号"></el-input>
                    </el-form-item>
                    <el-form-item prop="delivery">
                        <el-input type="password" v-model="ruleForm.password" placeholder="密码"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="width:100%" plain type="primary" @click="submitForm('registerForm')">立即注册</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="登录" name="second">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm">
                    <el-form-item prop="mobile">
                        <el-input v-model="ruleForm.mobile" placeholder="手机号"></el-input>
                    </el-form-item>
                    <el-form-item prop="password">
                        <el-input type="password" v-model="ruleForm.password" placeholder="密码"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="width:100%" plain type="primary" @click="submitForm('ruleForm')">登录</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </el-row>
</template>

<script>
    export default {

        data() {
            return {
                activeName: 'register',
                ruleForm: {
                    name: '',
                    mobile: '',
                    password: '',
                },
                rules: {
                    name: [
                        { required: true, message: '请输入姓名', trigger: 'blur' },
                    ],
                    mobile: [
                        { type: '', required: true, message: '请输入手机号', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' }
                    ],
                }
            };
        },
        methods: {
            login() {
                let email = this.email;
                let password = this.password;

                axios.post('/login', {email, password})
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(err => {
                        this.$message.error('用户名密码不匹配!');
                    });
            },
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            }
        }
    }
</script>
<style>
    .welcome {
        margin-top: 100px;
        font-family: Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    }
</style>