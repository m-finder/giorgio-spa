<template>
    <section class="content container-fluid">
        <div class="row justify-content-center p-3">
            <div class="text-center text-secondary">
                <p>
                    <svg-vue icon="404" class="not-found"/>
                </p>
                <h4>您要访问的页面不存在…</h4>
                <p class="mt-3">
                    <a href="/admin">回到首页</a>
                </p>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Error_404"
    }
</script>

<style lang="scss" scoped>
    .not-found {
        width: 300px;
        height: 300px;
    }
</style>