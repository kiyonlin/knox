<template>
    <div>
        <el-button-group>
            <el-button type="primary" icon="el-icon-circle-plus-outline" @click="showAddDialog = true"></el-button>
            <el-button type="primary" icon="el-icon-refresh" @click="fetch" :loading="loading"></el-button>
        </el-button-group>

        <el-table :data.sync="items" v-loading="loading" emptyText="暂无数据" class="mt20">
            <el-table-column prop="username" label="用户名" sortable>
            </el-table-column>
            <el-table-column prop="display_name" label="昵称">
            </el-table-column>
            <el-table-column prop="phone_number" label="手机号码" sortable>
            </el-table-column>
            <el-table-column prop="email" label="邮箱">
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
                items: [],
                showAddDialog: false,
                currentItem: null,
                path: '/users'
            }
        },
        methods: {
            view(index, item) {
                this.showAddDialog = true;
                this.currentItem = item;
                this.currentItem.index = index;
            }
        }
    }
</script>

<style>

</style>