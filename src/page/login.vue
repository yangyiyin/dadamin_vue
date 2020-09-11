<template>
  	<div class="login_page fillcontain" style="background: rgb(31,37,40)">
		<div style="position: absolute;top:0;width:100%;height: 100%;overflow: hidden;text-align: center;display: flex;flex-direction: column">
			<!--<img :src="login_bg" style="mix-blend-mode: lighten;width: 100%;height:100%;opacity: 1"/>-->


		</div>
	  	<transition v-if="$store.state.is_pc" name="form-fade" mode="in-out">
	  		<section v-on:keyup.13="submitForm('loginForm')" class="form_contianer" v-show="showLogin" style="background: none;width: 350px;margin-left: -175px;padding: 0">

				<div style="width: 800px;height:800px;opacity: 0.1;background: #666;position: absolute;top:-200px;left: -225px;z-index: 0"></div>

				<div class="manage_tip" style="color: #fff;text-shadow: 2px 2px 0px #000;position: relative;z-index: 1">
					<p style="line-height: 1rem;color: #fff;font-size: 40px;letter-spacing: 7px;">DADADMIN</p>

				</div>

				<div class="table_container" style="width: 100%;margin: 0 auto;margin-top: 40px;">
					<el-form :model="loginForm" :rules="rules" ref="loginForm">
						<el-form-item prop="username">
							<el-input class="login-input" autocomplete="new-password" prefix-icon="el-icon-user" v-model="loginForm.username" placeholder="用户名"></el-input>
						</el-form-item>
						<el-form-item prop="password">
							<el-input class="login-input" autocomplete="new-password" prefix-icon="el-icon-lock" type="password" placeholder="密码" v-model="loginForm.password"></el-input>
						</el-form-item>
						<el-form-item style="margin-bottom: 0" >
							<el-button  type="primary" @click="submitForm('loginForm')" class="submit_btn login-input-button">登陆</el-button>
						</el-form-item>
					</el-form>
				</div>
	  		</section>
	  	</transition>

		<transition v-if="!$store.state.is_pc" name="form-fade" mode="in-out">
			<div style="display: flex;width: 100%;height:100%;flex-direction: column">
				<div v-on:keyup.13="submitForm('loginForm')" style="width: 20rem;margin: auto;" v-show="showLogin">
					<div class="table_container" style="padding: 1rem;width: auto;text-align: center">
						<div class="manage_tip">
							<p style="font-size: 1.5rem;font-weight: bolder">{{app_name}}</p>
						</div>
						<el-form :model="loginForm" :rules="rules" ref="loginForm">
							<el-form-item prop="username">
								<el-input  v-model="loginForm.username" placeholder="用户名"><span>dsfsf</span></el-input>
							</el-form-item>
							<el-form-item prop="password">
								<el-input type="password" placeholder="密码" v-model="loginForm.password"></el-input>
							</el-form-item>
							<el-form-item>
								<el-button style="width: 100%" type="primary" @click="submitForm('loginForm')" class="submit_btn">登陆</el-button>
							</el-form-item>
						</el-form>
					</div>
				</div>
			</div>
		</transition>
  	</div>
</template>

<script>
	import {login} from '@/api/getData'
	import {mapActions, mapState} from 'vuex'
	import {setStore, getStore} from '@/config/mUtils'
	import login_bg from '@/assets/img/login_bg.jpg'
	export default {
	    data(){
			return {
				login_bg,
				app_name:this.$store.state.APP_NAME,
				loginForm: {
					username: '',
					password: '',
				},
				rules: {
					username: [
			            { required: true, message: '请输入用户名', trigger: 'blur' },
			        ],
					password: [
						{ required: true, message: '请输入密码', trigger: 'blur' }
					],
				},
				showLogin: false,
			}
		},
		mounted(){
			this.showLogin = true;
			if (getStore('token')) {
				if (this.$store.state.is_pc) {

					this.$router.push('manage')
				} else {
					this.$router.push('manage_mobile')
				}
    		}
		},
		computed: {
			...mapState(['adminInfo']),
		},
		methods: {
			...mapActions(['getAdminData']),
			async submitForm(formName) {
				this.$refs[formName].validate(async (valid) => {
					if (valid) {
//						alert(this.loginForm.username);
						const res = await login({user_name: this.loginForm.username, password: this.loginForm.password})
//						alert(res);
						if (res.code == this.$store.state.constant.status_success) {

							setStore('token', res.data.token);
							setStore('user_info', res.data.user_info);
							this.$message({
		                        type: 'success',
		                        message: '登录成功'
		                    });
							this.$store.commit('set_role', parseInt(res.data.user_info.group_id))
							if (this.$store.state.is_pc) {

								this.$router.push('manage')
							} else {
								this.$router.push('manage_mobile')
							}
						}else{
							this.$message({
		                        type: 'error',
		                        message: res.msg
		                    });
						}
					} else {
						this.$message({
							type: 'error',
							message: '请输入正确的用户名密码'
						});
						return false;
					}
				});
			},
		},
		watch: {
			adminInfo: function (newValue){
				if (newValue.id) {
					this.$message({
                        type: 'success',
                        message: '检测到您之前登录过，将自动登录'
                    });
					this.$router.push('manage')
				}
			}
		}
	}
</script>

<style lang="less" scoped>
	@import '../style/mixin';
	.login_page{
		/*background-color: #324057;*/
	}
	.manage_tip{

		width: 100%;
		margin-bottom: 30px;
		p{
			font-size: 34px;
			color: #303133;
		}
	}
	.form_contianer{
		.wh(520px, 210px);
		.ctp(520px, 210px);
		padding: 25px;
		border-radius: 5px;
		text-align: center;
		background-color: #fff;
		top:30%;
		.submit_btn{
			width: 100%;
			font-size: 16px;
		}
	}
	.tip{
		font-size: 12px;
		color: red;
	}
	.form-fade-enter-active, .form-fade-leave-active {
	  	transition: all 1s;
	}
	.form-fade-enter, .form-fade-leave-active {
	  	transform: translate3d(0, -50px, 0);
	  	opacity: 0;
	}
</style>
