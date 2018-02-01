import qs from "qs"

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
            query: {}
        }
    },
    watch: {
        $route() {
            this.fetch();
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
        fetch() {
            this.loading = true;
            axios.get(this.url())
                .then(this.refresh)
                .catch(response => this.loading = false);
        },
        url() {
            this.query.page = this.query.page || this.defaultPage
            this.query.pageSize = this.query.pageSize || this.defaultPageSize
            let querystring = qs.stringify(this.query);
            let url = querystring ? `${this.path}?${querystring}` : `${this.path}`
            return url;
        },
         refresh({ data }) {
            this.records = data.data;
            this.total = data.total;
             this.$router.push({ query: this.query })
            this.loading = false;
            window.scrollTo(0, 0);
        },
        sizeChange(pageSize) {
            this.query.pageSize = pageSize;
            this.fetch();
        },
        pageChange(page) {
            this.query.page = page;
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
            );
        },
        view(index, record) {
            this.currentRecord = record;
            this.showAddDialog = true;
            this.currentRecord._index = index;
        },
        setRowKey(row) {
            return row.id;
        },

        search() {
            this.fetch()
        }
    }
}