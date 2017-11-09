<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form" :rules="rules" ref="ruleForm">
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
            <el-form-item label="标识" prop="key">
                <el-input v-model="form.key" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="名称" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="路径" prop="path">
                <el-input v-model="form.path"></el-input>
            </el-form-item>
            <el-form-item label="icon" prop="icon">
                <el-input v-model="form.icon"></el-input>
            </el-form-item>
            <el-form-item label="排序" prop="sort">
                <el-input v-model.number="form.sort"></el-input>
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
            <el-button type="primary" @click="update" v-if="!isAdd && canUpdate">更 新</el-button>
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
                options: [],
                rules: {
                    key: [
                        { required: true, message: '请填写模块标识'},
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    name: [
                        { required: true, message: '请填写模块名称'},
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    path: [
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    icon: [
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    sort: [
                        { type: 'number', message: '请填写数字', trigger: 'blur'}
                    ],
                }
            }
        },
        methods: {
            fetch() {
                if(!this.record || this.record.pid != 0) {
                    this.form = Object.assign({}, {pid:0}, this.record);
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
