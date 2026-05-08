<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Stok Produk</h2>
                    <p class="text-gray-400 text-sm">Kelola dan perbarui stok setiap produk</p>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto items-stretch sm:items-center gap-3">
                    <div v-if="filters?.search" class="flex items-center justify-between sm:justify-start gap-2 px-3 py-2 bg-electric/10 border border-electric/20 rounded-xl text-sm">
                        <span class="text-gray-300">Pencarian: <span class="text-white font-semibold">"{{ filters.search }}"</span></span>
                        <button @click="clearSearch" class="text-gray-400 hover:text-red-400 transition-colors p-0.5" aria-label="Clear search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <SearchInput v-model="searchQuery" @search="handleSearch" @clear="clearSearch" placeholder="Cari Produk..." />
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Produk</th>
                                <th scope="col" class="px-6 py-4 font-medium">Kategori</th>
                                <th scope="col" class="px-6 py-4 font-medium">Stok</th>
                                <th scope="col" class="px-6 py-4 font-medium">Status</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products.data" :key="product.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-dark-800 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                                            <img v-if="product.image" :src="`/storage/${product.image}`" class="w-full h-full object-cover">
                                            <svg v-else class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-white mb-0.5">{{ product.name }}</div>
                                            <div class="text-[10px] text-gray-500 max-w-[150px] truncate">{{ product.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium bg-dark-800 text-gray-300 rounded-md border border-dark-600">
                                        {{ product.category?.name || 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['px-3 py-1.5 text-sm font-bold rounded-md border inline-flex items-center justify-center min-w-[3rem]', product.stock > 10 ? 'bg-green-500/10 text-green-400 border-green-500/20' : (product.stock > 0 ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20')]">
                                        {{ product.stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border', product.status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20']">
                                        {{ product.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openUpdateModal(product)" class="px-3 py-1.5 bg-electric/10 hover:bg-electric/20 text-electric border border-electric/20 rounded-lg text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Update Stok
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="products.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p>Tidak ada produk ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="products.links && products.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-center">
                    <template v-for="(link, pIndex) in products.links" :key="pIndex">
                        <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
                        <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all duration-200', link.active ? 'bg-electric text-white border-electric shadow-sm shadow-electric/20' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50 hover:bg-dark-700']" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Stock Update Modal -->
        <Modal
            :is-open="isModalOpen"
            @close="closeModal"
            title="Update Stok Produk"
            max-width="sm"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </template>

            <div v-if="selectedProduct" class="mb-5">
                <div class="flex items-center gap-3 p-3 bg-dark-800/60 rounded-xl border border-dark-600/50">
                    <div class="w-10 h-10 rounded-lg bg-dark-700 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                        <img v-if="selectedProduct.image" :src="`/storage/${selectedProduct.image}`" class="w-full h-full object-cover">
                        <svg v-else class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <div class="font-medium text-white text-sm">{{ selectedProduct.name }}</div>
                        <div class="text-xs text-gray-500">Stok saat ini: <span class="font-bold" :class="selectedProduct.stock > 0 ? 'text-green-400' : 'text-red-400'">{{ selectedProduct.stock }}</span></div>
                    </div>
                </div>
            </div>

            <form id="stockUpdateForm" @submit.prevent="submitForm" class="space-y-5">
                <div class="space-y-4">
                    <!-- Type selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Jenis Pergerakan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button"
                                @click="form.type = 'produk masuk'"
                                :class="['px-4 py-2.5 rounded-xl border text-sm font-medium transition-all flex items-center justify-center gap-2', form.type === 'produk masuk' ? 'bg-green-500/10 border-green-500/50 text-green-400' : 'bg-dark-900 border-dark-600 text-gray-400 hover:border-dark-500']"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                Masuk
                            </button>
                            <button type="button"
                                @click="form.type = 'produk keluar'"
                                :class="['px-4 py-2.5 rounded-xl border text-sm font-medium transition-all flex items-center justify-center gap-2', form.type === 'produk keluar' ? 'bg-red-500/10 border-red-500/50 text-red-400' : 'bg-dark-900 border-dark-600 text-gray-400 hover:border-dark-500']"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                Keluar
                            </button>
                        </div>
                        <div v-if="form.errors.type" class="mt-1 text-xs text-red-500">{{ form.errors.type }}</div>
                    </div>

                    <!-- Quantity -->
                    <TextInput
                        id="quantity"
                        type="number"
                        label="Jumlah"
                        v-model="form.quantity"
                        placeholder="1"
                        :error="form.errors.quantity"
                        min="1"
                        required
                    />

                    <!-- Reference/Keterangan -->
                    <TextInput
                        id="reference"
                        type="text"
                        label="Keterangan"
                        v-model="form.reference"
                        placeholder="ex: Restok dari supplier, Barang rusak, dll"
                        :error="form.errors.reference"
                    />
                </div>
            </form>

            <template #footer>
                <button type="button" @click="closeModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Batal
                </button>
                <button type="submit" form="stockUpdateForm" :disabled="form.processing" class="px-5 py-2.5 bg-electric hover:bg-electric/88 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20 flex items-center gap-2">
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import SearchInput from '../Components/SearchInput.vue';
import Modal from '../Components/Modal.vue';
import TextInput from '../Components/TextInput.vue';

const props = defineProps({
    products: {
        type: Object, // Laravel Paginated Object
        required: true,
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

const searchQuery = ref('');
const handleSearch = () => {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    router.get('/admin/stock', params, { 
        preserveState: true, 
        replace: true,
        onFinish: () => {
            searchQuery.value = '';
        }
    });
};

const clearSearch = () => {
    searchQuery.value = '';
    router.get('/admin/stock', {}, { preserveState: true, replace: true });
};

const isModalOpen = ref(false);
const selectedProduct = ref(null);

const form = useForm({
    type: 'produk masuk',
    quantity: 1,
    reference: '',
});

const openUpdateModal = (product) => {
    selectedProduct.value = product;
    form.reset();
    form.type = 'produk masuk';
    form.quantity = 1;
    form.reference = '';
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        selectedProduct.value = null;
    }, 300);
};

const submitForm = () => {
    if (selectedProduct.value) {
        form.put(`/admin/stock/${selectedProduct.value.id}`, {
            onSuccess: () => closeModal(),
            preserveScroll: true,
        });
    }
};
</script>
