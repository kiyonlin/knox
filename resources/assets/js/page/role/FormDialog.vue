<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form" :rules="rules" ref="ruleForm">
            <el-form-item label="角色名" prop="name">
                <el-input placeholder="请输入角色名" v-model="form.name" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="显示名称" prop="display_name">
                <el-input placeholder="请输入显示名称" v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="描述" prop="description">
                <el-input type="textarea" :rows="5" placeholder="请输入描述" v-model="form.description"></el-input>
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
                name: '角色',
                rules: {
                    name: [
                        { required: true, message: '请填写角色名'},
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
        }
    }
</script>
