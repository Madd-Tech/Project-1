<template>
  <AdminLayout :admin="admin">
    <div class="space-y-8">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h2 class="text-3xl font-bold text-white mb-2">Data Pelanggan</h2>
          <p class="text-gray-400 text-sm">Pelanggan yang sudah register/login akan tampil di sini</p>
        </div>
        <SearchInput v-model="searchQuery" @search="handleSearch" @clear="clearSearch" placeholder="Cari pelanggan..." />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-card rounded-2xl p-4 text-center">
          <p class="text-2xl font-bold text-white">{{ stats.total ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">Total Pelanggan</p>
        </div>
        <div class="glass-card rounded-2xl p-4 text-center">
          <p class="text-2xl font-bold text-electric">{{ stats.with_orders ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">Pernah Order</p>
        </div>
        <div class="glass-card rounded-2xl p-4 text-center">
          <p class="text-2xl font-bold text-neon">{{ stats.new_this_month ?? 0 }}</p>
          <p class="text-xs text-gray-500 mt-1">Baru Bulan Ini</p>
        </div>
      </div>

      <div class="glass-card rounded-2xl p-4 flex items-center gap-3">
        <span class="text-xs text-gray-400 uppercase tracking-wider">Sort:</span>
        <select v-model="sortBy" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50">
          <option value="newest">Terbaru</option>
          <option value="oldest">Terlama</option>
          <option value="orders_high">Order Terbanyak</option>
          <option value="amount_high">Total Belanja Tertinggi</option>
        </select>
      </div>

      <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
              <tr>
                <th class="px-6 py-4 font-medium">Pelanggan</th>
                <th class="px-6 py-4 font-medium">Kontak</th>
                <th class="px-6 py-4 font-medium">Order</th>
                <th class="px-6 py-4 font-medium">Total Belanja</th>
                <th class="px-6 py-4 font-medium">Bergabung</th>
                <th class="px-6 py-4 font-medium text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in customers.data" :key="customer.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                <td class="px-6 py-4">
                  <p class="text-white font-medium">{{ customer.name }}</p>
                  <p class="text-gray-500 text-xs">{{ customer.email }}</p>
                </td>
                <td class="px-6 py-4 text-xs text-gray-300">
                  <p>{{ customer.phone || '-' }}</p>
                </td>
                <td class="px-6 py-4">{{ customer.orders_count ?? 0 }}</td>
                <td class="px-6 py-4">Rp {{ formatPrice(customer.orders_sum_total_amount ?? 0) }}</td>
                <td class="px-6 py-4 text-xs text-gray-400">{{ formatDate(customer.created_at) }}</td>
                <td class="px-6 py-4 text-right">
                  <button @click="confirmDelete(customer)" class="text-gray-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-dark-700" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </td>
              </tr>
              <tr v-if="customers.data.length === 0">
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data pelanggan.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="customers.links && customers.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-center">
          <template v-for="(link, i) in customers.links" :key="i">
            <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
            <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all', link.active ? 'bg-electric text-white border-electric' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50']" v-html="link.label" />
          </template>
        </div>
      </div>
    </div>

    <Modal :is-open="isDeleteOpen" @close="isDeleteOpen = false" title="Hapus Pelanggan" max-width="md">
      <div class="text-center py-4">
        <p class="text-gray-300">Hapus pelanggan <span class="text-white font-medium">"{{ customerToDelete?.name }}"</span>?</p>
        <p class="text-gray-500 text-xs mt-2">Tindakan ini tidak bisa dibatalkan.</p>
      </div>
      <template #footer>
        <button @click="isDeleteOpen = false" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">Batal</button>
        <button @click="deleteCustomer" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white rounded-xl font-medium transition-all">
          {{ deleteForm.processing ? 'Menghapus...' : 'Hapus' }}
        </button>
      </template>
    </Modal>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import SearchInput from '../Components/SearchInput.vue';
import Modal from '../Components/Modal.vue';

const props = defineProps({
  customers: { type: Object, required: true },
  admin: { type: Object, required: true },
  filters: { type: Object, default: () => ({}) },
  stats: { type: Object, default: () => ({}) },
});

const searchQuery = ref('');
const sortBy = ref(props.filters?.sort_by || 'newest');

const buildParams = (isSearch = false) => {
  const p = {};
  if (isSearch) {
    if (searchQuery.value) p.search = searchQuery.value;
  } else if (props.filters?.search) {
    p.search = props.filters.search;
  }
  if (sortBy.value !== 'newest') p.sort_by = sortBy.value;
  return p;
};

const handleSearch = () => {
  router.get('/admin/customers', buildParams(true), {
    preserveState: true,
    replace: true,
    onFinish: () => {
      searchQuery.value = '';
    },
  });
};

const clearSearch = () => {
  searchQuery.value = '';
  const params = buildParams(false);
  delete params.search;
  router.get('/admin/customers', params, { preserveState: true, replace: true });
};

const applyFilters = () => {
  router.get('/admin/customers', buildParams(false), { preserveState: true, replace: true });
};

const isDeleteOpen = ref(false);
const customerToDelete = ref(null);
const deleteForm = useForm({});

const confirmDelete = (customer) => {
  customerToDelete.value = customer;
  isDeleteOpen.value = true;
};

const deleteCustomer = () => {
  if (!customerToDelete.value) return;
  deleteForm.delete(`/admin/customers/${customerToDelete.value.id}`, {
    onSuccess: () => {
      isDeleteOpen.value = false;
      customerToDelete.value = null;
    },
  });
};

const formatPrice = (p) => new Intl.NumberFormat('id-ID').format(Math.round(p));
const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
</script>
