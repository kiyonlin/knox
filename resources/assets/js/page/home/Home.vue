<template>
    <el-container>
        <el-header>
            <nav-bar></nav-bar>
        </el-header>
        <el-container class="mh20">
            <el-aside class="hidden-xs-only">
                <aside-menu></aside-menu>
            </el-aside>
            <el-container>
                <el-main>
                    <el-breadcrumb separator="/">
                    <el-breadcrumb-item to="/">首页</el-breadcrumb-item>
                    <el-breadcrumb-item 
                        v-for="(path, index) in paths" 
                        :key="index">
                        {{path.name}}
                    </el-breadcrumb-item>
                    </el-breadcrumb>
                    <router-view class="mt30"></router-view>
                </el-main>

                <el-footer>
                    <copyright></copyright>
                </el-footer>
            </el-container>
        </el-container>
    </el-container>
</template>

<script>
    import AsideMenu from './AsideMenu.vue';
    import NavBar from './NavBar.vue';
    import Copyright from './Copyright.vue';
    import {mapState} from 'vuex';

    export default {
        components: {AsideMenu, NavBar, Copyright},

        data () {
            return {
                // form: new Form({body: ''})
            }
        },
        computed: {
            paths() {
                return this.$store.state.breadcrumb.paths;
            }
        },
        mounted () {
            // console.log('Component mounted.')
        }
    }
</script>

<style>
    .aside {
        width: 255px;
    }
</style>