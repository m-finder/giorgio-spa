<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    props: ['href', 'active'],

    computed: {
        classes() {
            return this.active
                ? 'flex items-center w-full space-x-2 text-gray-700 rounded-lg bg-primary transition-all duration-150 ease-in-out'
                : 'flex items-center w-full space-x-2 text-gray-700 rounded-lg bg-primary hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
        }
    }
}
</script>

<style scoped>

</style>
