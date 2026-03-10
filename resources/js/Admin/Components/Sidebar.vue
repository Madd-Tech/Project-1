<template>
    <!-- Mobile overlay -->
    <div v-if="isMobileOpen" @click="$emit('close')" class="fixed inset-0 bg-black/60 z-40 lg:hidden backdrop-blur-sm"></div>

    <!-- Sidebar -->
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 w-64 bg-dark-800/90 backdrop-blur-xl border-r border-dark-600/50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col',
            isMobileOpen ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
        <!-- Logo -->
        <div class="h-20 shrink-0 flex items-center px-6 border-b border-dark-600/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-dark-700/80 rounded-xl flex items-center justify-center glow-border border border-electric/30">
                    <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold gradient-text pb-0.5">BabuihanStore</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto webkit-scrollbar">
            <Link
                v-for="item in navItems"
                :key="item.label"
                :href="item.href"
                :class="[
                    'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group relative overflow-hidden',
                    isActive(item.href)
                        ? 'bg-electric/10 text-electric border border-electric/20 shadow-[0_0_15px_-3px_rgba(59,130,246,0.3)]'
                        : 'text-gray-400 hover:bg-dark-700/50 hover:text-white border border-transparent hover:border-dark-600'
                ]"
            >
                <!-- Active Indicator -->
                <div v-if="isActive(item.href)" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-electric rounded-r-full shadow-[0_0_8px_rgba(59,130,246,0.8)]"></div>
                
                <div :class="['w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 z-10', isActive(item.href) ? 'bg-electric/20 text-electric' : 'bg-dark-800/80 group-hover:bg-dark-600 group-hover:text-white']">
                    <!-- Dashboard Icon -->
                    <svg v-if="item.label === 'Dashboard'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    
                    <!-- Categories Icon -->
                    <svg v-else-if="item.label === 'Categories'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>

                    <!-- Products Icon -->
                    <svg v-else-if="item.label === 'Products'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>

                    <!-- Stock Icon -->
                    <svg v-else-if="item.label === 'Stock'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <svg v-else-if="item.label === 'StockMov'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="z-10">{{ item.label }}</span>
            </Link>
        </nav>
        
        <!-- Bottom Section (Logout) -->
        <div class="p-4 border-t border-dark-600/50 shrink-0">
            <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-300 hover:text-white hover:bg-red-500/10 group border border-transparent hover:border-red-500/20 transition-all duration-300">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-800/80 group-hover:bg-red-500/20 transition-all duration-300 shadow-sm border border-transparent group-hover:border-red-500/30">
                    <svg class="w-4 h-4 group-hover:text-red-400 text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="group-hover:text-red-300 transition-colors">Sign out</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

defineProps({
    isMobileOpen: Boolean
});
defineEmits(['close']);

const navItems = [
    { label: 'Dashboard', href: '/admin/dashboard' },
    { label: 'Categories', href: '/admin/categories' },
    { label: 'Products', href: '/admin/products' },
    { label: 'Stock', href: '/admin/stock' },
    { label: 'StockMov', href: '/admin/stockmov' },
];

const isActive = (href) => {
    // Exact match for dashboard to avoid matching other routes, but startswith for nested category routes
    if (href === '/admin/dashboard') {
        return page.url === '/admin/dashboard';
    }
    return page.url.startsWith(href);
};

const logout = () => {
    router.post('/logout');
};
</script>
