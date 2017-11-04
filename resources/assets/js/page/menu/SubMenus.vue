<template>
    <div>
        <el-table 
            :data.sync="records" 
            :show-header="false" 
            :row-class-name="tableRowClassName" 
            border :row-key="setRowKey" 
            tooltip-effect="dark" 
            emptyText="暂无数据">
            <el-table-column prop="index" label="索引" width="60">
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
            <el-table-column label="操作" width="256">
                <div slot-scope="scope">
                    <el-button type="danger" size="mini" icon="el-icon-delete" @click.native.prevent="remove(scope.$index, scope.row)"></el-button>
                    <el-button size="mini" icon="el-icon-edit" @click.native.prevent="view(scope.$index, scope.row)"></el-button>
                    <el-button size="mini" @click.native.prevent="showPermissions(scope.$index, scope.row)">管理权限</el-button>
                </div>
            </el-table-column>
        </el-table>
        <form-dialog :visiable.sync="showAddDialog" :record.sync="currentRecord" :path="path" @updated="update"></form-dialog>
    </div>
</template>

<script>
    import FormDialog from './FormDialog';

    export default {
        components: { FormDialog },
        props: ['dataSubmenus'],
        data() {
            return {
                records: this.dataSubmenus,
                showAddDialog: false,
                currentRecord: null,
                path: "/menus"
            }
        },
        methods: {
            tableRowClassName({
                row,
                rowIndex
            }) {
                // TODO: 高亮锁定的菜单
                return '';
            },
            setRowKey(row) {
                return row.id;
            },
            view(index, record) {
                this.currentRecord = record;
                this.showAddDialog = true;
                this.currentRecord._index = index;
            },
            update(record) {
                Object.assign(this.records[record._index], record);
            },
            remove(index, record) {
                this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete(`${this.path}/${record.id}`)
                    .then(response => {
                        this.$message.success('删除成功')
                        this.records.splice(index, 1);
                        this.total--;
                    }).catch(error => this.$message.error(error.data.message));
                }).catch(() => this.$message('已取消删除'));
            },
            showPermissions(index, record) {
                console.log(index, record);
            }
        }
    }
</script>

<style>
    .el-table .warning-row {
        background: oldlace;
    }
</style>