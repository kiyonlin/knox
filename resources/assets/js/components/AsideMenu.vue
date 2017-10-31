<template>
    <el-menu
            router
            :default-active="defaultActive"
            @open="handleOpen"
            @close="handleClose">
        <template v-for="menu in menus">
            <el-submenu :index="menu.index" v-if="menu.submenu.length">
                <template slot="title">
                    <i :class="[menu.icon]"></i>
                    <span v-text="menu.name"></span>
                </template>
                <template v-for="submenu in menu.submenu">
                    <el-menu-item :index="submenu.index" :route="{path: submenu.path}">
                        <i :class="[submenu.icon]"></i>
                        <span v-text="submenu.name"></span>
                    </el-menu-item>
                </template>
            </el-submenu>

            <el-menu-item :index="menu.index" v-if="!menu.submenu.length" :route="{path: menu.path}">
                <i :class="[menu.icon]"></i>
                <span v-text="menu.name"></span>
            </el-menu-item>
        </template>
    </el-menu>

</template>

<script>
    export default {
        data () {
            return {
                defaultActive: '',
                menus: []
            };
        },

        mounted () {
            this.menus = window.Auth.menus;
            this.defaultActive = this.menus.length ? this.menus[0].index : null;
        },
        methods: {
            handleOpen (key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose (key, keyPath) {
                console.log(key, keyPath);
            }
        }
    }
</script>

<style>
</style>
