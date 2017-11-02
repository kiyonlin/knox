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
                    <!-- <role-tags :data="props.row"></role-tags>    -->
                </template>
            </el-table-column>
            <el-table-column prop="name" label="角色名" sortable>
            </el-table-column>
            <el-table-column prop="display_name" label="显示名称">
            </el-table-column>
            <el-table-column prop="description" label="描述" sortable>
            </el-table-column>
            <el-table-column fixed="right" label="操作" width="150">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" @click.native.prevent="remove(scope.$index, scope.row)"></el-button>
                    <el-button size="mini" icon="el-icon-edit" @click.native.prevent="view(scope.$index, scope.row)"></el-button>
                </div>
            </el-table-column>
        </el-table>

        <el-pagination style="text-align:right;" class="mt20" @size-change="sizeChange" @current-change="pageChange" :current-page="page" :page-sizes="pageSizes" :page-size="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
        <form-dialog :visiable.sync="showAddDialog" :record.sync="currentRecord" :path="path" @created="add" @updated="update"></form-dialog>
    </div>
</template>

<script>
    import FormDialog from './FormDialog';
    // import RoleTags from './RoleTags';
    import collection from '../../mixins/collection';
    export default {
        components: {
            FormDialog
        },
        mixins: [collection],
        data() {
            return {
                path: '/roles',
            }
        },

        computed: {
            expandRows() {
                let expandRows = [];
                for(let record of this.records){
                    // if(record.roles && record.roles.length) {
                    //     expandRows.push(record.id);
                    // }
                }
                return expandRows;
            }
        },

        methods: {
            tableRowClassName({row, rowIndex}) {
                // TODO: 高亮管理员
                if(row.name === 'systemAdmin') {
                    return 'warning-row';
                }
                return '';
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>