<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Kelola Pesanan</h2>
                    <p class="text-gray-400 text-sm">Pantau dan kelola pesanan dari pelanggan</p>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto items-stretch sm:items-center gap-3">
                    <div v-if="filters?.search" class="flex items-center justify-between sm:justify-start gap-2 px-3 py-2 bg-electric/10 border border-electric/20 rounded-xl text-sm">
                        <span class="text-gray-300">Pencarian: <span class="text-white font-semibold">"{{ filters.search }}"</span></span>
                        <button @click="clearSearch" class="text-gray-400 hover:text-red-400 transition-colors p-0.5" aria-label="Clear search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <SearchInput v-model="searchQuery" @search="handleSearch" @clear="clearSearch" placeholder="Cari pesanan..." />
                </div>
            </div>

            <!-- Flash -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div v-for="stat in statCards" :key="stat.label" class="glass-card rounded-2xl p-4 text-center">
                    <p class="text-2xl font-bold" :class="stat.color">{{ stat.value }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="glass-card rounded-2xl p-4 flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Status:</span>
                    <select v-model="statusFilter" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Bayar:</span>
                    <select v-model="paymentFilter" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="transfer">Transfer</option>
                        <option value="cod">COD</option>
                        <option value="datang_ke_toko">Datang ke Toko</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider">Sort:</span>
                    <select v-model="sortBy" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 appearance-none cursor-pointer">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="amount_high">Total Tertinggi</option>
                        <option value="amount_low">Total Terendah</option>
                    </select>
                </div>
                <button v-if="hasActiveFilters" @click="resetFilters" class="ml-auto px-4 py-2 text-xs font-medium text-gray-400 hover:text-white bg-dark-700/50 hover:bg-dark-600 border border-dark-600 rounded-xl transition-all">Reset</button>
            </div>

            <!-- Orders Table -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th class="px-6 py-4 font-medium">Order</th>
                                <th class="px-6 py-4 font-medium">Pelanggan</th>
                                <th class="px-6 py-4 font-medium">Pembayaran</th>
                                <th class="px-6 py-4 font-medium">Total</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Tanggal</th>
                                <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders.data" :key="order.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-electric font-mono text-xs font-bold">{{ order.order_number }}</p>
                                    <p class="text-gray-500 text-[10px]">{{ order.items?.length || 0 }} produk</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white font-medium text-sm">{{ order.customer_name }}</p>
                                    <p class="text-gray-500 text-[10px]">{{ order.customer_phone }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border bg-dark-800 text-gray-300 border-dark-600">
                                        {{ paymentLabel(order.payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-white font-medium text-sm">Rp {{ formatPrice(order.total_amount) }}</td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <select
                                            :value="order.status"
                                            @change="updateStatus(order, $event.target.value)"
                                            :disabled="isCancelledByCustomer(order)"
                                            :class="[
                                                'px-2.5 py-1 text-xs font-bold rounded-lg border appearance-none',
                                                isCancelledByCustomer(order) ? 'cursor-not-allowed opacity-60' : 'cursor-pointer',
                                                statusClass(order.status)
                                            ]"
                                        >
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <p v-if="isCancelledByCustomer(order)" class="text-[10px] text-red-300">
                                            Cancelled by customer
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">{{ formatDate(order.created_at) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Confirm Button: only for pending transfer orders -->
                                        <button
                                            v-if="order.status === 'pending' && order.payment_method === 'transfer'"
                                            @click="confirmOrder(order)"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-[#25D366]/10 hover:bg-[#25D366]/20 border border-[#25D366]/30 hover:border-[#25D366]/60 text-[#25D366] text-xs font-semibold rounded-lg transition-all duration-200"
                                            title="Konfirmasi & kirim no rekening via WhatsApp"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            Konfirmasi
                                        </button>
                                        <button @click="openDetail(order)" class="text-gray-400 hover:text-electric transition-colors p-1.5 rounded-lg hover:bg-dark-700" title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button @click="confirmDelete(order)" class="text-gray-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-dark-700" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="orders.data.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mb-3 mx-auto text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p>Belum ada pesanan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div v-if="orders.links && orders.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-center">
                    <template v-for="(link, i) in orders.links" :key="i">
                        <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
                        <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all', link.active ? 'bg-electric text-white border-electric' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50']" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <Modal :is-open="isDetailOpen" @close="isDetailOpen = false" title="Detail Pesanan" max-width="lg">
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </template>
            <div v-if="selectedOrder" class="space-y-5">
                <div class="flex items-center justify-between p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <div>
                        <p class="text-xs text-gray-400">No. Pesanan</p>
                        <p class="text-electric font-mono font-bold">{{ selectedOrder.order_number }}</p>
                    </div>
                    <span :class="['px-3 py-1 text-xs font-bold uppercase rounded-lg border', statusClass(selectedOrder.status)]">{{ selectedOrder.status }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 mb-1">Nama</p>
                        <p class="text-white font-medium">{{ selectedOrder.customer_name }}</p>
                    </div>
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 mb-1">Telepon</p>
                        <p class="text-white font-medium">{{ selectedOrder.customer_phone }}</p>
                    </div>
                </div>
                <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <p class="text-xs text-gray-400 mb-1">Alamat</p>
                    <p class="text-white text-sm">{{ selectedOrder.customer_address }}</p>
                    <a v-if="selectedOrder.latitude && selectedOrder.longitude"
                       :href="`https://www.google.com/maps?q=${selectedOrder.latitude},${selectedOrder.longitude}`"
                       target="_blank"
                       class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-electric/10 hover:bg-electric/20 text-electric text-xs font-semibold rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Buka di Google Maps
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 mb-1">Pembayaran</p>
                        <p class="text-white font-medium">{{ paymentLabel(selectedOrder.payment_method) }}</p>
                    </div>
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 mb-1">Total</p>
                        <p class="text-white font-bold">Rp {{ formatPrice(selectedOrder.total_amount) }}</p>
                    </div>
                </div>
                <div v-if="selectedOrder.notes" class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <p class="text-xs text-gray-400 mb-1">Catatan</p>
                    <p class="text-gray-200 text-sm">{{ selectedOrder.notes }}</p>
                </div>
                <div v-if="isCancelledByCustomer(selectedOrder)" class="p-4 bg-red-500/5 rounded-xl border border-red-500/20">
                    <p class="text-xs text-red-300 font-semibold uppercase tracking-wider">Status Note</p>
                    <p class="text-red-200 text-sm mt-1">Cancelled by customer</p>
                </div>
                <!-- Items -->
                <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Produk</p>
                    <div v-for="item in selectedOrder.items" :key="item.id" class="flex items-center justify-between py-2 border-b border-dark-600/30 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-dark-800 border border-dark-600 overflow-hidden">
                                <img v-if="item.product?.image" :src="`/storage/${item.product.image}`" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm text-white">{{ item.product_name }}</p>
                                <p class="text-[10px] text-gray-500">x{{ item.quantity }} @ Rp {{ formatPrice(item.price) }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white font-medium">Rp {{ formatPrice(item.subtotal) }}</p>
                    </div>
                </div>
            </div>
            <template #footer>
                <button @click="isDetailOpen = false" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">Tutup</button>
            </template>
        </Modal>

        <!-- Delete Modal -->
        <Modal :is-open="isDeleteOpen" @close="isDeleteOpen = false" title="Hapus Pesanan" max-width="md">
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
            </template>
            <div class="text-center py-4">
                <div class="w-16 h-16 mx-auto bg-red-500/10 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="text-gray-300">Hapus pesanan <span class="text-white font-medium">"{{ orderToDelete?.order_number }}"</span>?</p>
                <p class="text-gray-500 text-xs mt-2">Tindakan ini tidak bisa dibatalkan.</p>
            </div>
            <template #footer>
                <button @click="isDeleteOpen = false" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">Batal</button>
                <button @click="deleteOrder" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/20">
                    {{ deleteForm.processing ? 'Menghapus...' : 'Hapus' }}
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
import SearchInput from '../Components/SearchInput.vue';

const props = defineProps({
    orders: { type: Object, required: true },
    admin: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    stats: { type: Object, default: () => ({}) },
});

const searchQuery = ref('');
const statusFilter = ref(props.filters?.status || '');
const paymentFilter = ref(props.filters?.payment_method || '');
const sortBy = ref(props.filters?.sort_by || 'newest');

const statCards = computed(() => [
    { label: 'Total', value: props.stats.total, color: 'text-white' },
    { label: 'Pending', value: props.stats.pending, color: 'text-amber-400' },
    { label: 'Confirmed', value: props.stats.confirmed, color: 'text-blue-400' },
    { label: 'Completed', value: props.stats.completed, color: 'text-green-400' },
    { label: 'Cancelled', value: props.stats.cancelled, color: 'text-red-400' },
]);

const hasActiveFilters = computed(() => statusFilter.value || paymentFilter.value || sortBy.value !== 'newest');

const buildParams = (isSearch = false) => {
    const p = {};
    if (isSearch) {
        if (searchQuery.value) p.search = searchQuery.value;
    } else {
        if (searchQuery.value) p.search = searchQuery.value;
        else if (props.filters?.search) p.search = props.filters.search;
    }
    if (statusFilter.value) p.status = statusFilter.value;
    if (paymentFilter.value) p.payment_method = paymentFilter.value;
    if (sortBy.value !== 'newest') p.sort_by = sortBy.value;
    return p;
};

const handleSearch = () => {
    router.get('/admin/orders', buildParams(true), { 
        preserveState: true, 
        replace: true,
        onFinish: () => {
            searchQuery.value = '';
        }
    });
};
const applyFilters = () => router.get('/admin/orders', buildParams(false), { preserveState: true, replace: true });
const resetFilters = () => { statusFilter.value = ''; paymentFilter.value = ''; sortBy.value = 'newest'; applyFilters(); };
const clearSearch = () => {
    searchQuery.value = '';
    const params = buildParams(false);
    delete params.search;
    router.get('/admin/orders', params, { preserveState: true, replace: true });
};

// Detail
const isDetailOpen = ref(false);
const selectedOrder = ref(null);
const openDetail = (o) => { selectedOrder.value = o; isDetailOpen.value = true; };

// Delete
const isDeleteOpen = ref(false);
const orderToDelete = ref(null);
const deleteForm = useForm({});
const confirmDelete = (o) => { orderToDelete.value = o; isDeleteOpen.value = true; };
const deleteOrder = () => {
    if (orderToDelete.value) {
        deleteForm.delete(`/admin/orders/${orderToDelete.value.id}`, { onSuccess: () => { isDeleteOpen.value = false; orderToDelete.value = null; } });
    }
};

// Bank account config
const bankName = 'BCA';
const bankAccount = '7295250216';
const bankHolder = 'perilispami';

// Format phone to international WhatsApp format (08xx -> 628xx)
const formatPhoneForWa = (phone) => {
    let cleaned = phone.replace(/[^0-9]/g, '');
    if (cleaned.startsWith('0')) cleaned = '62' + cleaned.substring(1);
    if (!cleaned.startsWith('62')) cleaned = '62' + cleaned;
    return cleaned;
};

// Build WhatsApp transfer confirmation message
const buildTransferMessage = (order) => {
    const itemList = (order.items || [])
        .map(item => `- ${item.product_name} (x${item.quantity})`)
        .join('\n');

    return `Halo *${order.customer_name}*! 
    👋\n\nPesanan Anda telah *dikonfirmasi* ✅
    \n\n*No. Pesanan:* ${order.order_number}\n
    *Detail Produk:*\n${itemList}\n\n 
    *Total: Rp ${formatPrice(order.total_amount)}*
    \n\nSilakan transfer ke rekening berikut:\n
    *Bank:* ${bankName}\n
    *No. Rekening:* ${bankAccount}\n
    *Atas Nama:* ${bankHolder}\n\nSetelah transfer, mohon kirimkan bukti pembayaran ke chat ini.\n\nTerima kasih! 🙏`;
};

// Dedicated confirm button — always sends WhatsApp with bank info
const confirmOrder = (order) => {
    router.put(`/admin/orders/${order.id}/status`, { status: 'confirmed' }, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            if (order.payment_method !== 'transfer') return;
            const phone = formatPhoneForWa(order.customer_phone);
            const message = encodeURIComponent(buildTransferMessage(order));
            window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
        },
    });
};

// Status update (dropdown — only triggers WhatsApp for transfer→confirmed)
const updateStatus = (order, status) => {
    if (isCancelledByCustomer(order)) return;

    const isTransferConfirm = status === 'confirmed' && order.payment_method === 'transfer';

    router.put(`/admin/orders/${order.id}/status`, { status }, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            if (isTransferConfirm) {
                const phone = formatPhoneForWa(order.customer_phone);
                const message = encodeURIComponent(buildTransferMessage(order));
                window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
            }
        },
    });
};

const isCancelledByCustomer = (order) => {
    return (order?.notes || '').includes('[Cancelled by customer]');
};

const formatPrice = (p) => new Intl.NumberFormat('id-ID').format(Math.round(p));
const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
const paymentLabel = (m) => ({ transfer: 'Transfer', cod: 'COD', datang_ke_toko: 'Ke Toko' }[m] || m);
const statusClass = (s) => ({
    pending: 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    confirmed: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    processing: 'bg-purple-500/10 text-purple-400 border-purple-500/20',
    completed: 'bg-green-500/10 text-green-400 border-green-500/20',
    cancelled: 'bg-red-500/10 text-red-400 border-red-500/20',
}[s] || 'bg-gray-500/10 text-gray-400 border-gray-500/20');
</script>
