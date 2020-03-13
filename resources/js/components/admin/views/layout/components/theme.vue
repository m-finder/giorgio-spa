<template>
    <div class="theme-box" :class="{'show-theme-box': show}">
        <p>
            <svg-vue icon="theme"/>
            <span>主题设置</span>
        </p>
        <div class="close-btn" @click="close">
            <svg-vue icon="close"/>
        </div>
        <div>
            <div class="current-theme-box">
                当前主题:
                <div :class="[currentTheme, 'theme-btn']">
                    <div class="sidebar-container"></div>
                </div>
            </div>
            <div class="text-center">
                <div class="theme-btn default" @click="setTheme('default')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-light-blue" @click="setTheme('bg-light-blue')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-light-green" @click="setTheme('bg-light-green')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-light-red" @click="setTheme('bg-light-red')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-indigo" @click="setTheme('bg-indigo')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-purple" @click="setTheme('bg-purple')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-pink" @click="setTheme('bg-pink')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-orange" @click="setTheme('bg-orange')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-yellow" @click="setTheme('bg-yellow')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-green" @click="setTheme('bg-green')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-teal" @click="setTheme('bg-teal')">
                    <div class="sidebar-container"></div>
                </div>
                <div class="theme-btn bg-cyan" @click="setTheme('bg-cyan')">
                    <div class="sidebar-container"></div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import storage from '../../../utils/storage'

    export default {
        name: "theme",
        data() {
            return {
                currentTheme: storage.get('theme')
            }
        },
        props: {
            show: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            close: function (e) {
                this.$parent.showSideBar = false
            },
            setTheme(className) {
                this.currentTheme = className;
                this.$store.dispatch('app/changeTheme', className)
            },
        }
    }
</script>

<style lang="scss" scoped>
    .theme-box {
        width: 311px;
        height: calc(100vh - 90px);
        position: fixed;
        right: -350px;
        bottom: 0;
        background: #ffffff;
        border: 1px solid #dcdcdc;
        z-index: 1027;
        padding: 15px;
        transition: all 0.2s;

        .close-btn {
            border-left: 1px solid #dcdcdc;
            border-top: 1px solid #dcdcdc;
            border-bottom: 1px solid #dcdcdc;
            position: absolute;
            left: -35px;
            top: -1px;
            width: 35px;
            height: 35px;
            background: #ffffff;
            text-align: center;
            line-height: 35px;
            cursor: pointer;

            &:hover {
                svg {
                    fill: #e3342f;
                }
            }
        }
    }

    .show-theme-box {
        right: 0;
    }

    .theme-btn {
        width: 80px;
        height: 50px;
        cursor: pointer;
        display: inline-block;
        position: relative;
        border:1px solid rgba(0,0,0,.1);
        margin: 0 5px;
    }

    .sidebar-container{
        width: 10px;
        height:100%;
        position: absolute;
        top: 0;
        left: 0;
        background: rgb(0,0,0,.1);
    }

    .current-theme-box {
        margin: 15px auto;
        line-height: 50px;

        .theme-btn {
            cursor: default;
            vertical-align: bottom;
        }
    }
</style>
