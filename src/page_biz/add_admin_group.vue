<template>
    <div class="fillcontain">


        <div>
            <p class="title">添加角色组：</p>
            <div class="search_item">
                <span class="pre_info" style="font-size: 14px;">角色组名称:</span>
                <el-input clearable placeholder="请输入名称" v-model="name" style="width: 350px">
                </el-input>
            </div>


            <el-button type="primary" style="margin-top: 20px;width: 470px;" v-on:click="submit" :loading="loading">提交</el-button>


        </div>

    </div>
</template>

<script>
    import {admin_group_edit,admin_group_info} from '@/api/getDataEarth'
    import {deepCopy} from '@/config/mUtils'
    export default {
        name:'add_admin_group',
        data(){
            return {
                id:0,
                loading:false,
                name:''
            }

        },
        components: {
        },
        created(){

        },
        mounted(){

        },

        beforeRouteEnter (to, from, next) {
            console.log(123);
            next(vm => {
                // 通过 `vm` 访问组件实例
                vm.init(to.query.id);

            })
        },
        // beforeRouteUpdate (to, from, next) {
        //     console.log(123);
        //     this.init(to.query.id);
        // },
        methods: {

            init(id) {
                this.id = id ? id : 0;
                this.inited = this.inited || {};
                if (!this.inited[this.id]) {
                    this.loading = false;
                    this.name = '';
                    if (this.id && this.id > 0) {
                        this.get_info();
                    }
                    this.inited = {};
                    this.inited[this.id] = true;
                }
            },
            get_info() {
                admin_group_info({id:this.id}).then(function (res) {
                    if (res.code == this.$store.state.constant.status_success) {
                        this.name = res.data.name;
                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }

                }.bind(this));
            },
            submit: function () {

                if (!this.name) {
                    var error_msg = '请填写名称';
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
                    admin_group_edit({id:this.id,name:this.name}).then(function (res) {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                            this.$router.push({path:'admin_group',query:{}});
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
    .search_item{

        margin-top: 10px;

    }

</style>
