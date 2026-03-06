<template>
    <Teleport to="body">
        <div v-if="isOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Modal Backdrop -->
            <div class="absolute inset-0 bg-dark-900/80 backdrop-blur-md transition-opacity" @click="$emit('close')"></div>
            
            <!-- Modal Body -->
            <div :class="['relative w-full bg-dark-800 border border-dark-600 rounded-2xl shadow-2xl shadow-black/80 overflow-hidden animate-fade-in-up flex flex-col max-h-[90vh]', maxWidthClass]">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-dark-600/50 flex items-center justify-between shrink-0 bg-dark-800/80 backdrop-blur-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-electric to-neon blur-sm opacity-50"></div>
                    
                    <h3 class="text-xl font-bold text-white tracking-wide flex items-center gap-3">
                        <slot name="icon">
                             <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                                <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                             </div>
                        </slot>
                        {{ title }}
                    </h3>
                    
                    <button @click="$emit('close')" class="text-gray-400 hover:text-white transition-colors p-2 bg-dark-700/50 rounded-xl hover:bg-dark-600 border border-transparent hover:border-dark-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Modal Content Wrapper (Scrollable) -->
                <div class="p-6 overflow-y-auto webkit-scrollbar flex-1">
                    <slot />
                </div>
                
                <!-- Modal Footer -->
                <div v-if="$slots.footer" class="px-6 py-4 border-t border-dark-600/50 bg-dark-900/50 flex items-center justify-end gap-3 shrink-0">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '',
    },
    maxWidth: {
        type: String,
        default: 'md' // sm, md, lg, xl, 2xl
    }
});

defineEmits(['close']);

const maxWidthClass = computed(() => {
    return {
        'sm': 'max-w-sm',
        'md': 'max-w-md',
        'lg': 'max-w-lg',
        'xl': 'max-w-xl',
        '2xl': 'max-w-2xl',
    }[props.maxWidth];
});

// Prevent scrolling on body when modal is open
watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
});
</script>
