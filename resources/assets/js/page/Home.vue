<template>
    <el-container>
        <el-header style="text-align: right; font-size: 12px">
            <nav-bar></nav-bar>
        </el-header>
        <el-container style="margin: 0 20px;">
            <el-aside class="hidden-xs-only">
                <aside-menu></aside-menu>
            </el-aside>
            <el-container>
                <el-main>
                    <el-breadcrumb separator="/">
                        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                        <el-breadcrumb-item>活动管理</el-breadcrumb-item>
                        <el-breadcrumb-item>活动列表</el-breadcrumb-item>
                        <el-breadcrumb-item>活动详情</el-breadcrumb-item>
                    </el-breadcrumb>
                    <router-view></router-view>
                </el-main>

                <el-footer>
                    <copyright></copyright>
                </el-footer>
            </el-container>
        </el-container>
    </el-container>
</template>

<script>
    import AsideMenu from '../components/AsideMenu.vue';
    import NavBar from '../components/NavBar.vue';
    import Copyright from '../components/Copyright.vue';

    export default {
        components: {AsideMenu, NavBar, Copyright},

        data () {
            return {
                form: new Form({body: ''})
            }
        },

        mounted () {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .aside {
        width: 255px;
    }
</style>