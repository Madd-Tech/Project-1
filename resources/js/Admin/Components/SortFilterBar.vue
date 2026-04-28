<template>
    <div class="glass-card rounded-2xl p-4 animate-fade-in-up">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
            <!-- Sort Buttons -->
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 text-gray-400 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                    <span class="font-medium">Urutkan</span>
                </div>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button
                        v-for="opt in sortOptions"
                        :key="opt.value"
                        @click="applySort(opt.value)"
                        :class="[
                            'px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-300',
                            currentSort === opt.value
                                ? 'bg-electric/20 text-electric border-electric/30 shadow-sm shadow-electric/10'
                                : 'bg-dark-800 text-gray-400 border-dark-600 hover:border-electric/30 hover:text-white'
                        ]"
                    >
                        {{ opt.label }}
                    </button>
                </div>
            </div>

            <!-- Separator -->
            <div class="hidden lg:block w-px h-8 bg-dark-600"></div>

            <!-- Date Range -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1.5 text-gray-400 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-medium">Rentang Tanggal</span>
                </div>
                <div class="flex items-center gap-2">
                    <input
                        type="date"
                        :value="dateFrom"
                        @input="$emit('update:dateFrom', $event.target.value)"
                        @change="$emit('apply')"
                        class="bg-dark-800 border border-dark-600 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-electric/50 focus:border-electric/50 transition-all [color-scheme:dark]"
                    >
                    <span class="text-gray-500 text-xs">—</span>
                    <input
                        type="date"
                        :value="dateTo"
                        @input="$emit('update:dateTo', $event.target.value)"
                        @change="$emit('apply')"
                        class="bg-dark-800 border border-dark-600 rounded-lg px-3 py-1.5 text-xs text-white focus:outline-none focus:ring-1 focus:ring-electric/50 focus:border-electric/50 transition-all [color-scheme:dark]"
                    >
                </div>
            </div>

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- Active Filters / Reset -->
            <div v-if="hasActiveFilters" class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Filter aktif:</span>
                <span v-if="dateFrom || dateTo" class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-medium bg-amber/10 text-amber rounded-md border border-amber/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ dateFrom || '...' }} → {{ dateTo || '...' }}
                    <button @click="clearDateRange" class="ml-0.5 hover:text-white transition-colors">&times;</button>
                </span>
                <button
                    @click="resetAll"
                    class="px-2.5 py-1 text-[10px] font-medium bg-red-500/10 text-red-400 rounded-md border border-red-500/20 hover:bg-red-500/20 transition-all flex items-center gap-1"
                >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    /** Currently active sort key */
    sortBy: {
        type: String,
        default: 'newest',
    },
 
    dateFrom: {
        type: String,
        default: '',
    },
  
    dateTo: {
        type: String,
        default: '',
    },
 
    options: {
        type: Array,
        default: null,
    },
});

const emit = defineEmits([
    'update:sortBy',
    'update:dateFrom',
    'update:dateTo',
    'apply',
    'reset',
]);

const sortOptions = computed(() =>
    props.options || [
        { value: 'newest', label: 'Terbaru' },
        { value: 'oldest', label: 'Terlama' },
        { value: 'a-z', label: 'A — Z' },
        { value: 'z-a', label: 'Z — A' },
    ]
);

const currentSort = computed(() => props.sortBy);

const hasActiveFilters = computed(() => {
    return props.sortBy !== 'newest' || props.dateFrom || props.dateTo;
});

const applySort = (value) => {
    emit('update:sortBy', value);
    emit('apply');
};

const clearDateRange = () => {
    emit('update:dateFrom', '');
    emit('update:dateTo', '');
    emit('apply');
};

const resetAll = () => {
    emit('update:sortBy', 'newest');
    emit('update:dateFrom', '');
    emit('update:dateTo', '');
    emit('reset');
};
</script>
