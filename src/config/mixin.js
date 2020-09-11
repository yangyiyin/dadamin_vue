/**
 * 存储localStorage
 */
import {common_api} from '@/api/getDataEarth'
import {deepCopy,goto} from '@/config/mUtils'
export const mixin_edit = {
    data: function () {
        return {
            id:0,
            //select选项
            select_options:{},
            goto: goto,
        }
    },
    beforeRouteLeave (to, from, next) {
        if (this.getUeContent) {
            this.getUeContent();
        }
        next();
    },
    methods: {
        async get_info() {
            await common_api(this.module_name+'/info', {id:this.id}).then(function (res) {
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
        async init_select_options() {

        },
        async init(id) {

            this.id = id || 0;
            this.inited = this.inited || {};

            await this.init_select_options();


            if (this.id && this.id > 0) {
                await this.get_info();
            } else {
                this.form = deepCopy(this.form_init);
            }

            //如果有ue,则更新ue内容
            for(var i in this.fields_obj) {
                if (this.fields_obj[i].type == 'ue') {
                    this.$refs[this.fields_obj[i].key].init(this.form[this.fields_obj[i].key])
                }
            }
            
            this.inited = {}
            this.inited[this.id] = true;


            // if (!this.inited[this.id]) {
            //     await this.init_select_options();
            //     if (this.id && this.id > 0) {
            //         this.get_info();
            //     } else {
            //         this.form = deepCopy(this.form_init);
            //     }
            //     this.inited = {};
            //     this.inited[this.id] = true;
            // }


        },

        beforeRouteLeave (to, from, next) {
            // 导航离开该组件的对应路由时调用
            // 可以访问组件实例 `this`
            if (this.getUeContent) {
                this.getUeContent();
            }
        },

        //图像上传
        beforeUpload(file, ele, key, fields_obj){
            const isJPG = file.type === 'image/jpeg' || file.type === 'image/png';

            // var fields_obj = fields_obj;
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
            if (ele == 'single_image') {
                this.form[key] = '';
            } else if(ele == 'multi_image') {
                var list = [];
                fileList.forEach(function(val){
                    if (val.url && !val.response) {
                        list.push(val);
                    } else if (val.response.code == this.$store.state.constant.status_success && val.response.data) {
                        list.push({url:val.response.data[0],name:val.name,});
                    }
                }.bind(this));
                this.form[key] = list;
                console.log(this.form[key]);
            }

        },
        handleExceed(files, fileList, ele) {
            this.$message.warning('上传数量超过限制!');
        },
        handleSuccess(res, file, fileList, ele, key) {
            if (ele == 'single_image') {
                // this.form[key] = res.data[0];
                this.$set(this.form, key, res.data[0]);
            } else if(ele == 'multi_image') {
                var list = [];
                fileList.forEach(function(val){
                    if (val.url && !val.response) {
                        list.push(val);
                    } else if (val.response.code == this.$store.state.constant.status_success && val.response.data) {
                        list.push({url:val.response.data[0],name:val.name,});
                    }
                }.bind(this));
                this.form[key] = list;
                // console.log(this.form[key]);
            }

            console.log(this.form);
        },
        submit: function (url) {
            if (this.beforeSubmit) {
                this.beforeSubmit();
            }
            if (this.getUeContent) {
                this.getUeContent();
            }
// return;
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
            if (error) {
                return false;
            }

            this.$confirm('确认无误, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(function(){
                this.loading = true;
                common_api(this.module_name+'/edit', {id:this.id,...this.form}).then(function (res) {
                    if (res.code == this.$store.state.constant.status_success) {
                        this.$message({
                            message: res.msg,
                            type: 'success'
                        });
                        goto('/'+this.module_name+'_list',{refresh:1});
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

export const mixin_list = {
    data: function () {
        return {
            tableData: [],
            tableDataTree: [],
            limit: 10,
            count: 0,
            currentPage: 1,
            dialogFormVisible:false,
            current:{},
            search_data:{},
            goto: goto,
            select_options:{},
        }
    },
    methods: {
        list() {
            common_api(this.module_name+'/index', {page:this.currentPage,page_size:this.limit,...this.search_data}).then(function(res){
                if (res.code == this.$store.state.constant.status_success) {
                    this.tableData = res.data.list;
                    this.count = parseInt(res.data.count);
                } else {
                    this.$message({
                        type: 'warning',
                        message: res.msg
                    });
                }
            }.bind(this));
        },
        list_tree() {
            common_api(this.module_name+'/tree', {page:this.currentPage,page_size:this.limit,...this.search_data}).then(function(res){
                if (res.code == this.$store.state.constant.status_success) {
                    this.tableDataTree = res.data;
                } else {
                    this.$message({
                        type: 'warning',
                        message: res.msg
                    });
                }
            }.bind(this));

        },
        handleCurrentChange(val){
            this.currentPage = val;
            this.list();
        },
        search() {
            this.currentPage = 1;
            this.list();
        },
        verify(scope, status) {
            this.doing_id = scope.row.id;
            this.$confirm('确认此操作?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(function(){
                var item = scope.row;
                common_api(this.module_name+'/verify', {id:item.id,status:status}).then(function(res){
                    if (res.code == this.$store.state.constant.status_success) {
                        this.list();
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

                }.bind(this));
            }.bind(this));



        },
        del(item, index) {

            this.$confirm('此操作将永久删除该条数据, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(function(){
                common_api(this.module_name+'/del', {id:item.id}).then(function(res){
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
            common_api(this.module_name+'/sort', {
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
        }
    }
}