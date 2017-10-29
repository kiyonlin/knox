<template>
    <el-row>
        <el-col>
            <el-menu
                    :default-active="defaultActive"
                    @open="handleOpen"
                    @close="handleClose">
                <el-submenu index="1">
                    <template slot="title">
                        <i class="el-icon-location"></i>
                        <span>导航一</span>
                    </template>
                    <el-menu-item index="1-1">选项1</el-menu-item>
                    <el-menu-item index="1-2">选项2</el-menu-item>
                    <el-menu-item index="1-3">选项3</el-menu-item>
                </el-submenu>
                <el-submenu index="2">
                    <template slot="title">
                        <i class="el-icon-menu"></i>
                        <span>导航二</span>
                    </template>
                    <el-menu-item index="2-1">
                        <i class="el-icon-menu"></i>选项11
                    </el-menu-item>
                    <el-menu-item index="2-2">
                        <i class="el-icon-success"></i>选项22
                    </el-menu-item>
                </el-submenu>
                <el-menu-item index="3">
                    <i class="el-icon-setting"></i>
                    <span slot="title">导航三</span>
                </el-menu-item>
            </el-menu>
        </el-col>
    </el-row>

</template>

<script>
    export default {
        data() {
            return {
                defaultActive: '1',
                menus: [
                    {
                        'menu1':{
                            'name': '菜单一'
                        }
                    }
                ]
            };
        },

        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            handleOpen(key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            }
        }
    }
</script>

<style>
</style>
