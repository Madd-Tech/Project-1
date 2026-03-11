<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Welcome Banner -->
            <div
                class="glass-card p-8 rounded-3xl relative overflow-hidden animate-fade-in-up"
            >
                <div
                    class="absolute top-0 right-0 w-72 h-72 bg-electric/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 pointer-events-none"
                ></div>
                <div
                    class="absolute bottom-0 left-0 w-48 h-48 bg-neon/10 rounded-full blur-[60px] translate-y-1/2 -translate-x-1/2 pointer-events-none"
                ></div>
                <div class="relative z-10">
                    <span
                        class="inline-flex items-center gap-2 text-xs font-semibold text-neon bg-neon/10 border border-neon/20 px-3 py-1 rounded-full mb-4"
                    >
                        <span
                            class="w-1.5 h-1.5 bg-neon rounded-full animate-pulse"
                        ></span>
                        Session Active
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                        Selamat Datang,
                        <span class="gradient-text">{{ admin.name }}</span> 👋
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Kamu berhasil Login ke Dashboard Admin!.
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5"
            >
                <div
                    v-for="stat in stats"
                    :key="stat.label"
                    class="glass-card p-6 rounded-2xl group cursor-default animate-fade-in-up"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            :class="`w-11 h-11 rounded-xl flex items-center justify-center ${stat.iconBg}`"
                        >
                            <span class="text-xl">{{ stat.icon }}</span>
                        </div>
                        <span
                            :class="`text-xs font-semibold px-2 py-0.5 rounded-full ${stat.badgeCls}`"
                        >
                            {{ stat.badge }}
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-white">
                        {{ stat.value }}
                    </p>
                    <p class="text-sm text-gray-400 mt-1">{{ stat.label }}</p>
                </div>
            </div>

            <!-- Auth Info Card -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <!-- Session Details -->
                <div class="glass-card p-6 rounded-2xl animate-slide-in-left">
                    <div class="flex items-center gap-3 mb-5">
                        <div
                            class="w-8 h-8 bg-electric/15 rounded-lg flex items-center justify-center"
                        >
                            <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="font-semibold text-white">
                            Session Details
                        </h3>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="item in sessionDetails"
                            :key="item.label"
                            class="flex items-center justify-between py-2 border-b border-dark-600/50 last:border-0"
                        >
                            <span class="text-sm text-gray-400">{{
                                item.label
                            }}</span>
                            <span
                                :class="`text-sm font-medium ${item.cls ?? 'text-white'}`"
                                >{{ item.value }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card p-6 rounded-2xl animate-slide-in-right">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 bg-neon/15 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="font-semibold text-white">Quick Actions</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <Link v-for="action in quickActions" :key="action.label" :href="action.href" class="flex flex-col items-center gap-2 p-4 bg-dark-800/50 hover:bg-dark-700/70 border border-dark-600 hover:border-electric/30 rounded-xl transition-all duration-200 group text-center cursor-pointer">
                            <span class="text-2xl">{{ action.icon }}</span>
                            <span class="text-xs font-medium text-gray-300 group-hover:text-white transition-colors">{{ action.label }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AdminLayout from "../../Admin/Components/AdminLayout.vue";

const props = defineProps({
    admin: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    }
});

const stats = computed(() => [
    { label: "Total Produk", value: props.stats.productCount, icon: "📦", badge: "Manage", badgeCls: "text-electric bg-electric/10", iconBg: "bg-electric/10" },
    { label: "Kategori", value: props.stats.categoryCount, icon: "🗂️", badge: "View", badgeCls: "text-neon bg-neon/10", iconBg: "bg-neon/10" },
    { label: "Stok Item", value: props.stats.totalStock, icon: "🏷️", badge: "Track", badgeCls: "text-amber bg-amber/10", iconBg: "bg-amber/10" },
    { label: "Pesanan Hari Ini", value: props.stats.ordersToday, icon: "🛒", badge: "Review", badgeCls: "text-purple-400 bg-purple-400/10", iconBg: "bg-purple-400/10" },
]);

const sessionDetails = computed(() => [
    { label: "Logged in as", value: props.admin.name },
    { label: "Email", value: props.admin.email },
    { label: "Role", value: "Administrator", cls: "text-neon" },
    { label: "Session", value: "Active & Encrypted", cls: "text-electric" },
]);

const quickActions = [
    { icon: "📦", label: "Produk", href: "/admin/products" },
    { icon: "🗂️", label: "Kategori", href: "/admin/categories" },
    { icon: "🏷️", label: "Stok", href: "/admin/stock" },
    { icon: "🛒", label: "Pesanan", href: "/admin/dashboard" }, // Placeholder as orders route doesn't exist yet
];
</script>
