<template>
    <el-menu 
        router 
        ref="asideMenu"
        :default-active="defaultActive">
        <template v-for="_module in modules">
                <el-menu-item :index="_module.path" :key="_module.id" v-if="_module.is_leaf">
                    <i :class="[_module.icon]"></i>
                    <span v-text="_module.name"></span>
                </el-menu-item>

                <el-submenu :index="_module.index" :key="_module.id" v-else>
                    <template slot="title">
                        <i :class="[_module.icon]"></i>
                        <span v-text="_module.name"></span>
                    </template>
                    <template v-for="submodule in _module.submodules">
                        <el-menu-item :index="submodule.path" :key="submodule.id">
                            <i :class="[submodule.icon]"></i>
                            <span v-text="submodule.name"></span>
                        </el-menu-item>
                    </template>
                </el-submenu>
        </template>
    </el-menu>
</template>

<script>
    import {mapMutations} from 'vuex';
    import * as M from '../../store/mutation-consts';

    export default {
        data() {
            return {
                modules: [],
                defaultActive: ''
            };
        },
        mounted() {
            this.modules = window.Auth.modules;
            this.openDefaultModule();
        },
        watch: {
            $route() {
                this.openDefaultModule();
            }
        },
        methods: {
            openDefaultModule() {
                for(let module of this.modules) {
                    if(module.submodules.length) {
                        for(let submodule of module.submodules) {
                            if(submodule.path == this.$router.currentRoute.path) {
                                return this.$nextTick(_ => {
                                    this.$refs.asideMenu.open(module.index);
                                    this.defaultActive = submodule.path;
                                    this[M.CHANGE_PATH]({name: submodule.name, data:submodule.path});
                                });
                            }
                        }
                    } else {
                        if(module.path == this.$router.currentRoute.path) {
                            return this.$nextTick(_ => {
                                this.defaultActive = module.path;
                                this.$store.commit(M.CHANGE_PATH, {name: module.name, data:module.path})
                            });
                        }
                    }
                }
            },
            ...mapMutations([
                M.CHANGE_PATH
            ])
        }
    }
</script>

<style>

</style>
