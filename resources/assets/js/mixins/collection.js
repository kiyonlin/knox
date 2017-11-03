export default {
    data() {
        return {
            // 使用records替换records，否则与包代码冲突
            records: [],
            showAddDialog: false,
            currentRecord: null,
            path: '',
            page: 0,
            pageSize: 0,
            total: 0,
            pageSizes: [10, 15, 20, 50, 100, 200],
            loading: true,
        }
    },
    watch: {
        $route: 'fetch'
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
            this.page = parseInt(this.$router.currentRoute.query.page) || 1;
            
            this.pageSize = parseInt(this.$router.currentRoute.query.pageSize) || 10;
            // 忽略不合法的每页数量
            this.pageSize = this.pageSizes.includes(this.pageSize) ? this.pageSize : 10;

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
        add(record) {
            this.records.push(record);
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
        setRowKey(row) {
            return row.id;
        },
        view(index, record) {
            this.currentRecord = record;
            this.showAddDialog = true;
            this.currentRecord._index = index;
        }
    }
}