<template>
    <div>
        <el-row>
            <el-col>
                <el-button-group>
                    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true"></el-button>
                    <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
                </el-button-group>
            </el-col>
        </el-row>
        <el-row class="mt20">
            <el-table :data="items" v-loading="loading" emptyText="暂无数据" class="wp100">
                <el-table-column prop="username" label="用户名" sortable>
                </el-table-column>
                <el-table-column prop="display_name" label="昵称">
                </el-table-column>
                <el-table-column prop="phone_number" label="手机号码" sortable>
                </el-table-column>
                <el-table-column prop="email" label="邮箱">
                </el-table-column>
                <el-table-column fixed="right" label="操作" width="100">
                    <div slot-scope="scope">
                        <el-button type="danger" size="mini" icon="el-icon-delete" 
                            @click="remove(scope.$index, scope.row)"></el-button>
                    </div>
                </el-table-column>
            </el-table>
        </el-row>
        <el-row class="mt20">
            <el-col :xs="24" :md="{offset:11, span:13}">
                <el-pagination 
                    @size-change="sizeChange" 
                    @current-change="pageChange" 
                    :current-page="page" 
                    :page-sizes="pageSizes" 
                    :page-size="pageSize" 
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="total">
                </el-pagination>
            </el-col>
        </el-row>
        <add-user-dialog :visiable.sync="showAddDialog" @created="add"></add-user-dialog>
    </div>
</template>

<script>
    import AddUserDialog from '../components/dialogs/AddUserDialog';
    import collection from '../mixins/collection';
    export default {
        components: {
            AddUserDialog
        },
        mixins: [collection],
        data() {
            return {
                showAddDialog: false,
                path: '/users'
            }
        }
    }
</script>

<style>

</style>