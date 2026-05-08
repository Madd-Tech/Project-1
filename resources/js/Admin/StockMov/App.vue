<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Riwayat Pergerakan Stok</h2>
                    <p class="text-gray-400 text-sm">Monitoring semua pemasukan dan pengeluaran stok di seluruh inventaris Anda.</p>
                </div>
                <!-- Type filter pills -->
                <div class="flex items-center gap-2">
                    <button
                        v-for="filter in typeFilters"
                        :key="filter.value"
                        @click="setTypeFilter(filter.value)"
                        :class="[
                            'px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-all duration-200',
                            activeType === filter.value
                                ? filter.activeClass
                                : 'bg-dark-800 text-gray-400 border-dark-600 hover:border-dark-500 hover:text-gray-300'
                        ]"
                    >
                        {{ filter.label }}
                    </button>
                </div>
            </div>

            <!-- Flash Success Message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Sort & Filter Bar -->
            <SortFilterBar
                v-model:sort-by="activeSortBy"
                v-model:date-from="dateFrom"
                v-model:date-to="dateTo"
                @apply="applyFilters"
                @reset="applyFilters"
            />

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-electric/10 border border-electric/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Pergerakan</p>
                        <p class="text-xl font-bold text-white">{{ stats.total }}</p>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Produk Masuk</p>
                        <p class="text-xl font-bold text-green-400">{{ stats.masuk }}</p>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Produk Keluar</p>
                        <p class="text-xl font-bold text-red-400">{{ stats.keluar }}</p>
                    </div>
                </div>
            </div>

            <!-- Movements History Table -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Tanggal & Waktu</th>
                                <th scope="col" class="px-6 py-4 font-medium">Produk</th>
                                <th scope="col" class="px-6 py-4 font-medium">Masuk/Keluar</th>
                                <th scope="col" class="px-6 py-4 font-medium">Jumlah</th>
                                <th scope="col" class="px-6 py-4 font-medium">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="move in movements.data" :key="move.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-white font-medium">{{ formatDate(move.created_at) }}</div>
                                    <div class="text-[10px] text-gray-500">{{ formatTime(move.created_at) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-dark-800 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                                            <img v-if="move.product?.image" :src="`/storage/${move.product.image}`" class="w-full h-full object-cover">
                                            <svg v-else class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="font-medium text-gray-200">{{ move.product?.name || 'Produk Dihapus' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border', move.type === 'produk masuk' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20']">
                                        {{ move.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold">
                                    <span :class="move.type === 'produk masuk' ? 'text-green-400' : 'text-red-400'">
                                        {{ move.type === 'produk masuk' ? '+' : '-' }}{{ move.quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <!-- Auto-tag for sold products -->
                                        <span v-if="move.reference && move.reference.startsWith('Produk terjual')" class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            Terjual
                                        </span>
                                        <span v-else-if="move.reference && move.reference.startsWith('Pembatalan')" class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Dibatalkan
                                        </span>
                                        <span v-else-if="move.reference && move.reference.startsWith('Pesanan dihapus')" class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Dihapus
                                        </span>
                                        <span class="text-gray-400 italic text-xs">{{ move.reference || '-' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="movements.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>Belum ada riwayat pergerakan stok.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="movements.links && movements.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-center">
                    <template v-for="(link, pIndex) in movements.links" :key="pIndex">
                        <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
                        <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all duration-200', link.active ? 'bg-electric text-white border-electric shadow-sm shadow-electric/20' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50 hover:bg-dark-700']" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import SortFilterBar from '../Components/SortFilterBar.vue';

const props = defineProps({
    movements: {
        type: Object, // Laravel Paginated Object
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, masuk: 0, keluar: 0 }),
    },
    admin: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const typeFilters = [
    { value: '', label: 'Semua', activeClass: 'bg-electric/10 text-electric border-electric/30' },
    { value: 'produk masuk', label: 'Masuk', activeClass: 'bg-green-500/10 text-green-400 border-green-500/30' },
    { value: 'produk keluar', label: 'Keluar', activeClass: 'bg-red-500/10 text-red-400 border-red-500/30' },
];

// Sort & filter state
const activeSortBy = ref(props.filters?.sort_by || 'newest');
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');
const activeType = ref(props.filters?.type || '');

const buildParams = () => {
    const params = {};
    if (activeSortBy.value !== 'newest') params.sort_by = activeSortBy.value;
    if (dateFrom.value) params.date_from = dateFrom.value;
    if (dateTo.value) params.date_to = dateTo.value;
    if (activeType.value) params.type = activeType.value;
    return params;
};

const applyFilters = () => {
    router.get('/admin/stockmov', buildParams(), { preserveState: true, replace: true });
};

const setTypeFilter = (type) => {
    activeType.value = type;
    applyFilters();
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};
</script>
