<template>
    <transition name="fade">
        <div v-if="show" class="goTop" @click="goTop">
            <svg-vue icon="back-top"/>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                show: false
            }
        },
        mounted() {
            const container = document.querySelector('.app-main');
            const that = this;
            container.addEventListener("scroll", function () {
                let scrollTop = container.scrollTop || window.pageYOffset || document.body.scrollTop;
                if (scrollTop > 20) {
                    that.show = true;
                } else {
                    that.show = false;
                }
            });
        },
        methods: {
            goTop() {
                const container = document.querySelector('.app-main');
                let timer = setInterval(function () {
                    let scrollTop = container.scrollTop || window.pageYOffset || document.body.scrollTop;
                    let speed = scrollTop / 10;
                    scrollTop -= speed;
                    container.scrollTop = scrollTop;
                    if (scrollTop <= 0) {
                        clearInterval(timer);
                    }
                }, 1);
            }
        },
    }
</script>
<style scoped lang="scss">
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .goTop {
        position: fixed;
        right: 36px;
        bottom: 50px;
        background: #FFF;
        width: 50px;
        height: 50px;
        line-height: 60px;
        text-align: center;
        border-radius: 2px;
        box-shadow: 0 4px 12px 0 rgba(7, 17, 27, .1);
        cursor: pointer;
        z-index: 1000;

        svg {
            width: 35px;
            height: 35px;
            margin-top: 8px;
            vertical-align: baseline;
        }
    }
</style>