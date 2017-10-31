<template>
        <el-row type="flex" justify="center" class="welcome">
            <el-tabs v-model="activeName" tab-position="right">
                <el-tab-pane label="注册" name="register">
                    <register></register>
                </el-tab-pane>
                <el-tab-pane label="登录" name="login">
                    <login></login>
                </el-tab-pane>
            </el-tabs>
        </el-row>
</template>

<script>
    import Register from '../components/Register.vue';
    import Login from '../components/Login.vue';

    export default {
        components: {Register, Login},

        data() {
            return {
                activeName: 'register',
            };
        }
    }
</script>
<style>
    .welcome {
        margin-top: 100px;
        font-family: Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
    }
</style>