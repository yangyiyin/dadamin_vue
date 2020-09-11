<template>
    <div class="fillcontain">

        <div class="table_container" style="padding-bottom: 0">

            <!--<el-input-->
                    <!--style="display: inline-block;width: 250px;"-->
                    <!--placeholder="名称"-->
                    <!--v-model="name"-->
                    <!--clearable>-->
            <!--</el-input>-->

            <!--<el-select v-model="type" placeholder="类型">-->
                <!--<el-option-->
                        <!--v-for="item in types"-->
                        <!--:key="item.id"-->
                        <!--:label="item.name"-->
                        <!--:value="item.id">-->
                <!--</el-option>-->
            <!--</el-select>-->

            <!--<el-button type="primary" icon="el-icon-search" @click="search">搜索</el-button>-->

        </div>
        <div>
            <el-alert
                    style="margin-bottom: 10px"
                    :title="'如何设置角色组权限：'"
                    type="warning"
                    description="下面以树形方式罗列了各个权限节点，他们具有父子集的关系。勾选父级可默认勾选全部子集。勾选哪些节点，代表当前角色组具有哪些权限。
                    例如，勾选“（功能）用户管理和（菜单）用户管理”节点并确认提交，则使得加入当前角色组的所有成员将可以对用户进行新增，修改或者删除等操作。不
                    同节点代表不同功能。特别说明：节点分为两种：菜单节点和功能节点。菜单节点表示左侧的菜单栏，勾选相应的节点可显示相应的栏目。功能节点表示访问
                    具体功能的权限，仅仅勾选菜单节点而不勾选功能节点，则只能展示菜单，具体的页面里的功能则无法使用。所以，请根据实际情况勾选相应的节点。"
            >
            </el-alert>
            <el-tree

                    :data="tree_data"
                    :show-checkbox="true"
                    node-key="uri"
                    ref="tree"
                    :expand-on-click-node="false">
                  <span class="custom-tree-node" slot-scope="{ node, data }">
                    <span>
                           <el-tag v-if="data.type==2" size="mini">app菜单</el-tag>
                    <el-tag effect="dark" v-if="data.type==1" size="mini">菜单</el-tag>
                        <el-tag effect="dark" type="warning" v-if="data.type==0" size="mini">功能</el-tag>

                        {{ node.label }}

                    </span>


                  </span>
            </el-tree>

            <el-button style="margin-top: 20px;" type="primary" @click="submit_group_purview()">确定</el-button>
        </div>

        <!--<el-dialog title="修改排序" :visible.sync="dialogFormVisible" width="30%">-->
            <!--<el-form :model="current">-->
                <!--<el-form-item label="排序值(越大越靠前)">-->
                    <!--<el-input v-model="current.sort" auto-complete="off"></el-input>-->
                <!--</el-form-item>-->
            <!--</el-form>-->
            <!--<div slot="footer" class="dialog-footer">-->
                <!--<el-button @click="dialogFormVisible = false">取 消</el-button>-->
                <!--<el-button type="primary" @click="sort">确 定</el-button>-->
            <!--</div>-->
        <!--</el-dialog>-->
    </div>
</template>

<script>
    import {admin_purview_tree,admin_group_edit,admin_group_info} from '@/api/getDataEarth'
    export default {
        name:'group_purview',
        data(){
            return {
                tree_data:[],
                tableData: [],
                limit: 10,
                count: 0,
                currentPage: 1,
                dialogFormVisible:false,
                current:{},
                name:'',
                loadingBtn:-1,
                type:-1,
                types:[
                    {id:0,name:'普通权限'},
                    {id:1,name:'菜单权限'}
                ],
                edit_group_id:'',
                group_info:{}

            }
        },
        components: {
        },
        created(){
        },
        mounted(){

        },
        beforeRouteEnter (to, from, next) {
            next(async (vm) => {
                vm.inited = vm.inited || {};
                vm.edit_group_id = to.query.edit_group_id ? to.query.edit_group_id : 0;
                if (!vm.inited[vm.edit_group_id]) {
                    // console.log(vm.inited);
                    // 通过 `vm` 访问组件实例
                    if (vm.edit_group_id) {
                        vm.$refs.tree.setCheckedKeys([]);
                        admin_group_info({id:vm.edit_group_id}).then((res) =>{
                            if (res.code == vm.$store.state.constant.status_success) {
                                vm.group_info = res.data;
                                if (res.data.purviews) {
                                    vm.$refs.tree.setCheckedKeys(res.data.purviews.split(','));
                                }

                            }
                        });

                    }

                    await vm.list();
                    vm.inited = {};
                    vm.inited[vm.edit_group_id] = true;
                }

        })
        },
        methods: {
            async list() {
                admin_purview_tree({page:this.currentPage,page_size:this.limit,name:this.name, type:this.type}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.tree_data = res.data;
                    }
                }.bind(this));

            },
            search() {
                this.currentPage = 1;
                this.list();
            },
            submit_group_purview() {
                var purviews_arr = [];

                this.$refs.tree.getCheckedNodes().forEach(function(ele){

                    if (ele.type == 0 && ele.children && ele.children.length) {


                    } else {
                        purviews_arr.push(ele.uri);
                    }

                });

                var purviews = purviews_arr.join(',');
                //var purviews = this.$refs.tree.getCheckedKeys().join(',');
                var purviews_half = this.$refs.tree.getHalfCheckedKeys().join(',');
                admin_group_edit({id:this.edit_group_id,purviews:purviews,purviews_half:purviews_half}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {

                        this.$message({
                            type: 'success',
                            message: '操作成功'
                        });
                        this.$router.go(-1);
                    } else {
                        this.$message({
                            type: 'warning',
                            message: res.msg
                        });
                    }
                }.bind(this));


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
    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
</style>
