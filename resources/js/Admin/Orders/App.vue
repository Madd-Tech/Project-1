<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Riwayat Pembayaran</h2>
                    <p class="text-gray-400 text-sm">Kelola dan pantau semua transaksi pembayaran.</p>
                </div>
                <!-- Stats Badges -->
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="px-3 py-1.5 bg-amber/10 border border-amber/20 rounded-lg text-xs font-semibold text-amber flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber animate-pulse"></div>
                        Pending: {{ pendingCount }}
                    </div>
                    <div class="px-3 py-1.5 bg-green-500/10 border border-green-500/20 rounded-lg text-xs font-semibold text-green-400 flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                        Paid: {{ paidCount }}
                    </div>
                </div>
            </div>

            <!-- Flash Success -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Filters Bar -->
            <div class="glass-card rounded-2xl p-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search -->
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                    </svg>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari no. invoice, nama, atau email..."
                        class="w-full pl-10 pr-4 py-2.5 bg-dark-800 border border-dark-600 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-electric/50 focus:ring-1 focus:ring-electric/25 transition-all"
                        @keyup.enter="handleSearch"
                    />
                </div>

                <!-- Status Filter -->
                <select
                    v-model="statusFilter"
                    @change="handleSearch"
                    class="px-4 py-2.5 bg-dark-800 border border-dark-600 rounded-xl text-sm text-gray-300 focus:outline-none focus:border-electric/50 transition-all appearance-none cursor-pointer min-w-[160px]"
                >
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                </select>

                <!-- Search Button -->
                <button @click="handleSearch" class="px-4 py-2.5 bg-electric/10 hover:bg-electric/20 text-electric border border-electric/20 rounded-xl text-sm font-medium transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" /></svg>
                    Filter
                </button>

                <!-- Clear -->
                <button v-if="searchQuery || statusFilter" @click="clearFilters" class="px-4 py-2.5 bg-dark-700 hover:bg-dark-600 text-gray-400 hover:text-white border border-dark-600 rounded-xl text-sm transition-all">
                    Reset
                </button>
            </div>

            <!-- Orders Table -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th class="px-6 py-4 font-medium">Invoice</th>
                                <th class="px-6 py-4 font-medium">Pelanggan</th>
                                <th class="px-6 py-4 font-medium">Produk</th>
                                <th class="px-6 py-4 font-medium">Total</th>
                                <th class="px-6 py-4 font-medium">Metode</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Tanggal</th>
                                <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="order in orders.data"
                                :key="order.id"
                                class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors"
                            >
                                <!-- Invoice -->
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-semibold text-white bg-dark-800 border border-dark-600 px-2 py-1 rounded-md">
                                        {{ order.order_number }}
                                    </span>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-white text-sm">{{ order.customer_name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ order.customer_email }}</div>
                                    <div class="text-xs text-gray-500">{{ order.customer_phone }}</div>
                                </td>

                                <!-- Products -->
                                <td class="px-6 py-4">
                                    <div v-if="order.order_items && order.order_items.length > 0" class="space-y-1">
                                        <div
                                            v-for="item in order.order_items.slice(0, 2)"
                                            :key="item.id"
                                            class="flex items-center gap-2"
                                        >
                                            <div class="w-6 h-6 rounded bg-dark-700 overflow-hidden shrink-0">
                                                <img
                                                    v-if="item.product?.image"
                                                    :src="'/storage/' + item.product.image"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-300 truncate max-w-[120px]">
                                                {{ item.product?.name ?? 'Produk Dihapus' }}
                                                <span class="text-gray-500">x{{ item.quantity }}</span>
                                            </span>
                                        </div>
                                        <div v-if="order.order_items.length > 2" class="text-xs text-gray-500">
                                            +{{ order.order_items.length - 2 }} produk lainnya
                                        </div>
                                    </div>
                                    <span v-else class="text-xs text-gray-500">-</span>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-white text-sm">
                                        Rp {{ formatPrice(order.total_amount) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-0.5">Incl. PPN 11%</div>
                                </td>

                                <!-- Payment Method -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded bg-dark-700 text-gray-300 border border-dark-600">
                                            {{ order.payment_method === 'bank_transfer' ? 'Virtual Account' : order.payment_method?.toUpperCase() }}
                                        </span>
                                        <div v-if="order.payment_bank" class="text-xs text-gray-500 uppercase">
                                            {{ order.payment_bank }}
                                        </div>
                                        <div v-if="order.va_number" class="text-xs font-mono text-electric/70">
                                            {{ order.va_number }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border inline-flex items-center gap-1.5', statusClass(order.payment_status)]">
                                        <div :class="['w-1.5 h-1.5 rounded-full', statusDot(order.payment_status)]"></div>
                                        {{ statusLabel(order.payment_status) }}
                                    </span>
                                    <div v-if="order.paid_at" class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(order.paid_at) }}
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">{{ formatDate(order.created_at) }}</div>
                                    <div v-if="order.payment_expired_at && order.payment_status === 'pending'" class="text-xs text-amber mt-0.5">
                                        Exp: {{ formatDate(order.payment_expired_at) }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="openModal(order)"
                                        class="px-3 py-2 bg-electric/10 hover:bg-electric/20 text-electric rounded-lg font-medium transition-colors text-xs border border-electric/20 inline-flex items-center gap-1.5 whitespace-nowrap"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Edit Status
                                    </button>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="orders.data.length === 0">
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-dark-800 border border-dark-600 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">Tidak ada riwayat transaksi ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="orders.links && orders.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-between">
                    <span class="text-xs text-gray-500">
                        Menampilkan {{ orders.from }}–{{ orders.to }} dari {{ orders.total }} transaksi
                    </span>
                    <div class="flex flex-wrap gap-1">
                        <template v-for="(link, pIndex) in orders.links" :key="pIndex">
                            <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
                            <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all duration-200', link.active ? 'bg-electric text-white border-electric shadow-sm shadow-electric/20' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50 hover:bg-dark-700']" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Status Modal -->
        <Modal :is-open="isModalOpen" @close="closeModal" title="Edit Status Pembayaran" max-width="sm">
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
            </template>

            <div v-if="selectedOrder" class="space-y-5">
                <!-- Order Info Preview -->
                <div class="p-4 bg-dark-800 border border-dark-600 rounded-xl space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Invoice</span>
                        <span class="font-mono text-xs font-bold text-white">{{ selectedOrder.order_number }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Pelanggan</span>
                        <span class="text-xs text-gray-300">{{ selectedOrder.customer_name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Total</span>
                        <span class="text-xs font-semibold text-white">Rp {{ formatPrice(selectedOrder.total_amount) }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-dark-600">
                        <span class="text-xs text-gray-500">Status Saat Ini</span>
                        <span :class="['px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded border', statusClass(selectedOrder.payment_status)]">
                            {{ statusLabel(selectedOrder.payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Status Selector -->
                <div>
                    <label class="block text-sm text-gray-400 mb-3 font-medium">Ubah Status Menjadi:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            v-for="opt in statusOptions"
                            :key="opt.value"
                            type="button"
                            @click="form.payment_status = opt.value"
                            :class="[
                                'p-3 rounded-xl border-2 transition-all duration-200 flex items-center gap-2.5 text-left',
                                form.payment_status === opt.value
                                    ? opt.activeClass
                                    : 'border-dark-600 bg-dark-800 hover:border-dark-500'
                            ]"
                        >
                            <div :class="['w-2 h-2 rounded-full shrink-0', opt.dotClass]"></div>
                            <div>
                                <p :class="['text-xs font-bold uppercase tracking-wide', form.payment_status === opt.value ? opt.textClass : 'text-gray-400']">
                                    {{ opt.label }}
                                </p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ opt.description }}</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Warning for sensitive changes -->
                <div v-if="form.payment_status === 'paid'" class="flex items-start gap-2.5 p-3 bg-green-500/5 border border-green-500/20 rounded-xl">
                    <svg class="w-4 h-4 text-green-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-xs text-green-400">Menandai sebagai <strong>Paid</strong> akan mencatat waktu pembayaran sekarang.</p>
                </div>
                <div v-else-if="form.payment_status === 'cancelled'" class="flex items-start gap-2.5 p-3 bg-red-500/5 border border-red-500/20 rounded-xl">
                    <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <p class="text-xs text-red-400">Tindakan ini akan membatalkan pesanan ini.</p>
                </div>
            </div>

            <template #footer>
                <button type="button" @click="closeModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all text-sm">
                    Batal
                </button>
                <button
                    type="button"
                    @click="submitStatus"
                    :disabled="form.processing || form.payment_status === selectedOrder?.payment_status"
                    class="px-5 py-2.5 bg-electric hover:bg-electric/80 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20 flex items-center gap-2 text-sm"
                >
                    <svg v-if="form.processing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
            </template>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import Modal from '../Components/Modal.vue';

const props = defineProps({
    orders: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    admin: { type: Object, required: true },
});

// ── Search & Filter ──────────────────────────────────────
const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');

const handleSearch = () => {
    router.get('/admin/orders', {
        search: searchQuery.value,
        status: statusFilter.value,
    }, { preserveState: true, replace: true });
};

const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = '';
    handleSearch();
};

// ── Stats ────────────────────────────────────────────────
const pendingCount = computed(() => props.orders.data.filter(o => o.payment_status === 'pending').length);
const paidCount    = computed(() => props.orders.data.filter(o => o.payment_status === 'paid').length);

// ── Modal ────────────────────────────────────────────────
const isModalOpen    = ref(false);
const selectedOrder  = ref(null);

const form = useForm({ payment_status: '' });

const statusOptions = [
    {
        value: 'pending',
        label: 'Pending',
        description: 'Menunggu pembayaran',
        dotClass: 'bg-amber',
        activeClass: 'border-amber bg-amber/5',
        textClass: 'text-amber',
    },
    {
        value: 'paid',
        label: 'Paid',
        description: 'Pembayaran diterima',
        dotClass: 'bg-green-400',
        activeClass: 'border-green-400 bg-green-500/5',
        textClass: 'text-green-400',
    },
    {
        value: 'cancelled',
        label: 'Cancelled',
        description: 'Pesanan dibatalkan',
        dotClass: 'bg-red-400',
        activeClass: 'border-red-400 bg-red-500/5',
        textClass: 'text-red-400',
    },
    {
        value: 'expired',
        label: 'Expired',
        description: 'Batas waktu habis',
        dotClass: 'bg-gray-500',
        activeClass: 'border-gray-500 bg-gray-500/5',
        textClass: 'text-gray-400',
    },
];

const openModal = (order) => {
    selectedOrder.value = order;
    form.payment_status = order.payment_status;
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => { selectedOrder.value = null; }, 300);
};

const submitStatus = () => {
    if (!selectedOrder.value) return;
    form.patch(`/admin/orders/${selectedOrder.value.id}/status`, {
        onSuccess: () => closeModal(),
        preserveScroll: true,
    });
};

// ── Helpers ──────────────────────────────────────────────
const formatPrice = (price) => new Intl.NumberFormat('id-ID').format(Math.round(price));

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
};

const statusClass = (status) => {
    switch (status) {
        case 'pending':   return 'bg-amber/10 text-amber border-amber/20';
        case 'paid':      return 'bg-green-500/10 text-green-400 border-green-500/20';
        case 'cancelled': return 'bg-red-500/10 text-red-400 border-red-500/20';
        case 'expired':   return 'bg-gray-500/10 text-gray-400 border-gray-500/20';
        default:          return 'bg-dark-700 text-gray-400 border-dark-600';
    }
};

const statusDot = (status) => {
    switch (status) {
        case 'pending':   return 'bg-amber animate-pulse';
        case 'paid':      return 'bg-green-400';
        case 'cancelled': return 'bg-red-400';
        case 'expired':   return 'bg-gray-500';
        default:          return 'bg-gray-500';
    }
};

const statusLabel = (status) => {
    switch (status) {
        case 'pending':   return 'Pending';
        case 'paid':      return 'Paid';
        case 'cancelled': return 'Cancelled';
        case 'expired':   return 'Expired';
        default:          return status ?? '-';
    }
};
</script>
