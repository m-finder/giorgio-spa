<template>
    <div>
        <section class="text-center sidebar-container bv-d-md-down-none" display="lg" :class="{ 'side-bar-close' : !isCollapse }">
            <div class="sidebar-wrapper">
                <div class="header">
                    <b-navbar-brand>
                        <img :src="'/favicon.ico'" class="logo d-inline-block align-bottom" alt="">
                        <span v-if="isCollapse">
                        {{ config.title }} {{ config.subheading }}
                    </span>
                    </b-navbar-brand>
                </div>

                <b-nav vertical class="text-left" type="dark">
                    <template v-for="(item, index) in routers">
                        <template v-if="item.children">

                            <!-- 只有一个子菜单-->
                            <b-nav-item
                                    v-if="onlyOneShowingChildren(item.children) && !item.hidden && !item.children[0].hidden"
                                    :to="item.children[0].path" :id="item.children[0].path">
                                <svg-vue :icon="item.children[0].icon || 'smile'"/>
                                <b-tooltip v-if="!isCollapse" :target="item.children[0].path"
                                           placement="right"
                                           boundary="window" triggers="hover">
                                    {{ getTitle(item.children[0]) }}
                                </b-tooltip>
                                <span>{{ getTitle(item.children[0]) }}</span>
                            </b-nav-item>
                            <!-- 没有显示的子菜单 -->
                            <b-nav-item v-else-if="noShowingChildren(item.children) && !item.hidden"
                                        :to="item.path" :id="item.path">
                                <svg-vue :icon="item.icon || 'smile'"/>
                                <b-tooltip v-if="!isCollapse" :target="item.path" placement="right"
                                           boundary="window" triggers="hover">
                                    {{ getTitle(item) }}
                                </b-tooltip>
                                <span>{{ getTitle(item) }}</span>
                            </b-nav-item>

                            <sitebar-item v-else-if="!item.hidden" :name="getTitle(item)" :icon="item.icon"
                                          :href="item.path" :id="item.path">
                                <b-nav v-if="isCollapse" vertical class="nav-dropdown-items">
                                    <template v-for="(children, i) in item.children">
                                        <b-nav-item v-if="!children.hidden" :to="children.path">
                                            <svg-vue :icon="children.icon || 'smile'"/>
                                            <span>{{ getTitle(children) }}</span>
                                        </b-nav-item>
                                    </template>
                                </b-nav>
                                <b-tooltip v-if="!isCollapse" :target="item.path" placement="right"
                                           boundary="window" triggers="hover">
                                    <b-nav vertical class="tooltip-nav">
                                        <template v-for="(children, i) in item.children">
                                            <b-nav-item v-if="!children.hidden" :to="children.path">
                                                {{ getTitle(children) }}
                                            </b-nav-item>
                                        </template>
                                    </b-nav>
                                </b-tooltip>
                            </sitebar-item>
                        </template>

                        <template v-else>
                            <b-nav-item v-if="!item.hidden" :to="item.path" :id="item.path">
                                <svg-vue :icon="item.icon || 'smile'"/>
                                <b-tooltip v-if="!isCollapse" :target="item.path" placement="right"
                                           boundary="window" triggers="hover">
                                    {{ getTitle(item) }}
                                </b-tooltip>
                                <span>{{ getTitle(item) }}</span>
                            </b-nav-item>
                        </template>
                    </template>
                </b-nav>
            </div>
        </section>
        <!--   手机版     -->
        <section class="text-center sidebar-container mobile-side-bar d-none " display="sm" :class="{ 'mobile-side-bar-close' : !isMobileCollapse }">
            <div class="sidebar-wrapper">
                <div class="header">
                    <b-navbar-brand>
                        <img :src="'/favicon.ico'" class="logo d-inline-block align-bottom" alt="">
                        <span>
                            {{ config.title }} {{ config.subheading }}
                        </span>
                    </b-navbar-brand>
                </div>

                <b-nav vertical class="text-left" type="dark">
                    <template v-for="(item, index) in routers">
                        <template v-if="item.children">

                            <!-- 只有一个子菜单-->
                            <b-nav-item
                                    v-if="onlyOneShowingChildren(item.children) && !item.hidden && !item.children[0].hidden"
                                    :to="item.children[0].path" :id="item.children[0].path">
                                <svg-vue :icon="item.children[0].icon || 'smile'"/>
                                <span>{{ getTitle(item.children[0]) }}</span>
                            </b-nav-item>
                            <!-- 没有显示的子菜单 -->
                            <b-nav-item v-else-if="noShowingChildren(item.children) && !item.hidden"
                                        :to="item.path" :id="item.path">
                                <svg-vue :icon="item.icon || 'smile'"/>
                                <span>{{ getTitle(item) }}</span>
                            </b-nav-item>

                            <sitebar-item v-else-if="!item.hidden" :name="getTitle(item)" :icon="item.icon"
                                          :href="item.path" :id="item.path">
                                <b-nav vertical class="nav-dropdown-items">
                                    <template v-for="(children, i) in item.children">
                                        <b-nav-item v-if="!children.hidden" :to="children.path">
                                            <svg-vue :icon="children.icon || 'smile'"/>
                                            <span>{{ getTitle(children) }}</span>
                                        </b-nav-item>
                                    </template>
                                </b-nav>
                            </sitebar-item>
                        </template>

                        <template v-else>
                            <b-nav-item v-if="!item.hidden" :to="item.path" :id="item.path">
                                <svg-vue :icon="item.icon || 'smile'"/>
                                <span>{{ getTitle(item) }}</span>
                            </b-nav-item>
                        </template>
                    </template>
                </b-nav>
            </div>

        </section>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex'
    import SitebarItem from './SidebarItem'
    import config from '../../../config/config';

    export default {
        name: 'Sidebar',
        components: {
            'sitebar-item': SitebarItem,
        },
        data() {
            return {
                config: config,
            }
        },
        computed: {
            ...mapGetters([
                'sidebar',
                'routers'
            ]),
            isCollapse() {
                return this.sidebar.opened
            },
            isMobileCollapse() {
                return this.sidebar.mobileOpened
            },
        },
        methods: {
            onlyOneShowingChildren(children) {
                return children.filter(item => !item.hidden).length === 1
            },
            noShowingChildren(children) {
                return children.filter(item => !item.hidden).length === 0
            },
            getTitle(data) {
                return data.meta && data.meta.title ? data.meta.title : data.name
            }
        }
    }
</script>
<style lang="scss" scoped>
    @media (min-width: 1024px){
        .mobile {
            left: -250px!important;
        }
    }
    .sidebar-container {
        position: absolute;
        left: 0;
        top: 0;
        overflow: hidden;
        transition: all .2s;
        width: 200px;
        background: rgba(0, 0, 0, .1);
        height: 100%;

        .sidebar-wrapper {
            height: 100%;
            width: calc(100% + 30px);
            overflow-x: hidden;
            overflow-y: auto;
        }

        .header {
            text-align: left;
            padding-left: 15px;
            margin: 5px 0;

            .navbar-brand {
                margin-right: 0;

                .logo {
                    width: 30px;
                    height: 30px;
                }
            }
        }

        .nav-item {
            width: 200px;

            span {
                transition: left .2s;
                position: absolute;
                left: 40px;
                display: inline-flex;
                max-width: 100%;
                min-width: 180px;
            }
        }

        .nav-link {
            width: 200px;
            position: relative;
            transition: padding-left .2s;

            &:hover {
                color: #1d68a7;

                svg {
                    fill: #1d68a7;
                }
            }
        }
    }

    .side-bar-close, .side-bar-close .header, .side-bar-close .nav-item, .side-bar-close .nav-item .nav-link {
        width: 68px;
    }

    .side-bar-close {
        .header {
            text-align: center;
            padding: 0;
        }

        .nav-item {
            .nav-link {
                width: 68px;
                padding-left: 25px;
            }

            span {
                left: 200px;
            }
        }
    }

    .nav-dropdown-items {
        max-height: 0;
        width: 100%;
        padding: 0;
        margin: 0;
        overflow: hidden;
        background: rgba(255, 255, 255, .1);
        transition: max-height .3s ease-in-out;

        .nav-link {
            padding-left: 32px;

            span {
                left: 55px;
            }
        }
    }

    .nav-link.active.open {
        svg {
            fill: #1d68a7 !important;
        }
    }

    .active {
        color: #1d68a7 !important;
        background: rgba(255, 255, 255, .1);

        &:before {
            content: '';
            height: 100%;
            width: 3px;
            position: absolute;
            background: #fff;
            left: 0;
            top: 0;
        }
    }

    .open {
        .nav-dropdown-items {
            max-height: 1500px;
        }
    }

    .tooltip-nav {
        .nav-link {
            color: #ffffff;
            text-align: left;
        }

        .active {
            color: #ffffff !important;

            &:before {
                content: '';
                width: 0;
            }
        }

        .open {
            border-radius: 5px;
        }
    }

    @media (max-width: 991.98px) {
        .header{
            span{
                color: #fff;
            }
        }
        .mobile-side-bar {
            display: block !important;
            background: #4E5465!important;
            z-index: 999999;
            left: 0;
        }
        .mobile-side-bar-close, .mobile-side-bar-close .header, .mobile-side-bar-close .nav-item, .mobile-side-bar-close .nav-item .nav-link {
            width: 200px;
        }
        .mobile-side-bar-close {
            left: -250px!important;
            .header {
                text-align: left;
                padding: 15px;
            }
            .nav-item {
                .nav-link {
                    width: 200px;
                    padding-left: 15px;
                }

                span {
                    left: 40px;
                }
            }
            .nav-dropdown-items .nav-link {
                padding-left: 32px!important;
                span{
                    left: 55px;
                }
            }
        }

        .nav-link {
           color: rgba(255,255,255,.7);
            svg {
                fill: rgba(255,255,255,.7);
            }

            &:hover {
                color: #fff;

                svg {
                    fill: #fff;
                }
            }
        }

        .nav-link.active.open {
            color: #ffffff !important;
            svg {
                fill: #ffffff !important;
            }
        }
    }
</style>
