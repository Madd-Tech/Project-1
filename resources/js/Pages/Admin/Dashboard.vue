<template>
    <div class="min-h-screen bg-dark-900 text-white">
        <!-- Top Navigation Bar -->
        <nav
            class="bg-dark-800/80 backdrop-blur-md border-b border-dark-600/50 sticky top-0 z-50"
        >
            <div
                class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between"
            >
                <!-- Logo / Brand -->
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 bg-dark-700 rounded-xl flex items-center justify-center glow-border"
                    >
                        <svg
                            class="w-5 h-5 text-electric"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold gradient-text">
                            BabuihanStore
                        </h1>
                        <p
                            class="text-[10px] text-gray-500 uppercase tracking-widest"
                        >
                            Admin Panel
                        </p>
                    </div>
                </div>

                <!-- Admin info + logout -->
                <div class="flex items-center gap-4">
                    <div
                        class="hidden sm:flex items-center gap-3 glass px-4 py-2 rounded-xl"
                    >
                        <div
                            class="w-7 h-7 bg-electric/20 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-electric"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-semibold text-white">
                                {{ admin.name }}
                            </p>
                            <p class="text-[10px] text-gray-400">
                                {{ admin.email }}
                            </p>
                        </div>
                    </div>

                    <button
                        @click="logout"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-white bg-dark-700/50 hover:bg-red-500/20 border border-dark-600 hover:border-red-500/40 transition-all duration-200 group"
                    >
                        <svg
                            class="w-4 h-4 group-hover:text-red-400 transition-colors"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 py-10">
            <!-- Welcome Banner -->
            <div
                class="glass-card p-8 rounded-3xl mb-8 relative overflow-hidden animate-fade-in-up"
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
                        Welcome back,
                        <span class="gradient-text">{{ admin.name }}</span> 👋
                    </h2>
                    <p class="text-gray-400 text-sm">
                        You're successfully logged in to the admin dashboard.
                        Your session is secure and encrypted.
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8"
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
                            <svg
                                class="w-4 h-4 text-electric"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
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
                        <div
                            class="w-8 h-8 bg-neon/15 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-neon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Quick Actions</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            v-for="action in quickActions"
                            :key="action.label"
                            class="flex flex-col items-center gap-2 p-4 bg-dark-800/50 hover:bg-dark-700/70 border border-dark-600 hover:border-electric/30 rounded-xl transition-all duration-200 group text-center"
                        >
                            <span class="text-2xl">{{ action.icon }}</span>
                            <span
                                class="text-xs font-medium text-gray-300 group-hover:text-white transition-colors"
                                >{{ action.label }}</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    admin: {
        type: Object,
        required: true,
    },
});

const logout = () => {
    router.post("/logout");
};

const stats = [
    {
        label: "Total Products",
        value: "—",
        icon: "📦",
        badge: "Manage",
        badgeCls: "text-electric bg-electric/10",
        iconBg: "bg-electric/10",
    },
    {
        label: "Categories",
        value: "—",
        icon: "🗂️",
        badge: "View",
        badgeCls: "text-neon bg-neon/10",
        iconBg: "bg-neon/10",
    },
    {
        label: "Stock Items",
        value: "—",
        icon: "🏷️",
        badge: "Track",
        badgeCls: "text-amber bg-amber/10",
        iconBg: "bg-amber/10",
    },
    {
        label: "Orders Today",
        value: "—",
        icon: "🛒",
        badge: "Review",
        badgeCls: "text-purple-400 bg-purple-400/10",
        iconBg: "bg-purple-400/10",
    },
];

const sessionDetails = computed(() => [
    { label: "Logged in as", value: props.admin.name },
    { label: "Email", value: props.admin.email },
    { label: "Role", value: "Administrator", cls: "text-neon" },
    { label: "Session", value: "Active & Encrypted", cls: "text-electric" },
]);

const quickActions = [
    { icon: "📦", label: "Products" },
    { icon: "🗂️", label: "Categories" },
    { icon: "🏷️", label: "Stock" },
    { icon: "🛒", label: "Orders" },
];
</script>
