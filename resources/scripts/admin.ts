import {createApp} from 'vue';
import App from '@admin/App.vue';
import {directive} from '@admin/utils/directive/';
import pinia from "@admin/stores";
import router from '@admin/router';
import {i18n} from '@admin/i18n';
import other from '@admin/utils/other';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import '@admin/theme/index.scss';
import mitt from 'mitt';
import VueGridLayout from 'vue-grid-layout';
import zhCn from "element-plus/es/locale/lang/zh-cn";

const app = createApp(App);

directive(app);
other.elSvg(app);

app.use(pinia)
    .use(router)
    .use(ElementPlus, {
        i18n: i18n.global.t,
        locale: zhCn
    }).use(i18n)
    .use(VueGridLayout)
    .mount('#app');

app.config.globalProperties.mittBus = mitt();
app.config.warnHandler = () => null;
