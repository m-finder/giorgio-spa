<template>
    <ol class="breadcrumb">
        <li class="breadcrumb-item" :key="index" v-for="(routeObject, index) in routeRecords">
            <span class="active" v-if="isLast(index)">{{ getName(routeObject) }}</span>
            <router-link :to="routeObject" v-else>{{ getName(routeObject) }}</router-link>
        </li>
    </ol>
</template>

<script>
    export default {
        props: {
            list: {
                type: Array,
                required: true,
                default: () => []
            },
            homePage:{
                type: String,
                default: 'Home'
            },
            homeUrl:{
                type: String,
                default: '/dashboard'
            }
        },
        computed: {
            routeRecords: function () {
                let matched = this.list.filter((route) => route.name || route.meta.label);
                if (!this.isDashboard(matched[0])) {
                    matched = [{ path: this.homeUrl, name: this.homePage}].concat(matched)
                }
                return matched
            }
        },
        methods: {
            isDashboard(route) {
                const name = route && route.name;
                return name ? name.trim().toLocaleLowerCase() === (this.homePage).toLocaleLowerCase() :  false
            },
            getName(item) {
                return item.path == '' ? this.homePage : item.meta && item.meta.title ? item.meta.title : item.name || null
            },
            isLast(index) {
                return index === this.list.length - 1
            }
        }
    }
</script>
<style lang="scss" scoped>
    .breadcrumb {
        display: flex;
        margin-bottom: -1px;
        margin-left: 20px;
        background: none;
    }

    .active {
        color: #777;
    }
</style>
