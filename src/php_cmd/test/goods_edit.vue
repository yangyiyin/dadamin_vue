<template>
    <div class="fillcontain">
        <head-top></head-top>

        <div class="table_container" style="padding:20px">
            <el-card>
            <el-form label-suffix=":" ref="form" :rules="rules" label-position="left" :model="form" label-width="120px">
                                        <el-form-item label="标题"  prop="title">
                            <el-input style="max-width: 500px"  clearable placeholder="请输入" v-model="form.title"></el-input>
                        </el-form-item>
                                                <el-form-item label="内容"  prop="desc">
                            <el-input
                                    style="max-width: 500px"
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 7}"
                                    placeholder="请输入"
                                    v-model="form.desc">
                            </el-input>
                        </el-form-item>
                                                <el-form-item label="分类"  prop="category">
                            <el-select :multiple="1" v-model="form.category" placeholder="请选择" >
                                
                                                                    <el-option
                                            v-for="item in select_options.categories"
                                            :key="item.id"
                                            :label="item.id"
                                            :value="item.id">
                                    </el-option>
                                
                            </el-select>
                        </el-form-item>
                                                <el-form-item label="单图"  prop="pic">
                            <el-upload
                                    class="avatar-uploader"
                                    :action="$store.state.constant.upload_url"
                                    :on-remove="(file, fileList) => {return handleRemove(file, fileList, 'single_image', 'pic')}"
                                    :show-file-list="false"
                                    :on-exceed="(files, fileList) => {return handleExceed(files, fileList, 'single_image', 'pic')}"
                                    :on-success="(res, file, fileList) => {return handleSuccess(res, file, fileList, 'single_image', 'pic')}"
                                    :before-upload="(file) => {return beforeUpload(file, 'single_image', 'pic')}">
                                <img v-if="form.pic" :src="form.pic" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"  ></i>
                                <!--<el-button size="small" type="primary">点击上传</el-button>-->
                                <div slot="tip" class="el-upload__tip">建议尺寸600*400;且大小不超过2M</div>
                            </el-upload>

                        </el-form-item>
                                                <el-form-item label="多图"  prop="pics">
                            <el-upload
                                    class="avatar-uploader"
                                    :action="$store.state.constant.upload_url"
                                    multiple
                                    :limit="5"
                                    :file-list="form.pics"
                                    list-type="picture-card"
                                    :on-remove="(file, fileList) => {return handleRemove(file, fileList, 'multi_image', 'pics')}"
                                    :show-file-list="false"
                                    :on-exceed="(files, fileList) => {return handleExceed(files, fileList, 'multi_image', 'pics')}"
                                    :on-success="(res, file, fileList) => {return handleSuccess(res, file, fileList, 'multi_image', 'pics')}"
                                    :before-upload="(file) => {return beforeUpload(file, 'multi_image', 'pics')}">
                                <i class="el-icon-plus avatar-uploader-icon"  ></i>
                                <div slot="tip" class="el-upload__tip">建议尺寸600*400;最多上传5张;每张大小不超过2M</div>
                            </el-upload>

                        </el-form-item>
                        
            </el-form>

            <el-button type="success" style="margin-top: 20px;" v-on:click="submit" :loading="$store.state.loading.goodsedit">提交</el-button>

            </el-card>
        </div>

    </div>
</template>

<script>
    import headTop from '../components/headTop'
    import {common_api} from '@/api/getDataEarth'
    import {deepCopy,goto} from '@/config/mUtils'
    export default {
        name:'goods_edit',
        data(){
            return {
                id:0,
                //select选项
                select_options:{},
                //表单数据
                form: [{"title":""},{"desc":""},{"category":[]},{"pic":""},{"pics":[]}],
                form_init: [{"title":""},{"desc":""},{"category":[]},{"pic":""},{"pics":[]}],
                //rules
                rules: {"title":[{"required":true,"message":"标题不能为空","trigger":"blur"}],"desc":[{"required":true,"message":"内容不能为空","trigger":"blur"}],"category":[{"required":true,"type":"array","message":"分类不能为空","trigger":"blur"}],"pics":[{"required":true,"type":"array","message":"多图不能为空","trigger":"blur"}]},
                goto:goto,
            }

        },
        components: {
            headTop
        },
        created(){

        },
        mounted(){

        },

        beforeRouteEnter (to, from, next) {
            next(async vm => {
                // 通过 `vm` 访问组件实例
                vm.id = to.query.id ? to.query.id : 0;
//                console.log(vm.id )
                //初始化一些select选项
                await vm.init_select_options();
                //初始化数据
                if (vm.id && vm.id > 0) {
                    vm.get_info();
                } else {
                    vm.init();
                }

            })
        },
        methods: {
            async init_select_options() {
                                await common_api('category/list', {}).then((res) => {
                    if (res.code == this.$store.state.constant.status_success) {

                        this.$set(this.select_options, 'categories', res.data);
                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }
                });
                            },
            init() {
                this.form = deepCopy(this.form_init)
            },
            get_info() {
                common_api('goods/info', {id:this.id}).then(function (res) {
                    if (res.code == this.$store.state.constant.status_success) {
                        this.form = deepCopy(res.data);
                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }

                }.bind(this));
            },

            //图像上传
            beforeUpload(file, ele, key){
                const isJPG = file.type === 'image/jpeg' || file.type === 'image/png';

                var fields_obj = {"title":{"key":"title","type":"input","chs_title":"标题","rule":"require"},"desc":{"key":"desc","type":"textarea","chs_title":"内容","rule":"require"},"category":{"key":"category","type":"select","chs_title":"分类","value_type":"array","multi":true,"rule":"require","options":{"items":"category/list","items_vue_ins":"categories","key":"id","value":"id","label":"name"}},"pic":{"key":"pic","type":"single_image","chs_title":"单图","tips":"建议尺寸600*400","max_size":2},"pics":{"key":"pics","type":"multi_image","chs_title":"多图","rule":"require","value_type":"array","max_num":5,"tips":"建议尺寸600*400","max_size":2}};
                const isLt2M = file.size / 1024  < 1024 * fields_obj[key].max_size;

                if (!isJPG) {
                    this.$message.error('只能上传jpg或者png文件!');
                }

                if (!isLt2M) {
                    this.$message.error('文件大小不能超过'+fields_obj[key].max_size + 'M!');
                }
                return  isJPG && isLt2M;
            },

            handleRemove(file, fileList, ele, key) {
                //console.log(file, fileList);
                if (ele == 'single_img') {
                    this.form[key] = '';
                } else if(ele == 'multi_img') {
                    this.form[key] = [];
                    fileList.forEach(function(val){
                        if (val.url) {
                            this.form[key].push(val);
                        } else if (val.response.code == this.$store.state.constant.status_success && val.response.data) {
                            this.form[key].push({url:val.response.data[0],name:val.name,});
                        }
                    }.bind(this));
                    console.log(this.form[key]);
                }

            },
            handleExceed(files, fileList, ele) {
                this.$message.warning('上传数量超过限制!');
            },
            handleSuccess(res, file, fileList, ele, key) {
                if (ele == 'single_img') {
                    this.form[key] = res.data[0];
                } else if(ele == 'multi_img') {
                    this.form[key] = [];
                    fileList.forEach(function(val){
                        if (val.url) {
                            this.form[key].push(val);
                        } else if (val.response.code == this.$store.state.constant.status_success && val.response.data) {
                            this.form[key].push({url:val.response.data[0],name:val.name,});
                        }
                    }.bind(this));
                    console.log(this.form[key]);
                }


            },

            submit: function () {

                var error = false;
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        //alert('submit!');
                    } else {
//                        console.log('error submit!!');
                        error = true;
                        return false;
                    }
                });


                this.$confirm('确认无误, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function(){
                    this.loading = true;
                    common_api('goods/edit', {id:this.id,...this.form}).then(function (res) {
                        if (res.code == this.$store.state.constant.status_success) {
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                            goto({path:'goods/list',query:{refresh:1}});
                        } else {
                            this.$message({
                                message: res.msg,
                                type: 'warning'
                            });
                        }

                    }.bind(this));

                }.bind(this)).catch(() => {

                }).finally(function(){

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
        width: 150px;
        height: 150px;
        line-height: 150px;
        text-align: center;
    }
    .avatar,.el-upload--picture-card {
        width: 150px;
        height: 150px;
        display: block;
    }
</style>
