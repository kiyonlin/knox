<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true" v-if="canAdd"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>

        <el-table 
            :data.sync="records" :row-class-name="tableRowClassName" :row-key="setRowKey"
            v-loading="loading" border tooltip-effect="dark" emptyText="暂无数据"
            max-height="500" class="mt20">
            <el-table-column type="index"></el-table-column>
            <el-table-column prop="name" label="角色名" sortable>
            </el-table-column>
            <el-table-column prop="display_name" label="显示名称">
            </el-table-column>
            <el-table-column prop="description" label="描述" sortable>
            </el-table-column>
            <el-table-column label="操作" width="200">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" 
                        @click.native.prevent="remove(scope.$index, scope.row)"
                        v-if="canDelete"></el-button>

                    <el-button size="mini" icon="el-icon-edit" 
                        @click.native.prevent="view(scope.$index, scope.row)"
                        v-if="canView"></el-button>

                    <el-button size="mini" 
                        @click.native.prevent="showPermissions(scope.$index, scope.row)"
                        v-if="authorize('view', 'permission')">权限</el-button>
                </div>
            </el-table-column>
        </el-table>

        <el-pagination style="text-align:right;" class="mt20" @size-change="sizeChange" @current-change="pageChange" :current-page="page" :page-sizes="pageSizes" :page-size="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
        <form-dialog 
            :visiable.sync="showAddDialog" :record.sync="currentRecord" :path="path" :module="module"
            @created="add" @updated="update"></form-dialog>
        <permission-dialog :visiable.sync="showPermissionDialog" :record.sync="currentRecord"></permission-dialog>
    </div>
</template>

<script>
    import FormDialog from './FormDialog';
    import PermissionDialog from './PermissionDialog';
    import collection from '../../mixins/collection';
    export default {
        components: {
            FormDialog, PermissionDialog
        },
        mixins: [collection],
        data() {
            return {
                path: '/roles',
                module: 'role',
                showPermissionDialog: false
            }
        },

        methods: {
            tableRowClassName({row, rowIndex}) {
                if(row.name === 'system_admin') {
                    return 'warning-row';
                }
                return '';
            },
            showPermissions(index, role) {
                this.showPermissionDialog = true;
                this.currentRecord = role;
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>