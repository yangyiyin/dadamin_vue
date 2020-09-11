<template>
	<div class="manage_page fillcontain">
		<el-container style="height: 100%;margin: 0 auto">
			<el-container style="height: 100%;">
				<el-aside width="300px" style="height: 100%;">
					<el-col style="height: 100%; width: 300px">
						<!--<el-menu :default-active="defaultActive" style="height: 100%;"-->
								 <!--background-color="#545c64"-->
								 <!--text-color="#fff"-->
								 <!--active-text-color="#ffd04b" router>-->
							<!--<el-scrollbar style="height: 100%;">-->
								<!--<template  v-for="(item) in menu">-->
									<!--<template v-if="item.children">-->
										<!--<el-submenu :index="item.uri" :key="item.id">-->
											<!--<template slot="title"><i class="iconfont el-icon-cc" v-html="item.ico"></i>{{item.name}}</template>-->
											<!--<template v-if="item.children">-->
												<!--<el-menu-item style="padding-left: 60px;" v-for="item in item.children" :index="item.uri" :key="item.id">{{item.name}}</el-menu-item>-->
											<!--</template>-->

										<!--</el-submenu>-->
									<!--</template>-->
									<!--<template v-else>-->
										<!--<el-menu-item :index="item.uri" :key="item.id">{{item.name}}</el-menu-item>-->
									<!--</template>-->
								<!--</template>-->

							<!--</el-scrollbar>-->
						<!--</el-menu>-->
						<mymenu></mymenu>
					</el-col>
				</el-aside>
				<el-main style="padding: 0">
					<el-header style="height:70px;line-height: 70px;">
						<!--<p class="logo-title" style="color: #EFF3F6">{{app_name}}</p>-->

						<el-breadcrumb separator="/" style="float: left;line-height: 70px;opacity: 0.5">
							<el-breadcrumb-item v-for="item in $store.state.page_breadcrumb">{{item}}</el-breadcrumb-item>
						</el-breadcrumb>

						<el-dropdown trigger="click" size="medium" @command="handleCommand" menu-align='start' class="admin-info">
							<!--<img :src="avatar" class="avator">-->
							<p style="cursor: pointer;border-bottom: 1px solid #eee" >
								<el-avatar :size="20" :src="avatar" style="vertical-align: middle;margin-right: 10px"></el-avatar>
								<span>{{user_info.show_name}}</span></p>
							<el-dropdown-menu slot="dropdown" >
								<el-dropdown-item command="show_edit_password">修改密码</el-dropdown-item>
								<el-dropdown-item command="singout" >退出</el-dropdown-item>
							</el-dropdown-menu>
						</el-dropdown>

					</el-header>
					<!--<quicktabs v-if="$store.state.constant.enable_tabs" ref="quicktabs"></quicktabs>-->
					<div style="height: calc(100% - 71px);overflow: auto;width: 100%;position: relative">
						<div :style="'position: absolute;opacity: 0.2;z-index: 0;width:100%;height:100%;background-size:200px 200px;background-image: url('+$store.state.baseAdminUrl+'/index.php/apiback/index/textpng?str='+user_info.show_name+')'">

						</div>
						<div style="padding: 30px;position: relative">
							<el-scrollbar :style="'height:100%'">
								<keep-alive :include="$store.state.keepalive_include">
									<router-view></router-view>
								</keep-alive>
							</el-scrollbar>
						</div>
					</div>
				</el-main>
			</el-container>
		</el-container>
		<!--<div @click="showHelp=true" style="position: fixed;cursor:pointer;left: 0px;bottom:0px;z-index: 100;padding: 5px;">-->
			<!--<i class="iconfont" style="color: #303133;font-size: 20px;">&#xe694;</i>-->
		<!--</div>-->
		<el-dialog
				title="帮助与更新"
				:visible.sync="showHelp"
				width="80%">
			<div>
				<help></help>
			</div>
			<div slot="footer" class="dialog-footer">
				<el-button @click="showHelp = false">关 闭</el-button>
			</div>
		</el-dialog>

		<el-dialog center title="修改密码" :visible.sync="dialogFormVisible" width="30%">
			<div style="padding: 0 30px">
				<el-form :model="form" label-width="100px" :rules="rules" ref="form">
					<el-form-item label="原密码:" prop="old_password">
						<el-input type="password" v-model="form.old_password" auto-complete="off"></el-input>
					</el-form-item>
					<el-form-item label="新密码:" prop="password">
						<el-input type="password" v-model="form.password" auto-complete="off"></el-input>
					</el-form-item>
					<el-form-item label="确认新密码:" prop="again_password">
						<el-input type="password" v-model="form.again_password" auto-complete="off"></el-input>
					</el-form-item>
				</el-form>
				<div style="text-align: center;margin-top: 50px" slot="footer" class="dialog-footer">
					<el-button @click="dialogFormVisible = false">取 消</el-button>
					<el-button type="primary" @click="edit_password">确 定</el-button>
				</div>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	import {get_menu,admin_user_edit} from '@/api/getDataEarth'
	import help from '@/components/help'
	import quicktabs from '@/components/quicktabs'
	import mymenu from '@/components/my_menu'

	import {mapActions, mapState} from 'vuex'
	import {removeStore,getStore} from '@/config/mUtils'
	import avatar from '@/assets/img/default_avatar.png';

	export default {
		data(){
			return {
				app_name:this.$store.state.APP_NAME,
				menu:[],
				showHelp:false,
				avatar,
				user_info:'',
				dialogFormVisible:false,
				password:'',
				old_password:'',
				again_password:'',
				form:{
					password:'',
					old_password:'',
					again_password:'',
				},
				rules: {
					password: [{ required: true, message: '请输入新密码', trigger: 'blur' },],
					old_password: [{ required: true, message: '请输入原密码', trigger: 'blur' },],
					again_password: [{ required: true, message: '请输入确认新密码', trigger: 'blur' },]
				}
			}

		},
		created(){
			// this.get_menu();
			this.user_info = JSON.parse(getStore('user_info'));
		},
		components: {
			help,
			quicktabs,
			mymenu
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
			...mapActions(['getAdminData']),
			async handleCommand(command) {
				if (command == 'home') {
					this.$router.push('/manage');
				}else if(command == 'singout'){
					removeStore('token');
					this.$message({
						type: 'success',
						message: '退出成功'
					});
					this.$router.push('/?not_in_dingding='+(window.is_in_dingding ? 0 : 1));
				} else if(command == 'show_edit_password'){
					this.dialogFormVisible = true;
					this.form.old_password = '';
					this.form.password = '';
					this.form.again_password = '';
				}
			},
			edit_password() {
				if (this.form.password != this.form.again_password) {
					this.$message({
						message: '2次密码输入不一致，请重新确认输入',
						type: 'warning'
					});
					return;
				}
				var error = false;
				this.$refs['form'].validate((valid) => {
					if (valid) {
					} else {
						error = true;
						return false;
					}
				});
				// console.log(error)
				if (error) {
					return false;
				}
				admin_user_edit({id:this.user_info.id,old_password:this.form.old_password,password:this.form.password}).then(function (res) {
					if (res.code == this.$store.state.constant.status_success) {
						this.$message({
							message: res.msg,
							type: 'success'
						});
						this.dialogFormVisible = false;
					} else {
						this.$message({
							message: res.msg,
							type: 'warning'
						});
					}

				}.bind(this));
			}
		},
		computed: {
			// defaultActive: function(){
			// 	var index = this.$route.path.replace('/', '');
			// 	if (this.$route.meta._menu) {
			// 		index = this.$route.meta._menu;
			// 	}
			// 	return index;
			// },
			// ...mapState(['adminInfo']),
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
