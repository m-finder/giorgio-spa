<template>
	<el-form :model="dataForm" ref="dataFormRef" :rules="rules" size="large" class="login-content-form">
		<el-form-item class="login-animation1" prop="account">
			<el-input type="text" :placeholder="$t('message.mobile.placeholder1')" v-model="dataForm.account" clearable autocomplete="off">
				<template #prefix>
					<i class="iconfont icon-dianhua el-input__icon"></i>
				</template>
			</el-input>
		</el-form-item>
    <el-form-item class="login-animation3" prop="captcha">
      <el-col :span="15">
        <el-input type="text" maxlength="4" :placeholder="$t('message.account.accountPlaceholder3')" v-model="dataForm.captcha" clearable autocomplete="off">
          <template #prefix>
            <el-icon class="el-input__icon">
              <ele-Position/>
            </el-icon>
          </template>
        </el-input>
      </el-col>
      <el-col :span="1"></el-col>
      <el-col :span="8">
        <el-button class="login-content-code" @click="getCaptcha()">
          <el-image v-loading="captchaLoading" :src="captchaPath" fit="fill"></el-image>
        </el-button>
      </el-col>
    </el-form-item>
		<el-form-item class="login-animation2" prop="sms_code">
			<el-col :span="15">
				<el-input type="text" maxlength="4" :placeholder="$t('message.mobile.placeholder2')" v-model="dataForm.sms_code" clearable autocomplete="off">
					<template #prefix>
						<el-icon class="el-input__icon"><ele-Position /></el-icon>
					</template>
				</el-input>
			</el-col>
			<el-col :span="1"></el-col>
			<el-col :span="8">
				<el-button class="login-content-code" :loading="loading.smsLoading" :disabled="smsBtnStatus" @click="getSmsCode">
          {{ smsBtnStatus ? $t('message.mobile.resendCodeText') + smsNum : $t('message.mobile.codeText') }}
        </el-button>
			</el-col>
		</el-form-item>
		<el-form-item class="login-animation3">
			<el-button round type="primary" class="login-content-submit" @click="onSignIn" :loading="loading.signIn">
				<span>{{ $t('message.mobile.btnText') }}</span>
			</el-button>
		</el-form-item>
		<div class="font12 mt30 login-animation4 login-msg">{{ $t('message.mobile.msgText') }}</div>
	</el-form>
</template>

<script lang="ts">
import {toRefs, reactive, defineComponent, getCurrentInstance, onMounted, computed} from 'vue';
import {useLoginApi} from "@admin/api/login";
import {Session} from "@admin/utils/storage";
import Cookies from "js-cookie";
import logo from "@admin/assets/logo.png";
import {initFrontEndControlRoutes} from "@admin/router/frontEnd";
import {initBackEndControlRoutes} from "@admin/router/backEnd";
import {NextLoading} from "@admin/utils/loading";
import {formatAxis} from "@admin/utils/formatTime";
import {useI18n} from "vue-i18n";
import {useThemeConfig} from "@admin/stores/themeConfig";
import {storeToRefs} from "pinia";
import {useRoute, useRouter} from "vue-router";

// 定义接口来定义对象的类型
interface LoginMobileState {
	account: any;
	sms_code: string | number | undefined;
  captcha_key: string | undefined;
  captcha: string | number | undefined;
  is_password: boolean;
}

// 定义对象与类型
const dataForm: LoginMobileState = {
  account: '18016391011',
	sms_code: '',
  captcha_key: '',
  captcha: '',
  is_password: false
};

export default defineComponent({
	name: 'loginMobile',
	setup() {
    const {t} = useI18n();
    const storesThemeConfig = useThemeConfig();
    const {themeConfig} = storeToRefs(storesThemeConfig);
    const {proxy} = <any>getCurrentInstance();
    const route = useRoute();
    const router = useRouter();
		const state = reactive({
      dataForm,
      loading: {
        signIn: false,
        smsLoading: false,
      },
      captchaLoading: false,
      captchaPath: undefined,
      time: {
        txt: '',
        fun: null,
      },
      smsBtnStatus: false,
      smsNum: 60,
      rules: {
        account: {required: true, message: '请输入手机号', trigger: 'blur'},
        password: {required: true, message: '请输入登录密码', trigger: 'blur'},
        captcha: {required: true, message: '请输入验证码', trigger: 'blur'},
        sms_code: {required: true, message: '请输入短信验证码', trigger: 'blur'},
      },
		});

    // 时间获取
    const currentTime = computed(() => {
      return formatAxis(new Date());
    });

    // 页面加载时
    onMounted(() => {
      getCaptcha();
    });

    const loginApi = useLoginApi();
    // 获取验证码
    const getCaptcha = () => {
      if (state.dataForm.captcha_key) {
        state.dataForm.captcha_key = undefined
      }
      state.captchaLoading = true
      loginApi.getCaptcha().then((res) => {
        state.captchaLoading = false
        state.dataForm.captcha_key = res.data.key
        state.captchaPath = res.data.img
      }).catch((err?) => {
        state.captchaLoading = false
        proxy.$message.error(err.msg);
      })
    };
    // 获取短信验证码
    const getSmsCode = () => {
      if (!state.dataForm.account) {
        proxy.$message.warning('手机号尚未输入')
        return
      }
      state.loading.smsLoading = true
      loginApi.getSmsCode({
        account: state.dataForm.account,
        is_login: true
      }).then((res?) => {
        state.loading.smsLoading = false
        setSmsBtnStatus()
      }).catch(err => {
        state.loading.smsLoading = false
        proxy.$notify.error({
          title: '错误',
          message: err.msg
        })
      })
    };
    // 发送倒计时
    const setSmsBtnStatus = () => {
      let myTime = setInterval(function () {
        if (state.smsNum !== 0) {
          state.smsNum = state.smsNum - 1
          state.smsBtnStatus = true
        } else {
          state.smsBtnStatus = false
          state.smsNum = 60
          clearInterval(myTime)
        }
      }, 1000)
    };
    // 登录
    const onSignIn = () => {
      proxy.$refs.dataFormRef.validate((valid: boolean) => {
        if (valid) {

          state.loading.signIn = true;
          loginApi.signIn(state.dataForm).then(async (res?) => {
            // 存储 token 到浏览器缓存
            Session.set('token', res.data.token);
            // 用于 `/src/stores/userInfo.ts` 中不同用户登录判断（模拟数据）
            Cookies.set('userName', res.data.admin.name);
            Cookies.set('avatar', res.data.admin.avatar || logo);
            // 前端控制路由，2、请注意执行顺序
            await initFrontEndControlRoutes();
            signInSuccess();
          }).catch((err?) => {
            state.loading.signIn = false;
            proxy.$message.error(err.msg);
            console.log(err)
          })

        } else {
          return false;
        }
      });


    };
    // 登录成功后的跳转
    const signInSuccess = () => {
      // 初始化登录成功时间问候语
      let currentTimeInfo = currentTime.value;
      // 登录成功，跳到转首页
      // 如果是复制粘贴的路径，非首页/登录页，那么登录成功后重定向到对应的路径中
      if (route.query?.redirect) {
        router.push({
          path: <string>route.query?.redirect,
          query: Object.keys(<string>route.query?.params).length > 0 ? JSON.parse(<string>route.query?.params) : '',
        });
      } else {
        router.push('/');
      }
      // 登录成功提示
      // 关闭 loading
      state.loading.signIn = true;
      const signInText = t('message.signInText');
      proxy.$message.success(`${currentTimeInfo}，${signInText}`);
      // 添加 loading，防止第一次进入界面时出现短暂空白
      NextLoading.start();
    };

		return {
      getCaptcha,
      getSmsCode,
      onSignIn,
			...toRefs(state),
		};
	},
});
</script>

<style scoped lang="scss">
.login-content-form {
	margin-top: 20px;
	@for $i from 1 through 4 {
		.login-animation#{$i} {
			opacity: 0;
			animation-name: error-num;
			animation-duration: 0.5s;
			animation-fill-mode: forwards;
			animation-delay: calc($i/10) + s;
		}
	}
	.login-content-code {
		width: 100%;
		padding: 0;
	}
	.login-content-submit {
		width: 100%;
		letter-spacing: 2px;
		font-weight: 300;
		margin-top: 15px;
	}
	.login-msg {
		color: var(--el-text-color-placeholder);
	}
}
</style>
