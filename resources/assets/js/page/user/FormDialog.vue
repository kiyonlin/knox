<template>
    <el-dialog :title="title" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form">
            <el-form-item label="用户名">
                <el-input placeholder="请输入用户名" v-model="form.username" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="名字">
                <el-input placeholder="请输入名字" v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="手机号码">
                <el-input placeholder="请输入手机号码" v-model="form.phone_number"></el-input>
            </el-form-item>
            <el-form-item label="邮箱">
                <el-input placeholder="请输入邮箱example@me.com" v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="初始密码" v-if="isAdd">
                <el-input placeholder="请输入初始密码,至少6位" type="password" v-model="form.password"></el-input>
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
                name: '用户'
            }
        }
    }
</script>
