<template>
    <div class="fillcontain">


        <div>
            <p class="title">添加用户：</p>
            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">账号:</span>
                <el-input clearable placeholder="请输入账号" v-model="user_name" style="width: 350px">
                </el-input>
            </div>
            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">密码:</span>
                <el-input clearable placeholder="请输入密码" v-model="password" style="width: 350px">
                </el-input>
            </div>
            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">真实姓名:</span>
                <el-input clearable placeholder="请输入显示名" v-model="show_name" style="width: 350px">
                </el-input>
            </div>

            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">角色组:</span>
                <el-select v-model="group_id" placeholder="类型">
                    <el-option
                            v-for="item in groups"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </div>

            <!--<div class="search_item">-->
                <!--<span class="pre_info" style="font-size: 14px;">所属招聘点:</span>-->

                <!--<el-select multiple v-model="zps" placeholder="请选择">-->
                    <!--<el-option-->
                            <!--label="全部"-->
                            <!--value="all">-->
                    <!--</el-option>-->
                    <!--<el-option-->
                            <!--v-for="item in zplist"-->
                            <!--:key="item.id"-->
                            <!--:label="item.name"-->
                            <!--:value="item.id">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</div>-->

            <!--<div class="search_item">-->
                <!--<span class="pre_info" style="font-size: 14px;">部门:</span>-->
                <!--<el-select v-model="orgnizes" multiple placeholder="请选择">-->
                    <!--<el-option-->
                            <!--v-for="item in parents"-->
                            <!--:key="item.id"-->
                            <!--:label="item.name"-->
                            <!--:value="item.id">-->
                    <!--</el-option>-->
                <!--</el-select>-->
            <!--</div>-->

            <el-button type="primary" style="margin-top: 20px;width: 470px" v-on:click="submit" :loading="loading">提交</el-button>


        </div>

    </div>
</template>

<script>
    import {admin_user_edit,admin_user_info,admin_group_all_list} from '@/api/getDataEarth'

    import {deepCopy} from '@/config/mUtils'
    export default {
        name:'add_admin_user',
        data(){
            return {
                id:0,
                loading:false,
                user_name:'',
                show_name:'',
                password:'',
                group_id:'',
                groups : [],
                parents:[],
                orgnizes:null,
                dept:{
                    options_year:[],
                    options_dept:[],
                    options_class:[],
                    options_student:[],
                    select_student:[],
                    select_class:[],
                    select_dept:[],
                    select_year:[],
                },
                zplist:[],
                zps:[]
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

                vm.init(to.query.id)

            })
        },
        methods: {

            async init(id) {

                this.id = id || 0;
                this.inited = this.inited || {};
                if (!this.inited[this.id]) {
                    this.loading = false;
                    this.user_name = '';
                    this.show_name = '';
                    this.password = '';
                    this.group_id = '';
                    this.orgnizes = [];

                    //初始化可选年段
                    var curYear = (new Date()).getFullYear();
                    this.dept.options_year = [];
                    this.dept.select_year = [];
                    for(var i = 2016;i<=curYear;i++) {
                        this.dept.options_year.push({value:i.toString(),label:i+'级'});
                    }

//                    await this.get_dept_options();

                    if (this.id && this.id > 0) {
                        this.get_all_group_list();
                        this.get_info();
                    } else {
                        this.get_all_group_list();
                    }
                    this.inited = {};
                    this.inited[this.id] = true;
                }
            },

            async get_dept_options(){
                await get_dept_options().then((res) => {
                    if (res.code == this.$store.state.constant.status_success) {
                        this.dept.options_dept = res.data.options_dept;
                        this.dept.options_class = res.data.options_class;
                        this.dept.options_student = res.data.options_student;
                    }
                });
            },

            get_all_group_list(){
                return admin_group_all_list().then(function(res){
                    return new Promise(function(resolve,reject){
                        if (res.code == this.$store.state.constant.status_success) {
                            this.groups = res.data
                        }
                        resolve();
                    }.bind(this));

                }.bind(this));
            },
            get_all_orgnize_list(){

                return admin_orgnize_all_list().then(function(res){
                    return new Promise(function(resolve,reject){
                        if (res.code == this.$store.state.constant.status_success) {
                            this.parents = res.data

                        }
                        resolve();
                    }.bind(this));

                }.bind(this));
            },
            get_info() {
                admin_user_info({id:this.id}).then(async function (res) {
                    if (res.code == this.$store.state.constant.status_success) {
                        this.user_name = res.data.user_name;
                        this.show_name = res.data.show_name;
                        this.password = '';
                        this.group_id = res.data.group_id;
                        this.orgnizes = res.data.orgnizes;

                        this.dept.select_year = res.data.dept[0] || [];
                        this.dept.select_dept = res.data.dept[1] || [];
                        await this.changeDept(1);
                        this.dept.select_class = res.data.dept[2] || [];
                        await this.changeDept(2);
                        this.dept.select_student = res.data.dept[3] || [];

                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }

                }.bind(this));

                // getUserZp({id:this.id}).then(function (res) {
                //     if (res.code == this.$store.state.constant.status_success) {
                //         this.zps = res.data
                //     } else {
                //         this.$message({
                //             message: res.msg,
                //             type: 'warning'
                //         });
                //     }
                //
                // }.bind(this));
            },
            submit: function () {
                if (!this.user_name) {
                    var error_msg = '请填写账号';
                }

                if (!this.password) {
                    var error_msg = '请填写密码';
                }

                if (!this.show_name) {
                    var error_msg = '请填写显示名';
                }

                if (!this.group_id) {
                    var error_msg = '请选择组';
                }

//                if (!this.orgnizes.length) {
//                    var error_msg = '请选择部门';
//                }
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
                    admin_user_edit({id:this.id,user_name:this.user_name,password:this.password,show_name:this.show_name,group_id:this.group_id,orgnizes:this.orgnizes,dept:this.dept}).then(function (res) {
                        if (res.code == this.$store.state.constant.status_success) {

                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                            this.$router.push({path:'admin_user',query:{}});
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

            },
            async changeDept(level){
                if (level == 1) {
                    this.dept.select_class = [];
                    this.dept.select_student = [];
                    this.dept.options_class = [];
                    this.dept.options_student = [];

                }
                if (level == 2) {
                    this.dept.select_student = [];
                    this.dept.options_student = [];
                }

                if (level == 1 && this.dept.select_dept.length > 0) {//获取班级
                    await get_dept_options({level:2, levelid:this.dept.select_dept}).then((res) => {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.dept.options_class = res.data.options_class;
                        }
                    });
                } else if (level == 2 && this.dept.select_class.length > 0) {//获取学生
                    await get_dept_options({level:3, levelid:this.dept.select_class}).then((res) => {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.dept.options_student = res.data.options_student;
                        }
                    });
                }
            }

        }
    }
</script>

<style scoped lang="less">
    .search_item{

        margin-top: 10px;

    }

</style>
