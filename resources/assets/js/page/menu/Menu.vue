<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>
        <el-table :data.sync="records" :row-class-name="tableRowClassName" v-loading="loading" border :row-key="setRowKey" tooltip-effect="dark" emptyText="暂无数据" max-height="500" class="mt20">
            <el-table-column prop="index" label="索引" width="50">
            </el-table-column>
            <el-table-column type="expand" width="60">
                <template slot-scope="props">
                    <submenus 
                    :data-submenus="props.row.sub_menus"></submenus>
                </template>
            </el-table-column>
            <el-table-column prop="key" label="标识">
            </el-table-column>
            <el-table-column prop="name" label="名称">
            </el-table-column>
            <el-table-column prop="path" label="路径">
            </el-table-column>
            <el-table-column prop="level" label="层级" width="50">
            </el-table-column>
            <el-table-column prop="icon" label="icon">
            </el-table-column>
            <el-table-column prop="sort" label="排序">
            </el-table-column>
            <el-table-column prop="sub_menus.length" label="子菜单操作">
            </el-table-column>
            <el-table-column label="操作" width="128">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" @click.native.prevent="checkRemove(scope.$index, scope.row)"></el-button>
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
    import Submenus from './Submenus';
    import collection from '../../mixins/collection';
    export default {
        components: {
            FormDialog, Submenus
        },
        mixins: [collection],
        data() {
            return {
                path: '/menus'
            }
        },
        computed: {
        },
        methods: {
            tableRowClassName({row, rowIndex}) {
                // TODO: 高亮锁定的菜单
                return '';
            },
            checkRemove(index, record) {
                if(record.sub_menus.length){
                    this.$message.error("该菜单包含子菜单，无法直接删除！");
                    return;
                }

                this.remove(index, record);
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>