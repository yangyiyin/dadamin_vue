<template>
    <div class="fillcontain">



        <div>
            <p class="title">搜索条件：</p>
            <div style="display: flex">
                <div style="flex: 1">
                    <div class="search-item">
                        <div class="preinfo">名称:</div>
                        <el-input
                                v-model="name"
                                clearable>
                        </el-input>
                    </div>

                    <el-button type="primary" icon="el-icon-search" @click="search">搜索</el-button>
                </div>
                <div>
                    <el-button  style="" type="primary" @click="goto_edit_admin_group(0)">添加角色组<i class="el-icon-right"></i></el-button>
                </div>
            </div>
        </div>


        <!--<el-button  style="float: right;margin-left: 40px;" icon="el-icon-plus" type="success" @click="goto_edit_admin_group(0)">添加角色组</el-button>-->

        <el-table
                :data="tableData"
                border
                style="width: 100%;">
            <el-table-column label="名称" prop="name"></el-table-column>
            <el-table-column label="创建日期" prop="create_time"></el-table-column>
            <el-table-column label="排序">
                <template slot-scope="scope">
                    {{scope.row.sort}}
                    <el-button size="mini" @click="handleSort(scope.row)">设置</el-button>
                </template>
            </el-table-column>
            <el-table-column label="操作" width="300">
                <template slot-scope="scope">
                    <el-button size="mini" @click="goto_edit_admin_group(scope.row.id)">编辑</el-button>
                    <!--<el-button size="mini" v-if="scope.row.status == 1" @click="verify(scope, 0)" :loading="loadingBtn == scope.$index">禁用</el-button>-->
                    <!--<el-button size="mini" v-if="scope.row.status == 0" @click="verify(scope, 1)" :loading="loadingBtn == scope.$index">启用</el-button>-->
                    <el-button size="mini" @click="del(scope.row, scope.$index)">删除</el-button>
                    <el-button size="mini" type="primary" @click="purview_edit(scope.row)">权限</el-button>
                </template>
            </el-table-column>
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

        <el-dialog title="修改排序" :visible.sync="dialogFormVisible" width="30%">
            <el-form :model="current">
                <el-form-item label="排序值(越大越靠前)">
                    <el-input v-model="current.sort" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="sort">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {admin_group_list,admin_group_del,admin_group_verify,admin_group_sort} from '@/api/getDataEarth'
    import moreinfo from '@/components/moreinfo'
    export default {
        name:'admin_group',
        data(){
            return {
                tableData: [],
                limit: 10,
                count: 0,
                currentPage: 1,
                dialogFormVisible:false,
                current:{},
                name:'',
                loadingBtn:-1
            }
        },
        components: {
            moreinfo,
        },
        created(){
            this.list();
        },
        mounted(){

        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // 通过 `vm` 访问组件实例
                // vm.list();
            })
        },
        methods: {
            list() {
                admin_group_list({page:this.currentPage,page_size:this.limit,name:this.name}).then(function(res){
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
            goto_edit_admin_group(id) {
                this.$router.push({path:'add_admin_group',query:{id:id}});
            },
            verify(scope, status) {

                this.$confirm('确认此操作?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function(){
                    var item = scope.row;
                    this.loadingBtn = scope.$index;
                    admin_group_verify({id:item.id,status:status}).then(function(res){
                        if (res.code == this.$store.state.constant.status_success) {
                            item.status = status;
                            this.$message({
                                type: 'success',
                                message: '操作成功'
                            });
                        } else {
                            this.$message({
                                type: 'warning',
                                message: res.msg
                            });
                        }
                    }.bind(this)).finally(function(){
                        this.loadingBtn = -1;
                    }.bind(this));
                }.bind(this));



            },
            del(item, index) {

                this.$confirm('此操作将永久删除该条数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function(){
                    admin_group_del({id:item.id}).then(function(res){
                        if (res.code == this.$store.state.constant.status_success) {
                            this.tableData.splice(index,1);
                            this.count --;
                            this.$message({
                                type: 'success',
                                message: '操作成功'
                            });
                        } else {
                            this.$message({
                                type: 'warning',
                                message: res.msg
                            });
                        }
                    }.bind(this));
                }.bind(this))

            },
            handleSort(row){
                this.dialogFormVisible = true;
                this.current = row;
            },
            sort() {
                admin_group_sort({
                    id:this.current.id,
                    sort:this.current.sort

                }).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.dialogFormVisible = false;
                        this.$message({
                            type: 'success',
                            message: '操作成功'
                        });
                    } else {
                        this.$message({
                            showClose: true,
                            message: res.msg,
                            type: 'warning'
                        });
                    }
                }.bind(this));
                this.dialogFormVisible = false;
            },
            purview_edit(row){
                this.$router.push({path:'group_purview',query:{edit_group_id:row.id}});
            }
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
