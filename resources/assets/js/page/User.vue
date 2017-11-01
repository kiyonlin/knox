<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>

        <el-table :data.sync="records"
                  :row-class-name="tableRowClassName"
                  v-loading="loading"
                  border
                  :row-key="setRowKey"
                  :expand-row-keys="expandRows"
                  tooltip-effect="dark"
                  emptyText="暂无数据"
                  max-height="500"
                  class="mt20">
            <el-table-column type="index"></el-table-column>
            <el-table-column type="expand">
                <template slot-scope="props">
                    <el-tag
                            v-for="role in props.row.roles"
                            :key="role.name"
                            @close="removeRole(props.row.roles, role)"
                            closable>
                        {{role.display_name}}
                    </el-tag>
                    <el-input
                            class="input-new-tag"
                            v-if="inputVisible"
                            v-model="inputValue"
                            ref="saveTagInput"
                            size="small"
                            @keyup.enter.native="handleInputConfirm"
                            @blur="handleInputConfirm"
                    >
                    </el-input>
                    <el-button v-else class="button-new-tag" size="small" @click="showInput">添加角色</el-button>
                </template>
            </el-table-column>
            <el-table-column prop="username" label="用户名" sortable>
            </el-table-column>
            <el-table-column prop="display_name" label="昵称">
            </el-table-column>
            <el-table-column prop="phone_number" label="手机号码" sortable>
            </el-table-column>
            <el-table-column prop="email" label="邮箱" show-overflow-tooltip>
            </el-table-column>
            <el-table-column fixed="right" label="操作" width="150">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" @click.native.prevent="remove(scope.$index, scope.row)"></el-button>
                    <el-button size="mini" icon="el-icon-edit" @click.native.prevent="view(scope.$index, scope.row)"></el-button>
                </div>
            </el-table-column>
        </el-table>

        <el-pagination class="mt20" @size-change="sizeChange" @current-change="pageChange" :current-page="page" :page-sizes="pageSizes" :page-size="pageSize" layout="total, sizes, prev, pager, next" :total="total">
        </el-pagination>
        <user-dialog :visiable.sync="showAddDialog" :user.sync="currentItem" @created="add" @updated="update"></user-dialog>
    </div>
</template>

<script>
    import UserDialog from '../components/dialogs/UserDialog';
    import collection from '../mixins/collection';
    export default {
        components: {
            UserDialog
        },
        mixins: [collection],
        data() {
            return {
                records: [],
                showAddDialog: false,
                currentItem: null,
                path: '/users',
                inputVisible: false,
                inputValue: ''
            }
        },

        computed: {
            expandRows() {
                let expandRows = [];
                for(let record of this.records){
                    if(record.roles.length) {
                        expandRows.push(record.id);
                    }
                }
                return expandRows;
            }
        },

        methods: {
            view(index, item) {
                this.currentItem = item;
                this.showAddDialog = true;
                this.currentItem.index = index;
            },
            tableRowClassName({row, rowIndex}) {
                // TODO: 高亮管理员
                if(rowIndex === 0) {
                    return 'warning-row';
                }
                return '';
            },
            setRowKey(row) {
                return row.id;
            },
            removeRole(roles, role) {
                // TODO: 删除角色
                roles.splice(roles.indexOf(role), 1);
            },
            showInput() {
                this.inputVisible = true;
                this.$nextTick(_ => {
//                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },

            handleInputConfirm() {
                let inputValue = this.inputValue;
                if (inputValue) {
//                    this.dynamicTags.push(inputValue);
                }
                this.inputVisible = false;
                this.inputValue = '';
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }

    .el-table .success-row {
        background: #f0f9eb;
    }

    .el-tag + .el-tag {
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