<template>
    <section class="nav-wrap">
        <div class="header navbar">
            <button class="d-lg-none navbar-toggler" type="button" display="md" mobile @click="toggleMobileSideBar"
                    v-menuClose="closeMenu">
                <svg-vue class="nav-status " icon="menu"/>
            </button>

            <button class="bv-d-md-down-none navbar-toggler pc-navbar" type="button" display="lg" @click="toggleSideBar">
                <svg-vue class="nav-status " icon="menu"/>
            </button>

            <button class="navbar-toggler bv-d-md-down-none" display="md" type="button" @click="refresh">
                <svg-vue class="nav-status " icon="refresh"/>
            </button>

            <bread-crumb class="mr-auto p-0 bv-d-md-down-none" display="md" :list="list" :homePage="config.homePage"
                         :homeUrl="config.homeUrl"/>

            <b-navbar class="justify-content-end p-0">
                <b-navbar-nav>
<!--                    <b-nav-item class="bv-d-md-down-none">-->
<!--                        <svg-vue icon="message"/>-->
<!--                        <b-badge pill variant="danger">5</b-badge>-->
<!--                    </b-nav-item>-->

                    <b-nav-item-dropdown class=" ml-2 mr-2" right>
                        <template v-slot:button-content>
                            <img :src="userInfo.avatar || '/images/avatar.png'" class="img-avatar" alt=""/>
                        </template>
                        <b-dropdown-item to="/reset/info">资料设置</b-dropdown-item>
                        <b-dropdown-item to="/reset/password">密码设置</b-dropdown-item>
                        <b-dropdown-item @click="logout">退出登陆</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <b-nav-item @click="showSideBar = !showSideBar" v-themeClose="close">
                        <svg-vue icon="setting"/>
                    </b-nav-item>
                </b-navbar-nav>
            </b-navbar>
        </div>
        <theme :show="showSideBar"/>
    </section>
</template>
<script>
    import {mapGetters} from 'vuex'
    import storage from '../../../utils/storage'
    import BreadCrumb from '../../../components/breadcrumb/Index'
    import Theme from './theme'
    import config from "../../../config/config";

    export default {
        name: 'Navbar',
        components: {
            'bread-crumb': BreadCrumb,
            'theme': Theme
        },
        data() {
            return {
                userInfo: storage.get('user-info') || storage.sessionGet('user-info'),
                showSideBar: false,
                config: config
            }
        },
        computed: {
            ...mapGetters([
                'sidebar',
            ]),
            isCollapse() {
                return this.sidebar.opened
            },
            isMobile() {
                return this.sidebar.mobileOpened
            },
            name() {
                return this.$route.name
            },
            list() {
                return this.$route.matched.filter((route) => route.name || route.meta.label)
            }
        },
        directives: {
            // themeClose,
            // menuClose
            themeClose: {
                bind(el, binding, vnode) {
                    function documentHandler(e) {
                        let theme = document.getElementsByClassName('theme-box')[0];
                        if (el.contains(e.target) || theme.contains(e.target)) {
                            return false;
                        }
                        if (binding.expression) {
                            binding.value(e);
                        }
                    }

                    el.__vueClickOutside__ = documentHandler;
                    document.addEventListener('click', documentHandler);
                },
                unbind(el, binding) {
                    document.removeEventListener('click', el.__vueClickOutside__);
                    delete el.__vueClickOutside__;
                },
            },
            menuClose: {
                bind(el, binding, vnode) {
                    function documentHandler(e) {
                        let tag = document.getElementsByClassName('mobile-side-bar')[0].getElementsByClassName('dropdown-toggle')[0];
                        if (el.contains(e.target) || tag.contains(e.target)) {
                            return false;
                        }
                        if (binding.expression) {
                            binding.value(e);
                        }
                    }

                    el.__vueClickOutside__ = documentHandler;
                    document.addEventListener('click', documentHandler);
                },
                unbind(el, binding) {
                    document.removeEventListener('click', el.__vueClickOutside__);
                    delete el.__vueClickOutside__;
                },
            }
        },
        methods: {
            closeMenu() {
                this.$store.dispatch('app/closeMobileSideBar')
            },
            close() {
                this.showSideBar = false
            },
            logout() {
                storage.remove('user-info') && storage.sessionRemove('user-info');
                storage.remove('token') && storage.sessionRemove('token');
                this.$router.push({path: '/login'})
            },
            toggleMobileSideBar() {
                this.$store.dispatch('app/toggleMobileSideBar')
            },
            toggleSideBar() {
                this.$store.dispatch('app/toggleSideBar')
            },
            refresh() {
                this.$store.dispatch('delCachedView', this.$route).then(() => {
                    const {fullPath} = this.$route;
                    this.$nextTick(() => {
                        this.$router.replace({path: '/redirect' + fullPath})
                    })
                })
            },
            getName(item) {
                return item.meta && item.meta.label ? item.meta.label : item.name || null
            },
            isLast(index) {
                return index === this.list.length - 1
            },
        }
    }
</script>

<style lang="scss" scoped>
    .nav-wrap {
        box-shadow: 0 1px 4px rgba(0, 21, 41, .08);
    }

    .navbar-toggler {
        outline: none;

        .nav-status {
            width: 20px;
            height: 20px;
            cursor: pointer;
            fill: #666;
        }
    }

    .img-avatar {
        height: 22px;
        margin: 0 10px;
        max-width: 100%;
        border-radius: 50%;
    }

    .nav-item {
        position: relative;

        .badge {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -16px;
            margin-left: 0;
        }
    }


</style>
