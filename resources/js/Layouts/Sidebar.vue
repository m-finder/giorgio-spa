<template>
    <div class="sidebar max-h-full bg-gray-200 shadow-lg flex flex-col transition-all duration-500 ease-in-out transform" :class="{'w-12': !open, 'w-64': open}">
        <div class="nav-brand py-4 flex items-center text-center bg-gray-500 border-2 border-gray-500">
            <inertia-link :href="route('admin.index')" class="w-full block text-center">
                <admin-logo class="block h-9 w-auto block mx-auto" :open="open"/>
            </inertia-link>
        </div>

        <div class="w-64  nav-brand flex items-center text-center">

            <ul class="transition-all duration-500 ease-in-out transform">
                <li>
                    <side-bar-link :href="route('admin.index')" :active="route().current('admin.index')">
                        <span aria-hidden="true" class="p-2 pl-3 rounded-lg bg-primary-dark">
                          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                          </svg>
                        </span>
                        <span class="w-full text-left block">控制台</span>
                    </side-bar-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

import AdminLogo from "@/Jetstream/Admin/AdminLogo";
import SideBarLink from "@/Layouts/SideBarLink";

export default {
    components: {
        AdminLogo,
        SideBarLink,
    },
    props: ['open'],
}
</script>

<style scoped>

</style>
