<template>
    <section class="app-main" :class="{'app-main-close': !isCollapse}">
        <transition name="fade" mode="out-in">
            <keep-alive :include="cachedViews">
                <router-view/>
            </keep-alive>
        </transition>
    </section>
</template>
<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'AppMain',
        computed: {
            key() {
                return this.$route.path
            },
            cachedViews() {
                return this.$store.state.tagsView.cachedViews
            },
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
    .app-main {
        height: calc(100vh - 91px);
        width: calc(100vw - 200px);
        padding: 1.5rem 1rem 5rem 1rem;
        transition: width 0.2s;
        overflow-x: hidden;
        overflow-y: auto;
        display: block;
    }

    .app-main-close {
        width: calc(100vw - 68px)!important;
    }

    @media (max-width: 991.98px){
        .app-main-close {
            width: 100vw!important;
        }
    }

    .fade-enter-active, .fade-leave-active {
        transition-duration: 0.3s;
        transition-property: opacity;
        transition-timing-function: ease;
    }

    .fade-enter, .fade-leave-active {
        opacity: 0
    }
</style>
