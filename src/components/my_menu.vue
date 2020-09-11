<template>

    <div style="background: #272E32;height: 100%;width: 100%;overflow: auto;">
        <div style="height: 110px;text-align: center;color: #fff;font-size: 18px;line-height: 40px;padding-top: 40px;background: rgb(31,37,40)">
            <p style="font-size: 24px;letter-spacing: 5px;">DADADMIN</p>
            <p style="font-size: 15px;margin-left: -8px">基础初始化后台</p>
        </div>
        <el-menu :default-active="defaultActive" style="height: calc(100% - 150px);overflow: auto;border-right: none"
                 background-color="#272E32"
                 text-color="#fff"
                 active-text-color="#409EFF" router>
            <el-scrollbar style="height: 100%;">
                <template  v-for="(item) in menu">
                    <template v-if="item.children">
                        <el-submenu :index="item.uri" :key="item.id">
                            <template slot="title"><i v-if="item.ico" :class="item.ico"></i>{{item.name}}</template>
                            <template v-if="item.children">
                                <el-menu-item style="padding-left: 60px;" v-for="item in item.children" :index="item.uri" :key="item.id">{{item.name}}
                                    <p v-if="$store.state.menu_tips[item.uri] && $store.state.menu_tips[item.uri].daiban_count > 0" style="display:inline-block;background: #F56C6C;line-height:15px;font-size:12px;color: #fff;padding: 1px 5px;border-radius:20px;margin-left: 5px;">{{$store.state.menu_tips[item.uri].daiban_count}}</p>
                                    <p v-if="$store.state.menu_tips[item.uri] &&  $store.state.menu_tips[item.uri].banlizhong_count > 0" style="display:inline-block;background: #E6A23C;line-height:15px;font-size:12px;color: #fff;padding: 1px 5px;border-radius:20px;margin-left: 5px;">{{$store.state.menu_tips[item.uri].banlizhong_count}}</p>
                                </el-menu-item>
                            </template>

                        </el-submenu>
                    </template>
                    <template v-else>
                        <el-menu-item :index="item.uri" :key="item.id"><i v-if="item.ico" :class="item.ico"></i>{{item.name}}</el-menu-item>
                    </template>
                </template>

            </el-scrollbar>
        </el-menu>
    </div>
</template>

<script>
    import {get_menu,common_api} from '@/api/getDataEarth'

    import {mapActions, mapState} from 'vuex'
    import {removeStore,getStore} from '@/config/mUtils'


    export default {
        data(){
            return {
                app_name:this.$store.state.APP_NAME,
                menu:[],
                showHelp:false,
                user_info:'',
                dialogFormVisible:false,
            }

        },
        created(){
            this.get_menu();
            // common_api('index/getMenuTipsCount').then((res)=>{
            //     this.$store.commit('set_menu_tips',res.data)
            //
            // })
            this.user_info = JSON.parse(getStore('user_info'));
        },
        components: {

        },
        methods: {
            get_menu(){
                get_menu().then(function (res) {
                    if (res.code == this.$store.state.constant.status_success) {
                        res.data.forEach(function(ele){
                            ele.uri = ele.uri.replace('menu_', '');
                            if (ele.children) {
                                ele.children.forEach(function(e){
                                    e.uri = e.uri.replace('menu_', '');
                                })
                            }
                        })
                        this.menu = res.data;
                    } else {
                        this.$message({
                            message: res.msg,
                            type: 'warning'
                        });
                    }

                }.bind(this));
            },
        },
        computed: {
            defaultActive: function(){
                var index = this.$route.path.replace('/', '');

                this.$store.commit('set_page_breadcrumb', this.$route.meta.path)
                if (this.$route.meta._menu) {
                    index = this.$route.meta._menu;
                }
                return index;
            },
            ...mapState(['adminInfo']),
        },
    }
</script>


<style lang="less" scoped>
    @import '../style/mixin';
    .iconfont{
        margin-right: 5px;
        width: 24px;
        text-align: center;
        font-size: 18px;
    }
    /*.el-menu-item{*/
    /*background-color: rgb(67, 74, 80)!important;*/
    /*}*/
    /*.el-menu-item:hover{*/
    /*background-color: rgb(60, 70, 80)!important;*/
    /*}*/
    /*.el-menu-item.is-active{*/
    /*background-color: #eee!important;*/
    /*}*/
    img.kfformula {
        vertical-align: middle;
    }
    .logo-title{
        color: #303133;
        font-size: 24px;
        font-weight: bold;
        float: left;
    }
    .admin-info{
        float: right;
    }
    .menu-title{
        color: #303133;
        font-size: 16px;
        font-weight: bolder;
        text-align: center;
    }
    .blank{
        height:20px
    }
    .header_container{
        margin-left: 20px;
        margin-bottom: 10px;
        color: #999;
    }
</style>
