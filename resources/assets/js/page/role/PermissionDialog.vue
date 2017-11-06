<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-input class="pb10" placeholder="输入关键字进行过滤" v-model="filterText">
        </el-input>
        <el-tree :data="treeData" show-checkbox node-key="key" ref="tree" default-expand-all
                 :default-checked-keys="defaultCheckedKeys" :filter-node-method="filterNode">
        </el-tree>
        <div slot="footer">
            <el-button @click="show = false">取 消</el-button>
            <el-button type="primary" @click="submit">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ["visiable", "record"],
        data () {
            return {
                show: false,
                title: "",
                filterText: "",
                treeData: null,
                defaultCheckedKeys: [],
                defaultProps: {
                    children: "children",
                    label: "label",
                    isLeaf: 'leaf'
                }
            };
        },
        watch: {
            /**
             * 由父组件控制是否显示对话框
             */
            visiable () {
                if (this.visiable) {
                    this.show = this.visiable;
                    this.title = `查看【${this.record.display_name}】权限`;
                    this.fetch();
                }
            },
            /**
             * 同步sync数据，根据Vue2.3以后的版本，因为是props属性，需要使用事件触发
             * 详见：https://cn.vuejs.org/v2/guide/components.html#sync-修饰符
             * 当关闭对话框时，更新父组件信息
             */
            show (value) {
                if (!value) {
                    this.$emit("update:visiable", false);
                    this.$emit("update:record", null);
                }
            },
            filterText (value) {
                this.$refs.tree.filter(value);
            }
        },
        methods: {
            fetch () {
                axios.get(`roles/${this.record.id}/permissions`)
                    .then(response => {
                        this.treeData = response.data.tree;
                        this.defaultCheckedKeys = response.data.defaultCheckedKeys;
                    });
            },
            submit () {
                let checkedKeys = this.$refs.tree.getCheckedKeys()
                    .filter(item => typeof item === 'number');
                if (!this.arrayDiff(checkedKeys, this.defaultCheckedKeys).length) {
                    this.show = false;
                    return;
                }
                axios.put(`roles/${this.record.id}/permissions`, {checkedKeys})
                    .then(_ => {
                        this.$message.success('更改权限成功');
                        this.show = false;
                    });
            },
            filterNode (value, data) {
                if (!value) return true;
                return data.label.includes(value);
            }
        }
    };
</script>

<style>

</style>
