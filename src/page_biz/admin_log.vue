<template>
    <div class="fillcontain">

        <div >
            <p class="title">搜索条件：</p>

            <div style="display: flex">
                <div style="flex: 1">
                    <div class="search-item">
                        <div class="preinfo">用户名:</div>
                        <el-input
                                style="display: inline-block;width: 250px;"
                                placeholder="用户名"
                                v-model="opt_name"
                                clearable>
                        </el-input>
                    </div>
                    <div class="search-item">
                        <div class="preinfo">动作:</div>
                        <el-input
                                style="display: inline-block;width: 250px;"
                                placeholder="动作"
                                v-model="action_name"
                                clearable>
                        </el-input>
                    </div>

                    <el-button type="primary" icon="el-icon-search" @click="search">搜索</el-button>
                </div>
            </div>

        </div>

            <el-table
                    :data="tableData"
                    border
                    style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <p style="width: 100%;overflow: auto;word-break: normal">{{ props.row.params }}</p>
                    </template>
                </el-table-column>
                <el-table-column label="动作" prop="action_name"></el-table-column>
                <el-table-column label="动作参数" prop="params">
                    <template slot-scope="scope">
                        {{scope.row.params.substring(0, 10)}}...
                    </template>
                </el-table-column>
                <el-table-column label="操作员" prop="opt_name"></el-table-column>
                <el-table-column label="操作员(显示名)" prop="opt_show_name"></el-table-column>
                <el-table-column label="日期" prop="create_time"></el-table-column>
            </el-table>
            <div class="Pagination" style="text-align: left;margin-top: 10px;">
                <el-pagination
                        @current-change="handleCurrentChange"
                        :current-page="currentPage"
                        :page-size="limit"
                        layout="total, prev, pager, next"
                        :total="count"
                        background>
                </el-pagination>
            </div>
    </div>
</template>

<script>
    import {admin_log_list} from '@/api/getDataEarth'
    export default {
        name:'admin_log',
        data(){
            return {
                tableData: [],
                limit: 10,
                count: 0,
                currentPage: 1,
                dialogFormVisible:false,
                current:{},
                user_name:'',
                opt_name:'',
                action_name:'',
                loadingBtn:-1
            }
        },
        components: {
        },
        created(){
            this.list();
        },
        mounted(){

        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // 通过 `vm` 访问组件实例
                //vm.list();
        })
        },
        methods: {
            list() {
                admin_log_list({page:this.currentPage,page_size:this.limit, opt_name:this.opt_name,action_name:this.action_name}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.tableData = res.data.list;
                        this.count = parseInt(res.data.count);
                    }
                }.bind(this));

            },
            handleCurrentChange(val){
                this.currentPage = val;
                this.list();
            },
            handleEdit(row){
                this.dialogFormVisible = true;
                if (row) {
                    this.current_entity = row;
                } else {
                    this.current_entity = {};
                }

            },
            search() {
                this.currentPage = 1;
                this.list();
            },

        },
    }
</script>

<style scoped lang="less">
    @import '../style/mixin';
    .table_container{
        padding: 20px;
    }
    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>
