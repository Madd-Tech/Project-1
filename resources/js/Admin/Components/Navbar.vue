<template>
    <header class="h-20 shrink-0 bg-dark-800/60 backdrop-blur-xl border-b border-dark-600/30 flex items-center justify-between px-4 sm:px-6 z-30 sticky top-0">

        <button @click="$emit('toggleSidebar')" class="lg:hidden p-2.5 text-gray-400 hover:text-white rounded-xl hover:bg-dark-700/50 focus:outline-none transition-all border border-transparent hover:border-dark-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="hidden lg:flex items-center gap-3">
            <div class="bg-dark-700/40 text-electric/80 px-3 py-1.5 rounded-lg border border-dark-600/50 flex items-center gap-2 text-xs font-medium">
                <span class="w-1.5 h-1.5 bg-electric rounded-full animate-pulse shadow-[0_0_8px_rgba(59,130,246,0.8)]"></span>
                System Operational
            </div>
            <h2 class="text-sm font-medium text-gray-400 pl-2 border-l border-dark-600/50">Welcome back, Admin.</h2>
        </div>

        <div class="flex items-center gap-4 ml-auto">
             <div class="hidden sm:flex items-center gap-2 text-xs text-gray-400">
                <span class="pe-2 border-r border-dark-600/50">{{ currentTime }}</span>
            </div>

            <div class="flex items-center gap-3 bg-dark-800/50 px-4 py-2 rounded-xl border border-dark-600 shadow-sm">
                <div class="w-8 h-8 bg-electric/10 border border-electric/30 rounded-lg flex items-center justify-center shadow-[inset_0_1px_4px_rgba(59,130,246,0.3)]">
    
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="text-[13px] font-semibold text-white tracking-wide">{{ admin?.name || 'Admin' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">{{ admin?.email || 'admin@example.com' }}</p>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    admin: {
        type: Object,
        required: true,
    }
});
defineEmits(['toggleSidebar']);

const currentTime = ref('');

const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

let timer;
onMounted(() => {
    updateTime();
    timer = setInterval(updateTime, 10000);
});
onUnmounted(() => clearInterval(timer));
</script>
