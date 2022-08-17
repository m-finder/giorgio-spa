import axios from 'axios';
import { ElMessage, ElMessageBox } from 'element-plus';
import { Session } from '@admin/utils/storage';

// 配置新建一个 axios 实例
const service = axios.create({
	baseURL: import.meta.env.VITE_API_URL as any,
	timeout: 50000,
	headers: { 'Content-Type': 'application/json' },
});

// 添加请求拦截器
service.interceptors.request.use(
	(config) => {
		// 在发送请求之前做些什么 token
		if (Session.get('token')) {
			(<any>config.headers).common['Authorization'] = 'Bearer ' + `${Session.get('token')}`;
		}
		return config;
	},
	(error) => {
		// 对请求错误做些什么
		return Promise.reject(error);
	}
);

// 添加响应拦截器
service.interceptors.response.use((response) => {
		// 对响应数据做点什么
		const res = response.data;
		if (res.code && res.code !== 200) {
			// `token` 过期或者账号已在别处登录
			if (res.code === 401 || res.code === 4001) {
				Session.clear(); // 清除浏览器全部临时缓存
				window.location.href = '/admin'; // 去登录页
				ElMessageBox.alert('你已被登出，请重新登录', '提示', {}).then(() => {}).catch(() => {});
			} else if (res.code === 429) {
				ElMessageBox.alert('请求次数过多，请稍后', '提示', {
					type: 'warning',
					showClose: false,
					closeOnClickModal: false,
					closeOnPressEscape: false,
				}).then(() => {})
			}
			return Promise.reject(res);
		} else {
			return res;
		}
	}, (error) => {
		// 对响应错误做点什么
		if (error.message.indexOf('timeout') != -1) {
			ElMessage.error('网络超时');
		} else if (error.message == 'Network Error') {
			ElMessage.error('网络连接错误');
		} else {
			if (error.response.data) ElMessage.error(error.response.data.msg);
			else ElMessage.error('接口路径找不到');
		}
		return Promise.reject(error);
	}
);

// 导出 axios 实例
export default service;
