export default {
    data() {
        return {
            // 使用records替换items，否则与包代码冲突
            records: [],
            path: '',
            page: 0,
            pageSize: 0,
            total: 0,
            pageSizes: [10, 15, 20, 50, 100, 200],
            loading: true,
        }
    },
    created() {            
        this.fetch();
    },
    methods: {
        fetch() {
            this.loading = true;
            axios.get(this.url())
                .then(this.refresh)
                .catch(() => {
                    this.$message.error('获取数据失败！');
                    this.loading = false;
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
                this.pageSize = query ? parseInt(query[1]) : 10;
                // 忽略不合法的每页数量
                this.pageSize = this.pageSizes.includes(this.pageSize) ? this.pageSize : 10;
            }
            return `${this.path}?page=${this.page}&pageSize=${this.pageSize}`;
        },
        refresh({
            data
        }) {
            this.records = data.data;
            this.total = data.total;
            location.href = `http://${location.host}/#${this.path}?page=${this.page}&pageSize=${this.pageSize}`;
            this.loading = false;
            window.scrollTo(0, 0);
        },
        sizeChange(pageSize) {
            this.pageSize = pageSize;
            this.fetch();
        },
        pageChange(page) {
            this.page = page;
            this.fetch();
        },
        add(item) {
            this.records.push(item);
        },
        update(item) {
            Object.assign(this.records[item.index], item);
        },
        remove(index, item) {
            this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                axios.delete(`${this.path}/${item.id}`)
                .then(response => {
                    this.$message.success('删除成功')
                    this.records.splice(index, 1);
                    this.total--;
                }).catch(error => this.$message.error(error.data.message));
            }).catch(() => this.$message('已取消删除'));
        }
    }
}