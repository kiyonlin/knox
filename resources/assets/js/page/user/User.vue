<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>

        <el-table 
            :data.sync="records" :row-class-name="tableRowClassName" :row-key="setRowKey" :expand-row-keys="expandRows"
            v-loading="loading" border tooltip-effect="dark" emptyText="暂无数据"
            max-height="500" class="mt20">
            <el-table-column type="index"></el-table-column>
            <el-table-column type="expand">
                <template slot-scope="props">
                    <role-tags :data="props.row"></role-tags>   
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
            <el-table-column label="操作" width="128">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" 
                        @click.native.prevent="remove(scope.$index, scope.row)"></el-button>

                    <el-button size="mini" icon="el-icon-edit" 
                        @click.native.prevent="view(scope.$index, scope.row)"></el-button>
                </div>
            </el-table-column>
        </el-table>

        <el-pagination 
            style="text-align:right;" class="mt20" 
            @size-change="sizeChange" @current-change="pageChange" 
            :current-page="page" :page-sizes="pageSizes" :page-size="pageSize" :total="total"
            layout="total, sizes, prev, pager, next, jumper">
        </el-pagination>
        <form-dialog 
            :visiable.sync="showAddDialog" :record.sync="currentRecord" :path="path" 
            @created="add" @updated="update"></form-dialog>
    </div>
</template>

<script>
    import FormDialog from './FormDialog';
    import RoleTags from './RoleTags';
    import collection from '../../mixins/collection';
    export default {
        components: {
            FormDialog, RoleTags
        },
        mixins: [collection],
        data() {
            return {
                records: [],
                showAddDialog: false,
                currentRecord: null,
                path: '/users',
            }
        },

        computed: {
            expandRows() {
                let expandRows = [];
                for(let record of this.records){
                    if(record.roles && record.roles.length) {
                        expandRows.push(record.id);
                    }
                }
                return expandRows;
            }
        },

        methods: {
            view(index, record) {
                this.currentRecord = record;
                this.showAddDialog = true;
                this.currentRecord.index = index;
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
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>