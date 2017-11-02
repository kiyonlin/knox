<template>
    <div>
        <el-tag v-for="(role, index) in items" :key="role.name" @close="remove(index)" closable>
            {{role.display_name}}
        </el-tag>
        <el-input v-if="inputVisible" class="input-new-tag" ref="saveTagInput" v-model="item" size="small" @keyup.enter.native="add" @blur="add">
        </el-input>
        <el-button v-else class="button-new-tag" size="small" @click="showInput">添加角色</el-button>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                items: this.data.roles,
                inputVisible: false,
                item: ''
            };
        },
        methods: {
            remove(index) {
                // TODO: 调用接口删除角色
                this.items.splice(index, 1);
            },
            showInput() {
                this.inputVisible = true;
                this.$nextTick(_ => {
                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },
            add() {
                let item = this.item;
                if (item) {
                    let randomRole = {};
                    Object.assign(randomRole, this.items[0]);
                    randomRole.display_name = item;
                    randomRole.name = item;
                    this.items.push(randomRole);
                    console.log(this.items);
                }
                this.inputVisible = false;
                this.item = '';
            }
        }
    }
</script>

<style>
    .el-tag+.el-tag {
        margin-left: 10px;
    }
    .button-new-tag {
        margin-left: 10px;
        height: 32px;
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
    }
    .input-new-tag {
        width: 90px;
        margin-left: 10px;
        vertical-align: bottom;
    }
</style>
