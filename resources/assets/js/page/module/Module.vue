<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true" v-if="canAdd"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>
        <el-table 
            :data.sync="records" :row-class-name="tableRowClassName" :row-key="setRowKey"
            max-height="500" class="mt20"
            v-loading="loading" border tooltip-effect="dark">
            <el-table-column prop="index" label="索引" width="50">
            </el-table-column>
            <el-table-column type="expand" width="60">
                <template slot-scope="props">
                    <submodules 
                    :data-submodules="props.row.sub_modules"></submodules>
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
            <el-table-column label="icon">
                <div slot-scope="scope">
                    <i :class="[scope.row.icon]"></i>
                </div>
            </el-table-column>
            <el-table-column prop="sort" label="排序">
            </el-table-column>
            <el-table-column label="子模块操作">
                <div slot-scope="scope">
                    <span v-text="scope.row.sub_modules.length + '个'"></span>
                </div>
            </el-table-column>
            <el-table-column label="操作" width="128">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" 
                        @click.native.prevent="checkRemove(scope.$index, scope.row)"
                        v-if="canDelete"></el-button>

                    <el-button size="mini" icon="el-icon-edit" 
                        @click.native.prevent="view(scope.$index, scope.row)"
                        v-if="canView"></el-button>
                </div>
            </el-table-column>
        </el-table>
        <el-pagination style="text-align:right;" class="mt20" 
            @size-change="sizeChange" @current-change="pageChange" 
            :current-page="page" :page-sizes="pageSizes" :page-size="pageSize" :total="total"
            layout="total, sizes, prev, pager, next, jumper">
        </el-pagination>
        <form-dialog 
            :visiable.sync="showAddDialog" :record.sync="currentRecord" :path="path" :module="module"
            @created="add" @updated="update"></form-dialog>
    </div>
</template>

<script>
    import FormDialog from './FormDialog';
    import Submodules from './Submodules';
    import collection from '../../mixins/collection';
    export default {
        components: {
            FormDialog, Submodules
        },
        mixins: [collection],
        data() {
            return {
                module: 'module',
                path: '/modules'
            }
        },
        computed: {
        },
        methods: {
            tableRowClassName({row, rowIndex}) {
                // TODO: 高亮锁定的模块
                if(row.key === 'system') {
                    return 'warning-row';
                }
                return '';
            },
            checkRemove(index, record) {
                if(record.sub_modules.length){
                    this.$message.error("该模块包含子模块，无法直接删除！");
                    return;
                }

                this.remove(index, record);
            },
            /**
             * 重写add函数，当添加的是子模块时，加到所属的父模块中
             */
            add(record) {
                if(!record.pid) {
                    this.records.push(record);
                    return;
                }
                // 添加到父模块中
                this.records.map(item => {
                    if(item.id == record.pid) {
                        item.sub_modules.push(record);
                    }
                });
            },
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>