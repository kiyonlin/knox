<template>
    <el-menu
            router
            :default-active="defaultActive"
            @open="handleOpen"
            @close="handleClose">
        <template v-for="menu in menus">
            <el-menu-item :index="menu.index" :key="menu.id" v-if="menu.is_leaf" :route="{path: menu.path}">
                <i :class="[menu.icon]"></i>
                <span v-text="menu.name"></span>
            </el-menu-item>

            <el-submenu :index="menu.index" :key="menu.id" v-else>
                <template slot="title">
                    <i :class="[menu.icon]"></i>
                    <span v-text="menu.name"></span>
                </template>
                <template v-for="submenu in menu.submenu">
                    <el-menu-item :index="submenu.index" :key="submenu.id" :route="{path: submenu.path}">
                        <i :class="[submenu.icon]"></i>
                        <span v-text="submenu.name"></span>
                    </el-menu-item>
                </template>
            </el-submenu>
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
