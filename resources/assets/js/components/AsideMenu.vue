<template>
    <el-menu 
        router 
        ref="asideMenu"
        :default-active="defaultActive">
        <template v-for="menu in menus">
                <el-menu-item :index="menu.path" :key="menu.id" v-if="menu.is_leaf">
                    <i :class="[menu.icon]"></i>
                    <span v-text="menu.name + defaultActive"></span>
                </el-menu-item>

                <el-submenu :index="menu.index" :key="menu.id" v-else>
                    <template slot="title">
                        <i :class="[menu.icon]"></i>
                        <span v-text="menu.name + defaultActive"></span>
                    </template>
                    <template v-for="submenu in menu.submenus">
                        <el-menu-item :index="submenu.path" :key="submenu.id">
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
        data() {
            return {
                menus: [],
                defaultActive: ''
            };
        },
        mounted() {
            this.menus = window.Auth.menus;
            this.openSubMenu();
        },
        watch: {
            $route() {
                this.openSubMenu();
            }
        },
        methods: {
            openSubMenu() {
                for(let menu of this.menus) {
                    if(menu.submenus) {
                        for(let submenu of menu.submenus) {
                            if(submenu.path == this.$router.currentRoute.path) {
                                console.log(this.$refs.asideMenu);
                                this.$nextTick(_ => {
                                    this.$refs.asideMenu.open(menu.index);
                                    this.defaultActive = submenu.path;
                                });
                                return;
                            }
                        }
                    } else {
                        if(menu.path == this.$router.currentRoute.path) {
                            this.defaultActive = menu.path;
                            return;
                        }
                    }
                }
            }
        }
    }
</script>

<style>

</style>
