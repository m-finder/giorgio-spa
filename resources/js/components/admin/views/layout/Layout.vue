<template>
    <section class="app-wrapper">
        <side-bar/>
        <section class="main-container"  :class="{'main-container-open': !isCollapse}">
            <nav-bar/>
            <tags/>
            <app-main/>
            <go-top/>
        </section>
    </section>
</template>
<script>
    import {mapGetters} from 'vuex';
    import {Navbar, Sidebar, AppMain, Tags} from './components';
    import GoTop from '../../components/gotop/Index';

    export default {
        name: 'Layout',
        components: {
            'side-bar': Sidebar,
            'nav-bar': Navbar,
            'app-main': AppMain,
            'tags': Tags,
            'go-top': GoTop,
        },
        computed: {
            ...mapGetters([
                'sidebar'
            ]),
            isCollapse() {
                return this.sidebar.opened
            },
        },

    }
</script>
<style lang="scss" scoped>
    .main-container {
        position: absolute;
        right: 0;
        height: 100vh;
        transition: width 0.2s;
        width: calc(100vw - 200px);
    }

    .main-container-open{
        width: calc(100vw - 68px)!important;
    }

    @media (max-width: 991.98px){
        .main-container-open{
            width: calc(100vw)!important;
        }
    }
</style>
