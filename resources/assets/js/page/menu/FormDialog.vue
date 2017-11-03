<template>
    <el-dialog :title="title + '菜单'" :visible.sync="show" :show-close="false">
        <el-form label-width="80px" :model="form">
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
        mixins: [commonDialog]
    }
</script>
