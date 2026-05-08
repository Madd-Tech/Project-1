<template>
    <div class="h-screen bg-dark-900 text-white flex overflow-hidden">
        <Sidebar :is-mobile-open="isMobileOpen" @close="isMobileOpen = false" />
        
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <Navbar :admin="admin" @toggle-sidebar="isMobileOpen = !isMobileOpen" />
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto w-full relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';

defineProps({
    admin: {
        type: Object,
        required: true,
    }
});

const isMobileOpen = ref(false);
</script>
