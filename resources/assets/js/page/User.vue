<template>
    <div>
        <el-row>
            <el-col>
                <el-button-group>
                    <el-button type="primary" icon="el-icon-circle-plus-outline"></el-button>
                    <el-button type="primary" icon="el-icon-refresh" @click="fetch"></el-button>
                </el-button-group>
            </el-col>
        </el-row>
        <el-row class="mt30">
            <el-table
                    :data="items"
                    v-loading="loading"
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

        <el-row class="mt30" v-if="true">
            <el-col :xs="24" :md="{offset:11, span:13}">
                <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page="page"
                        :page-sizes="pageSizes"
                        :page-size="pageSize"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="total">
                </el-pagination>
            </el-col>
        </el-row>
    </div>
</template>

<script>

    export default {

        data () {
            return {
                items: [],
                page: 0,
                pageSize: 0,
                total: 0,
                pageSizes: [10, 15, 20, 50, 100, 200],
                shouldPaginate: false,
                loading: true
            }
        },

        created () {
            this.fetch();
        },

        methods: {
            fetch () {
                this.loading = true;

                axios.get(this.url())
                    .then(this.refresh)
                    .catch(() => {
                        this.$message('获取用户信息失败！');
                    });
            },

            url() {
                if (!this.page) {
                    // 正则表达式匹配query里的参数
                    let query = location.hash.match(/page=(\d+)/);
                    // 有匹配到使用正则表达式括号分组里的值
                    this.page = query ? parseInt(query[1]) : 1;
                    query = location.hash.match(/pageSize=(\d+)/);
                    // 有匹配到使用正则表达式括号分组里的值
                    this.pageSize = query ? parseInt(query[1]) : 15;
                    // 忽略不合法的每页数量
                    this.pageSize = this.pageSizes.includes(this.pageSize) ? this.pageSize : 15;
                }

                return `/users?page=${this.page}&pageSize=${this.pageSize}`;
            },

            refresh({data}) {
                this.items = data.data;
                this.total = data.total;
                this.shouldPaginate = !!(data.prev_page_url || data.next_page_url);

                window.scrollTo(0, 0);

                location.href = `http://${location.host}/#/users?page=${this.page}&pageSize=${this.pageSize}`;

                setTimeout(() => this.loading = false, 333);
            },

            handleSizeChange(pageSize) {
                this.pageSize = pageSize;
                this.fetch();
            },

            handleCurrentChange(page) {
                this.page = page;
                this.fetch();
            },
        }
    }
</script>
<style>
</style>