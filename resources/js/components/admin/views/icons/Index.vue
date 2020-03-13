<template>
    <div>
        <div class="icon-item-box" v-for="(item,i) of iconsMap" :key="i">
            <div class="icon-item">
                <svg-vue :icon="item"/>
            </div>
            <div>{{ item }}</div>
        </div>
    </div>
</template>

<script>
    import icons from './requireIcons'

    export default {
        name: "Icons",
        data() {
            return {
                iconsMap: icons
            }
        }
    }
</script>

<style scoped>
    .icon-item-box {
        display: inline-block;
        width: 110px;
        height: 30px;
        margin: 25px;
        text-align: center;
    }

    svg {
        width: 30px;
        height: 30px;
    }
</style>
