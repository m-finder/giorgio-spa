import { defineStore } from 'pinia';
import Cookies from 'js-cookie';
import { UserInfosStates } from './interface';
import { Session } from '@admin/utils/storage';
import { permissionApi } from '@admin/api/permission';
/**
 * 用户信息
 * @methods setUserInfos 设置用户信息
 */
export const useUserInfo = defineStore('userInfo', {
	state: (): UserInfosStates => ({
		userInfos: {
			userName: '',
			photo: '',
			time: 0,
			authList: [],
		},
	}),
	actions: {
		async setUserInfos() {
			// 存储用户信息到浏览器缓存
			if (Session.get('userInfo')) {
				this.userInfos = Session.get('userInfo');
			} else {
				const userInfos: any = await this.getApiUserInfo();
				this.userInfos = userInfos;
			}
		},
		// 模拟接口数据
		// https://gitee.com/lyt-top/vue-next-admin/issues/I5F1HP
		async getApiUserInfo() {
			return new Promise((resolve) => {

				permissionApi().getPermissionList().then((res?) => {
					const userName = Cookies.get('userName');
					const avatar = Cookies.get('avatar');
					let authList: Array<string> = res.data[0] === '*' ? res?.data[0] : res?.data || [];
					// 用户信息
					const userInfos = {
						userName: userName,
						photo: avatar,
						time: new Date().getTime(),
						authList: authList,
					};
					resolve(userInfos);
				})
			});
		},
	},
});
