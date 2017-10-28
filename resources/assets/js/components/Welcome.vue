<template>
    <div class="container">
        <el-input v-model="email" placeholder="请输入邮箱"></el-input>
        <el-input type="password" v-model="password" placeholder="请输入密码"></el-input>
        <el-button @click="login">登陆</el-button>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                email: '',
                password: ''
            };
        },
        methods: {
            login() {
                let email = this.email;
                let password = this.password;

                axios.post('/login', {email, password})
                    .then(()=>{
                        window.location.reload();
                    })
                    .catch(err => {
                        this.$message.error('用户名密码不匹配!');
                    });
            }
        }
    }
</script>
