export default {
    data() {
        return {
            // 使用 records 替换 items ，防止与包代码冲突
            records: [],
            loading: true,
            showAddDialog: false,
            currentRecord: null,
            path: '',
            module: '',
            page: 0,
            pageSize: 0,
            total: 0,
            pageSizes: [10, 15, 20, 50, 100, 200],
            defaultPage: 1,
            defaultPageSize: 10,
        }
    },
    watch: {
        $route() {
            let page = parseInt(this.$router.currentRoute.query.page) || this.defaultPage;
            let pageSize = parseInt(this.$router.currentRoute.query.pageSize) || this.defaultPageSize;
            this.fetch(page, pageSize);
        }
    },
    created() {            
        this.fetch();
    },
    computed: {
        canAdd() {
            return this.authorize('add', this.module);
        },
        canView() {
            return this.authorize('view', this.module);
        },
        canDelete() {
            return this.authorize('delete', this.module);
        }
    },
    methods: {
        fetch(page, pageSize) {
            this.loading = true;
            axios.get(this.url(page, pageSize))
                .then(this.refresh)
                .catch(response => this.loading = false);
        },
        url(page, pageSize) {
            this.page = parseInt(page) || this.defaultPage;
            
            this.pageSize = parseInt(pageSize) || this.defaultPageSize;
            // 忽略不合法的每页数量
            this.pageSize = this.pageSizes.includes(this.pageSize) ? this.pageSize : this.defaultPageSize;

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
            this.fetch(this.page, pageSize);
        },
        pageChange(page) {
            this.page = page;
            this.fetch(page, this.pageSize);
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