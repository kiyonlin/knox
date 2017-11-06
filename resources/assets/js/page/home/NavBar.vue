<template>
    <el-row type="flex" justify="end" class="pv15 fs16">
        <el-dropdown>
            <i class="el-icon-setting mr10 pt4"></i>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>查看</el-dropdown-item>
                <el-dropdown-item>新增</el-dropdown-item>
                <el-dropdown-item>删除</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <span>王小虎</span>
    </el-row>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component mounted.')
        },
    }
</script>

<style>
</style>
