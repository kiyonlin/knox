<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false" width="35%">
        <el-alert title="点击权限标签可以查看详情😊" type="info" center show-icon class="mb10"></el-alert>
        <el-form label-width="80px" :model="form" :rules="rules" ref="ruleForm">
            <el-form-item label="已有权限">
                <el-tag v-for="(perm, index) in perms" :key="perm.id" @close="remove(index, perm)" :closable="authorize('delete', 'permission')">
                    <span @click="view(perm)" v-text="perm.display_name" style="cursor:pointer"></span>
                </el-tag>
                <el-button class="button-new-tag" size="small" @click="showAdd" v-if="authorize('add', 'permission')">添加权限</el-button>
            </el-form-item>
            <template v-if="showForm">
                <el-form-item label="权限名" prop="name">
                    <el-input placeholder="请输入权限名:module.action" v-model="form.name" :disabled="!isAdd"></el-input>
                </el-form-item>
                <el-form-item label="显示名称" prop="display_name">
                    <el-input placeholder="请输入显示名称" v-model="form.display_name"></el-input>
                </el-form-item>
                <el-form-item label="描述" prop="description">
                    <el-input type="textarea" :rows="5" placeholder="请输入描述" v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="add" v-if="isAdd">添 加</el-button>
                    <el-button type="primary" @click="update" v-if="!isAdd && authorize('update', 'permission')">更 新</el-button>
                </el-form-item>
            </template>
        </el-form>
        <div slot="footer">
            <el-button type="primary" @click="show = false">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['visiable', 'record', 'path'],
        data() {
            return {
                perms: [],
                show: false,
                title: '',
                form: {},
                isAdd: null,
                showForm: false,
                currentPerm: null,
                rules: {
                    name: [
                        { required: true, message: '请填写权限名'},
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    display_name: [
                        { required: true, message: '请填写权限显示名'},
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    description: [
                        { max: 511, message: '长度不能超过511个字符', trigger: 'blur'}
                    ],
                }
            }
        },
        
        watch: {
            /**
             * 由父组件控制是否显示对话框
             * 需要显示时，根据是否传入旧项目判断是新增还是修改
             */
            visiable() {
                if(this.visiable) {
                    this.show = this.visiable;
                    this.perms = this.record.perms;
                    this.title = `【${this.record.name}】权限`;
                    this.showForm = false;
                }
            },
            /**
             * 同步sync数据，根据Vue2.3以后的版本，因为是props属性，需要使用事件触发
             * 详见：https://cn.vuejs.org/v2/guide/components.html#sync-修饰符
             * 当关闭对话框时，更新父组件信息
             */
            show(value) {
                if(!value) {
                    this.$emit('update:visiable', false);
                    this.$emit('update:record', null);
                }
            }
        },
        methods: {
            showAdd() {
                this.showForm = true;
                this.isAdd = true;
                this.form = {};
            },
            add(){
                this.$refs.ruleForm && this.$refs.ruleForm.validate((valid) => {
                    if (valid) {
                        axios.post(`/modules/${this.record.id}/permissions`, this.form)
                        .then(response => {
                            this.perms.push(response.data);
                            this.showForm = false;
                            this.$message.success('添加成功');
                        })
                    }
                    
                    return false;
                });
            },
            view(perm) {
                this.showForm = true;
                this.isAdd = false;
                this.form = {};
                Object.assign(this.form, perm);
                this.currentPerm = perm;
            },
            update() {
                let patch = this.getDirty(this.currentPerm, this.form);
                axios.patch(`/modules/${this.record.id}/permissions/${this.currentPerm.id}`, patch)
                    .then(response => {
                        this.perms.push(response.data);
                        this.showForm = false;
                        this.currentPerm = null;
                        this.$message.success('更新成功');
                    })
            },
            remove(index, perm) {
                this.deleteConfirm(_ => 
                    axios.delete(`/modules/${this.record.id}/permissions/${perm.id}`)
                    .then(response => {
                        if(this.currentPerm == perm) {
                            this.showForm = false;
                            this.currentPerm = null;
                        }
                        this.perms.splice(index, 1);
                        this.$message.success('删除成功');
                    })
                );
            },
        }
    }
</script>

<style>
    .el-tag+.el-tag {
        margin-left: 10px;
    }

    .button-new-tag {
        margin-left: 10px;
        height: 32px;
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
