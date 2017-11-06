export default {
    data() {
        return {
            // 使用 records 替换 items ，否则与包代码冲突
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
                .catch(response => {
                    this.$message.error(response.data.error.message);
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
        refresh({ data }) {
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
            this.deleteConfirm(_ => 
                axios.delete(`${this.path}/${record.id}`)
                .then(response => {
                    this.$message.success('删除成功')
                    this.records.splice(index, 1);
                    this.total--;
                })
                .catch(response => this.$message.error(response.data.error.message))
            );
        },
        view(index, record) {
            this.currentRecord = record;
            this.showAddDialog = true;
            this.currentRecord._index = index;
        },
        setRowKey(row) {
            return row.id;
        }
    }
}