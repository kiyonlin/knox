<template>
    <el-dialog title="添加角色" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form">
            <el-form-item label="角色名">
                <el-input v-model="form.name" :disabled="!isAdd"></el-input>
            </el-form-item>
            <el-form-item label="显示名称">
                <el-input v-model="form.display_name"></el-input>
            </el-form-item>
            <el-form-item label="描述">
                <el-input v-model="form.description"></el-input>
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
        mixins: [commonDialog]
    }
</script>
