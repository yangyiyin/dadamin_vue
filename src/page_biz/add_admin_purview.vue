<template>
    <div class="fillcontain">


        <div >
            <p class="title">添加权限节点：</p>
            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">类型:</span>
                <el-select @change="get_all_list" v-model="type" placeholder="类型">
                    <el-option
                            v-for="item in types"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </div>

            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">父类:</span>
                <el-select v-model="pid" placeholder="类型">
                    <el-option
                            v-for="item in parents"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </div>


            <div v-if="type==1" class="search_item">
                <span class="pre_info" style="font-size: 14px;">菜单名称:</span>
                <el-input clearable placeholder="请输入菜单名称" v-model="name" style="width: 350px">
                </el-input>
            </div>

            <div v-if="type!=1" class="search_item">
                <span class="pre_info" style="font-size: 14px;">名称:</span>
                <el-input clearable placeholder="请输入名称" v-model="name" style="width: 350px">
                </el-input>
            </div>

            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">权限资源:</span>
                <el-input clearable placeholder="请输入权限资源" v-model="uri" style="width: 350px">
                </el-input>
            </div>

            <div v-if="type==1" class="search_item">
                <span class="pre_info" style="font-size: 14px;">图标:</span>
                <el-input clearable placeholder="请输入图标代码" v-model="ico" style="width: 350px">
                </el-input>
            </div>

            <el-button type="primary" style="margin-top: 20px;width: 470px;" v-on:click="submit" :loading="loading">提交</el-button>


        </div>

    </div>
</template>

<script>
    import {admin_purview_edit,admin_purview_info,admin_purview_all_list} from '@/api/getDataEarth'
    import {deepCopy} from '@/config/mUtils'
    export default {
        name:'add_admin_purview',
        data(){
            return {
                id:0,
                action:'',
                loading:false,
                type:0,
                pid:'0',
                name:'',
                uri:'',
                ico:'',
                types:[
                    {id:0,name:'普通权限'},
                    {id:1,name:'菜单权限'}
                ],
                parents:[{id:'0',name:'顶级'}],
            }

        },
        components: {
        },
        created(){

        },
        mounted(){

        },

        beforeRouteEnter (to, from, next) {
            next(vm => {
                // 通过 `vm` 访问组件实例
                vm.init(to.query.id, to.query.action);

            })
        },
        methods: {

            init(id, action) {
                this.id = id ? id : '';
                this.action = action ? action : '';
                this.inited = this.inited || {};
                if (!this.inited[this.id + '_' + this.action]) {
                    this.loading = false;
                    this.name = '';
                    this.type=0;
                    this.pid = '0';
                    this.uri = '';
                    this.ico = '';
                    if (this.id && this.id > 0) {
                        this.get_info().then(this.get_all_list);
                    } else {
                        this.get_all_list();
                    }
                    this.inited = {};
                    this.inited[this.id + '_' + this.action] = true;
                }
            },
            get_all_list(){
                admin_purview_all_list({type:this.type}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.parents = res.data
                        this.parents.unshift({id:'0',name:'顶级'});
                    }
                }.bind(this));
            },
            get_info() {

                return admin_purview_info({id:this.id}).then(function (res) {
                    return new Promise(function(resolve,reject){

                        if (res.code == this.$store.state.constant.status_success) {
                            if (this.action == 'modify') {
                                this.pid = res.data.pid
                                this.type = parseInt(res.data.type);
                                this.uri = res.data.uri;
                                this.name = res.data.name;
                                this.ico = res.data.ico;
                            } else if(this.action == 'add') {
                                this.pid = res.data.id
                                this.type=parseInt(res.data.type);
                                this.uri = '';
                                this.name = '';
                                this.ico = '';
                            }
                            resolve(res);
                        } else {
                            this.$message({
                                message: res.msg,
                                type: 'warning'
                            });
                            reject();
                        }

                    }.bind(this));
                }.bind(this));
            },
            submit: function () {

                if (!this.name && !this.menu_name) {
                    var error_msg = '请填写名称';
                }

                if (!this.uri) {
                    var error_msg = '请填写权限资源';
                }

                if (error_msg) {
                    this.$message({
                        type: 'warning',
                        message: error_msg
                    });
                    return;
                }

                this.$confirm('确认无误, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function(){
                    this.loading = true;
                    if (this.action == 'add') {
                        this.id = '';
                    }
                    admin_purview_edit({id:this.id,name:this.name,pid:this.pid,type:this.type,uri:this.uri,ico:this.ico}).then(function (res) {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                            this.$router.push({path:'admin_purview',query:{}});
                        } else {
                            this.$message({
                                message: res.msg,
                                type: 'warning'
                            });
                        }

                    }.bind(this));

                }.bind(this)).catch(() => {

                }).finally(function(){
                    this.loading = false;
                }.bind(this));

            }

        }
    }
</script>

<style scoped lang="less">
    @import '../style/mixin';
    .search_item{

        margin-top: 10px;

    }

</style>
