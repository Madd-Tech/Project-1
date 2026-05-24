<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Riwayat Pergerakan Stok</h2>
                    <p class="text-gray-400 text-sm">Monitoring semua pemasukan dan pengeluaran stok di seluruh inventaris Anda.</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Type filter pills -->
                    <div class="flex items-center gap-2">
                        <button
                            v-for="filter in typeFilters"
                            :key="filter.value"
                            @click="setTypeFilter(filter.value)"
                            :disabled="isMasukDisabled(filter.value)"
                            :class="[
                                'px-3.5 py-1.5 rounded-lg text-xs font-medium border transition-all duration-200',
                                activeType === filter.value
                                    ? filter.activeClass
                                    : 'bg-dark-800 text-gray-400 border-dark-600 hover:border-dark-500 hover:text-gray-300',
                                isMasukDisabled(filter.value) ? 'opacity-50 cursor-not-allowed hover:border-dark-600 hover:text-gray-400' : ''
                            ]"
                        >
                            {{ filter.label }}
                        </button>
                    </div>

                    <!-- Export PDF Button -->
                    <button
                        @click="showExportModal = true"
                        class="flex items-center gap-2 px-4 py-1.5 rounded-lg text-xs font-semibold border border-electric/30 bg-gradient-to-r from-electric/20 to-neon/10 text-electric hover:from-electric/30 hover:to-neon/20 hover:border-electric/50 hover:text-white transition-all duration-300 shadow-sm shadow-electric/10 hover:shadow-md hover:shadow-electric/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
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
                                <th scope="col" class="px-6 py-4 font-medium">Total Pesanan</th>
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
                                    <span v-if="move.order_total_amount !== null" class="font-semibold text-white">
                                        Rp {{ formatPrice(move.order_total_amount) }}
                                    </span>
                                    <span v-else class="text-gray-500">-</span>
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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

        <!-- ========== Export PDF Modal ========== -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showExportModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showExportModal = false"></div>

                    <!-- Modal -->
                    <div class="relative w-full max-w-lg bg-dark-900/95 border border-dark-600/50 rounded-3xl shadow-2xl shadow-black/40 overflow-hidden animate-fade-in-up">
                        <!-- Header gradient -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-electric via-neon to-electric"></div>

                        <!-- Content -->
                        <div class="p-8">
                            <!-- Title -->
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-11 h-11 rounded-xl bg-electric/10 border border-electric/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Export ke PDF</h3>
                                    <p class="text-xs text-gray-500">Sesuaikan data yang akan diexport</p>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Urutkan</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="opt in exportSortOptions"
                                            :key="opt.value"
                                            @click="exportSort = opt.value"
                                            :class="[
                                                'px-4 py-2 text-xs font-medium rounded-xl border transition-all duration-300',
                                                exportSort === opt.value
                                                    ? 'bg-electric/20 text-electric border-electric/40 shadow-sm shadow-electric/10'
                                                    : 'bg-dark-800 text-gray-400 border-dark-600 hover:border-dark-500 hover:text-white'
                                            ]"
                                        >
                                            {{ opt.label }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Type filter -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Tipe Pergerakan</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="filter in exportTypeOptions"
                                            :key="filter.value"
                                            @click="exportType = filter.value"
                                            :disabled="isExportMasukDisabled(filter.value)"
                                            :class="[
                                                'px-4 py-2 text-xs font-medium rounded-xl border transition-all duration-300',
                                                exportType === filter.value
                                                    ? filter.activeClass
                                                    : 'bg-dark-800 text-gray-400 border-dark-600 hover:border-dark-500 hover:text-white',
                                                isExportMasukDisabled(filter.value) ? 'opacity-50 cursor-not-allowed hover:border-dark-600 hover:text-gray-400' : ''
                                            ]"
                                        >
                                            {{ filter.label }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Date range -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Rentang Tanggal</label>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                type="date"
                                                v-model="exportDateFrom"
                                                class="w-full bg-dark-800 border border-dark-600 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-electric/30 focus:border-electric/50 transition-all [color-scheme:dark]"
                                                placeholder="Dari"
                                            >
                                        </div>
                                        <div class="text-gray-500 text-sm font-medium">-</div>
                                        <div class="flex-1">
                                            <input
                                                type="date"
                                                v-model="exportDateTo"
                                                class="w-full bg-dark-800 border border-dark-600 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-electric/30 focus:border-electric/50 transition-all [color-scheme:dark]"
                                                placeholder="Sampai"
                                            >
                                        </div>
                                    </div>
                                    <button
                                        v-if="exportDateFrom || exportDateTo"
                                        @click="exportDateFrom = ''; exportDateTo = ''"
                                        class="mt-2 text-[10px] text-red-400 hover:text-red-300 transition-colors flex items-center gap-1"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Hapus rentang tanggal
                                    </button>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-dark-600/50 my-6"></div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <button
                                    @click="showExportModal = false"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-400 hover:text-white bg-dark-800 hover:bg-dark-700 border border-dark-600 hover:border-dark-500 rounded-xl transition-all duration-300"
                                >
                                    Batal
                                </button>
                                <button
                                    @click="downloadPdf"
                                    class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-electric to-neon rounded-xl shadow-lg shadow-electric/25 hover:shadow-xl hover:shadow-electric/30 hover:brightness-110 transition-all duration-300 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
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
    if (isMasukDisabled(type)) return;
    activeType.value = type;
    applyFilters();
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(Math.round(Number(price) || 0));
};

// ========== Export PDF ==========
const showExportModal = ref(false);
const exportSort = ref(props.filters?.sort_by || 'newest');
const exportType = ref(props.filters?.type || '');
const exportDateFrom = ref(props.filters?.date_from || '');
const exportDateTo = ref(props.filters?.date_to || '');

const exportSortOptions = [
    { value: 'newest', label: 'Terbaru' },
    { value: 'oldest', label: 'Terlama' },
    { value: 'most_total', label: 'Total Terbesar' },
];

const exportTypeOptions = [
    { value: '', label: 'Semua', activeClass: 'bg-electric/10 text-electric border-electric/30' },
    { value: 'produk masuk', label: 'Masuk', activeClass: 'bg-green-500/10 text-green-400 border-green-500/30' },
    { value: 'produk keluar', label: 'Keluar', activeClass: 'bg-red-500/10 text-red-400 border-red-500/30' },
];

const isMasukDisabled = (typeValue) => activeSortBy.value === 'most_total' && typeValue === 'produk masuk';
const isExportMasukDisabled = (typeValue) => exportSort.value === 'most_total' && typeValue === 'produk masuk';

const downloadPdf = () => {
    const params = new URLSearchParams();
    if (exportSort.value && exportSort.value !== 'newest') params.append('sort_by', exportSort.value);
    if (exportType.value) params.append('type', exportType.value);
    if (exportDateFrom.value) params.append('date_from', exportDateFrom.value);
    if (exportDateTo.value) params.append('date_to', exportDateTo.value);

    const queryString = params.toString();
    const url = '/admin/stockmov/export-pdf' + (queryString ? '?' + queryString : '');
    window.open(url, '_blank');
    showExportModal.value = false;
};

watch(activeSortBy, (newSort) => {
    if (newSort === 'most_total' && activeType.value === 'produk masuk') {
        activeType.value = '';
        applyFilters();
    }
});

watch(exportSort, (newSort) => {
    if (newSort === 'most_total' && exportType.value === 'produk masuk') {
        exportType.value = '';
    }
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative,
.modal-leave-to .relative {
    transform: scale(0.95) translateY(10px);
}
</style>

