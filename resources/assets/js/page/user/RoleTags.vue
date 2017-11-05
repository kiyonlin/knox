<template>
    <div>
        <el-tag 
            v-for="(role, index) in items" 
            :key="role.name" 
            @close="remove(index)" closable>
            {{role.display_name}}
        </el-tag>
        <el-autocomplete 
            v-if="inputVisible" 
            class="input-new-tag" 
            ref="saveTagInput" 
            v-model="item" 
            size="small" 
            placeholder="请选择角色"
            :fetch-suggestions="querySearch"
            @select="handleSelect"
            @keyup.esc.native="cancel">
        </el-autocomplete>
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
                item: '',
                optionalRoles: []
            };
        },
        methods: {
            showInput() {
                this.inputVisible = true;
                this.fetchRoles();
                this.$nextTick(_ => {
                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },
            querySearch(queryString, callback) {
                let results = queryString 
                    ? this.optionalRoles.filter(this.createFilter(queryString)) 
                    : this.optionalRoles;
                // 调用 callback 返回建议列表的数据
                callback(results);
            },
            createFilter(queryString) {
                return (roles) => {
                    return (roles.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
                };
            },
            handleSelect(role) {
                // 调用接口附加角色
                this.items.push(role);
                this.$message.success("添加成功");
                this.inputVisible = false;
                this.optionalRoles = [];
            },
            fetchRoles() {
                axios.get(`users/${this.data.id}/roles`)
                    .then(response => this.optionalRoles = response.data);
            },
            cancel() {
                this.inputVisible = false;
            },
            remove(index) {
                // TODO: 调用接口删除角色
                this.items.splice(index, 1);
            },
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
        margin-left: 10px;
        vertical-align: bottom;
    }
</style>
