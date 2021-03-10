<template>
    <div class="sidebar max-h-full bg-gray-200 shadow-lg flex flex-col justify-between transition-all duration-500 ease-in-out transform border-r-2 border-gray-200" :class="{'md:w-12': !open, 'md:w-64': open}">
        <div class="nav-brand py-6 pl-1 my-1 flex items-center justify-between text-center">
            <a href="" class="w-full block text-center">
                <admin-mark class="block h-9 w-auto block mx-auto" :open="open" :app-name="$page.props.adminName"/>
            </a>
        </div>
    </div>
</template>

<script>

import AdminLogo from "@/Jetstream/Admin/AdminLogo";
import AdminMark from "@/Jetstream/Admin/AdminMark";

export default {
    components: {
        AdminLogo,
        AdminMark
    },
    props: ['open'],
}
</script>

<style scoped>

</style>
