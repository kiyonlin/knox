<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form" :rules="rules" ref="ruleForm">
            <el-form-item label="用户名" prop="username">
                <el-input placeholder="请输入用户名" v-model="form.username" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="名字">
                <el-input placeholder="请输入名字" v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="手机号码" prop="phone_number">
                <el-input placeholder="请输入手机号码" v-model="form.phone_number"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input placeholder="请输入邮箱example@me.com" v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="初始密码" v-if="isAdd" prop="password">
                <el-input placeholder="请输入初始密码,至少6位" type="password" v-model="form.password"></el-input>
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
                name: '用户',
                rules: {
                    username: [
                        { required: true, message: '请填写用户名'},
                        { max: 255, message: '长度不能超过255个字符', trigger: 'blur'}
                    ],
                    phone_number : [
                        { required: true, message: '请填写手机号码'},
                        { pattern: /^1(3|4|5|8|7)\d{9}$/, message: '请填写正确的手机号码', trigger: 'blur'}
                    ],
                    email : [
                        { required: true, message: '请填写正确的邮箱'},
                        { type: 'email', message: '请填写正确的邮箱', trigger: 'blur'}
                    ],
                    password: [
                        { required: true, message: '请填写密码'},
                        { min: 6, max: 255, message: '密码长度不能小于6个字符', trigger: 'blur'}
                    ],
                }
            }
        }
    }
</script>
