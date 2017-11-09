<template>
    <div>
        <template v-for="(role, index) in items">
            <el-tooltip 
                :content="role.description" :key="role.name" 
                effect="dark" placement="bottom">
                <el-tag 
                    :key="role.name" 
                    @close="remove(index, role)" :closable="authorize('update', 'user')">
                        {{role.display_name}}
                </el-tag>
            </el-tooltip>
        </template>
        <el-autocomplete 
            class="input-new-tag" popper-class="autocomplete" placeholder="添加角色" size="small" 
            v-if="authorize('update', 'user')" 
            v-model="item"
            valueKey="display_name" :fetch-suggestions="fetchRoles" @select="update">
            <i class="el-icon-edit el-input__icon" slot="suffix"></i>
            <template slot-scope="props">
                <div class="name">{{ props.item.display_name }}</div>
                <span class="description">{{ props.item.description }}</span>
            </template>
        </el-autocomplete>
        <el-tag type="warning" closable v-if="feedback" @close="feedback = ''">{{ feedback }}</el-tag>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                items: this.data.roles,
                item: '',
                feedback: ''
            };
        },
        methods: {
            fetchRoles(query, callback) {
                axios.get(`users/${this.data.id}/roles?query=${query}`)
                    .then(response => {
                        if(! response.data.length) {
                            this.feedback = '没有可选的角色';
                            setTimeout(_ => this.feedback = '', 3333);
                        } else {
                            this.feedback = '';
                        }
                        callback(response.data);
                    });
            },
            update(role) {
                axios.put(`users/${this.data.id}/roles/${role.id}`)
                    .then(_ => {
                        this.items.push(role);
                        this.$message.success("添加成功");
                        this.item = '';
                    });
            },
            remove(index, role) {
                this.deleteConfirm(_ => 
                    axios.delete(`users/${this.data.id}/roles/${role.id}`)
                    .then(_ => {
                        this.items.splice(index, 1);
                        this.$message.success("删除成功");
                    })
                );
            }
        }
    }
</script>

<style lang="scss" scoped>
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
        width: 120px;
        margin-left: 10px;
        vertical-align: bottom;
    }
    .autocomplete {
        li {
            line-height: normal;
            padding: 7px;

            .name {
                text-overflow: ellipsis;
                overflow: hidden;
            }
            .description {
                font-size: 12px;
                color: #b4b4b4;
            }

            .highlighted .description {
                color: #ddd;
            }
        }
    }
</style>
