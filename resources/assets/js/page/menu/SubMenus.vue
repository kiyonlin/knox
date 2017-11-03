<template>
    <el-table 
        :data.sync="records" 
        :show-header="false" 
        :row-class-name="tableRowClassName" 
        border :row-key="setRowKey" 
        tooltip-effect="dark" 
        emptyText="暂无数据">
        <el-table-column prop="index" label="索引" width="50">
        </el-table-column>
        <el-table-column prop="key" label="标识">
        </el-table-column>
        <el-table-column prop="name" label="名称">
        </el-table-column>
        <el-table-column prop="path" label="路径">
        </el-table-column>
        <el-table-column prop="level" label="层级">
        </el-table-column>
        <el-table-column prop="icon" label="icon">
        </el-table-column>
        <el-table-column prop="sort" label="排序">
        </el-table-column>
        <el-table-column label="操作" width="256">
            <div slot-scope="scope">
                <!-- <el-button type="danger" size="mini" icon="el-icon-delete" @click.native.prevent="remove(scope.$index, scope.row)"></el-button> -->
                <el-button size="mini" icon="el-icon-edit" @click.native.prevent="view(scope.row)"></el-button>
                <!-- <el-button size="mini" @click.native.prevent="showPermissions(scope.$index, scope.row)">管理权限</el-button> -->
            </div>
        </el-table-column>
    </el-table>
</template>

<script>
    export default {
        props: ['dataSubmenus', 'dataIndex'],
        data() {
            return {
                records: this.dataSubmenus
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
            view(record) {
                this.$emit('showSubmenu', this.dataIndex, record);
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