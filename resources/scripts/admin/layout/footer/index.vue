<template>
	<div class="layout-footer mt15" v-show="isDelayFooter">
		<div class="layout-footer-warp">
			<div>
       <span>{{ getThemeConfig.globalCompanyName }} 版权所有 </span>
        <span style="color: #e27575;font-size: 14px;">❤</span></div>
		</div>
	</div>
</template>

<script lang="ts">
import {toRefs, reactive, defineComponent, computed} from 'vue';
import { onBeforeRouteUpdate } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useThemeConfig } from '@admin/stores/themeConfig';

export default defineComponent({
	name: 'layoutFooter',
	setup() {
    const storesThemeConfig = useThemeConfig();
    const { themeConfig } = storeToRefs(storesThemeConfig);
    // 获取布局配置信息
    const getThemeConfig = computed(() => {
      return themeConfig.value;
    });
		const state = reactive({
			isDelayFooter: true,
		});
		// 路由改变时，等主界面动画加载完毕再显示 footer
		onBeforeRouteUpdate(() => {
			setTimeout(() => {
				state.isDelayFooter = false;
				setTimeout(() => {
					state.isDelayFooter = true;
				}, 800);
			}, 0);
		});
		return {
      getThemeConfig,
			...toRefs(state),
		};
	},
});
</script>

<style scoped lang="scss">
.layout-footer {
	width: 100%;
	display: flex;
	&-warp {
		margin: auto;
		color: var(--el-text-color-secondary);
		text-align: center;
		animation: error-num 1s ease-in-out;
	}
}
</style>
