<template>
    <div>
        <el-row>
            <el-col>
                <el-button-group>
                    <el-button type="primary" icon="el-icon-circle-plus-outline"></el-button>
                    <el-button type="primary" icon="el-icon-share"></el-button>
                    <el-button type="primary" icon="el-icon-delete"></el-button>
                </el-button-group>
            </el-col>
        </el-row>
        <el-row style="padding-top: 50px">
            <el-table
                    :data="users"
                    emptyText="暂无数据"
                    style="width: 100%">
                <el-table-column
                        prop="username"
                        label="用户名">
                </el-table-column>
                <el-table-column
                        prop="display_name"
                        label="昵称">
                </el-table-column>
                <el-table-column
                        prop="phone_number"
                        label="手机号码">
                </el-table-column>
                <el-table-column
                        prop="email"
                        label="邮箱">
                </el-table-column>
            </el-table>
        </el-row>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                users: []
            }
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch () {
                axios.get('/users')
                    .then(({data}) => this.users = data)
                    .catch(() => {
                        this.$message('获取用户信息失败');
                    });
            }
        }
    }
</script>
<style>
</style>