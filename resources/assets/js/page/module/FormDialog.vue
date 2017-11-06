<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form">
            <el-form-item label="父模块">
                <el-select v-model="form.pid" :disabled="!isAdd">
                    <el-option :key="0" :label="'顶级'" :value="0"></el-option>
                    <el-option
                        v-for="item in options"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="标识">
                <el-input v-model="form.key" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="名称">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="路径">
                <el-input v-model="form.path"></el-input>
            </el-form-item>
            <el-form-item label="icon">
                <el-input v-model="form.icon"></el-input>
            </el-form-item>
            <el-form-item label="排序">
                <el-input v-model="form.sort"></el-input>
            </el-form-item>
            <el-form-item label="层级" v-if="!isAdd">
                <el-input v-model="form.level" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="索引" v-if="!isAdd">
                <el-input v-model="form.index" :disabled="!isAdd"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button @click="show = false">取 消</el-button>
            <el-button type="primary" @click="add" v-if="isAdd">添 加</el-button>
            <el-button type="primary" @click="update" v-else>更 新</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import commonDialog from '../../mixins/commonDialog';

    export default {
        mixins: [commonDialog],

        data() {
            return {
                name: '模块',
                options: []
            }
        },
        methods: {
            fetch() {
                if(!this.record || this.record.pid != 0) {
                    this.form = Object.assign({}, {pid:0});
                    axios.get(`modules/tops`)
                        .then(response => this.options = response.data);
                } else {
                    this.options = [];
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .autocomplete {
        li {
            line-height: normal;
            padding: 7px;

            .name {
                text-overflow: ellipsis;
                overflow: hidden;
            }
        }
    }
</style>
