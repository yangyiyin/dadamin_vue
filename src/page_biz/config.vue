<template>
    <div class="fillcontain">

        <template >
            <el-card style="width: 80%;">
                <div style="width: 100%;position: relative;margin-top: 5px;" v-for="(item, index)  in data_list">

                    <el-input v-if="!item.can_edit" clearable placeholder="值" v-model="item.value" >
                        <template slot="prepend" >{{item.remark}}</template>
                    </el-input>

                    <el-card v-if="item.can_edit">
                        <el-input v-if="item.can_edit" clearable placeholder="键" v-model="item.key">
                            <template slot="prepend">键</template>
                        </el-input>

                        <el-input v-if="item.can_edit" clearable placeholder="值" v-model="item.value" >
                            <template slot="prepend">值</template>
                        </el-input>
                        <el-input v-if="item.can_edit" clearable placeholder="备注" v-model="item.remark">
                            <template slot="prepend">备注</template>
                        </el-input>
                    </el-card>
                </div>
                <el-button style="width: 100%;margin-top: 10px;margin-bottom: 10px" v-on:click="add_page_item" >+</el-button>
                <br/>
                <el-button type="success" style="width: 100%;margin-bottom: 10px" v-on:click="submit" :loading="loading">保存</el-button>
            </el-card>

        </template>
    </div>
</template>

<script>

    import {config_list,config_edit} from '@/api/getDataEarth'

    export default {
        data(){
            return {
                loading:false,
                data_list:[],

            }

        },
        components: {

        },
        created(){

        },
        mounted(){

            //console.log(this.$route.query);
        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // 通过 `vm` 访问组件实例
                vm.init();

        })
        },

        methods: {
            init() {
                config_list({page:1,page_size:100}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.data_list = res.data.list;

                    }
                }.bind(this));
            },
            add_page_item:function () {
                this.data_list.push({key:'',value:'',remark:'',can_edit:true});
            },
            del_page_item:function(index){
                this.data_list.splice(index, 1);
            },
            submit: function () {

                this.$confirm('此操作将保存数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function(){
                    this.loading = true;
                    config_edit({data:this.data_list}).then(function (res) {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.$message({
                                message: '保存成功',
                                type: 'success'
                            });
                            this.init();
                        } else {
                            this.$message({
                                message: res.message,
                                type: 'warning'
                            });
                        }
                    }.bind(this),response => {
                    });

                }.bind(this)).catch(() => {

                }).finally(function(){
                    this.loading = false;
                }.bind(this));

            }

        }
    }
</script>

<style lang="less">
    @import '../style/mixin';
    .search_item{

        margin-top: 10px;

    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
    }
    .avatar {
        width: 100px;
        height: 100px;
        display: block;
    }
</style>
