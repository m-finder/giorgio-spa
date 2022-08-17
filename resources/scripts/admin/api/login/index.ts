import request from '@admin/utils/request';

/**
 * 登录api接口集合
 * @method getCaptcha 获取验证码
 * @method getSmsCode 获取短信验证码
 * @method signIn 用户登录
 * @method signOut 用户退出登录
 */
export function useLoginApi() {
	return {
		getCaptcha: () => {
			return request({
				url: '/admin/captcha',
				method: 'post',
			})
		},
		getSmsCode: (params: object) => {
			return request({
				url: '/admin/verification_code',
				method: 'post',
				data: params,
			})
		},
		signIn: (params: object) => {
			return request({
				url: '/admin/login',
				method: 'post',
				data: params,
			});
		},
		signOut: (params: object) => {
			return request({
				url: '/user/logout',
				method: 'post',
				data: params,
			});
		},
	};
}
