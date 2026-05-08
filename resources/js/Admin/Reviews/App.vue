<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Kelola Review</h2>
                    <p class="text-gray-400 text-sm">Lihat dan kelola review produk dari pelanggan</p>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto items-stretch sm:items-center gap-3">
                    <div v-if="filters?.search" class="flex items-center justify-between sm:justify-start gap-2 px-3 py-2 bg-electric/10 border border-electric/20 rounded-xl text-sm">
                        <span class="text-gray-300">Pencarian: <span class="text-white font-semibold">"{{ filters.search }}"</span></span>
                        <button @click="clearSearch" class="text-gray-400 hover:text-red-400 transition-colors p-0.5" aria-label="Clear search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <SearchInput v-model="searchQuery" @search="handleSearch" @clear="clearSearch" placeholder="Cari review..." />
                </div>
            </div>

            <!-- Flash Success Message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Filter Bar -->
            <div class="glass-card rounded-2xl p-4 flex flex-wrap items-center gap-3 animate-fade-in-up">
                <!-- Sort -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider whitespace-nowrap">Sort:</span>
                    <select v-model="activeSortBy" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all appearance-none cursor-pointer">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="rating_high">Rating Tertinggi</option>
                        <option value="rating_low">Rating Terendah</option>
                    </select>
                </div>

                <!-- Rating Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider whitespace-nowrap">Rating:</span>
                    <select v-model="ratingFilter" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="5">⭐ 5</option>
                        <option value="4">⭐ 4</option>
                        <option value="3">⭐ 3</option>
                        <option value="2">⭐ 2</option>
                        <option value="1">⭐ 1</option>
                    </select>
                </div>

                <!-- Verified Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 uppercase tracking-wider whitespace-nowrap">Verified:</span>
                    <select v-model="verifiedFilter" @change="applyFilters" class="bg-dark-900 border border-dark-600 rounded-xl py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        <option value="yes">Verified</option>
                        <option value="no">Unverified</option>
                    </select>
                </div>

                <!-- Reset -->
                <button v-if="hasActiveFilters" @click="resetFilters" class="ml-auto px-4 py-2 text-xs font-medium text-gray-400 hover:text-white bg-dark-700/50 hover:bg-dark-600 border border-dark-600 rounded-xl transition-all">
                    Reset Filter
                </button>
            </div>

            <!-- Reviews Table -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Produk</th>
                                <th scope="col" class="px-6 py-4 font-medium">Reviewer</th>
                                <th scope="col" class="px-6 py-4 font-medium">Rating</th>
                                <th scope="col" class="px-6 py-4 font-medium">Komentar</th>
                                <th scope="col" class="px-6 py-4 font-medium">Status</th>
                                <th scope="col" class="px-6 py-4 font-medium">Tanggal</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="review in reviews.data" :key="review.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <!-- Product -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-dark-800 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                                            <img v-if="review.product?.image" :src="`/storage/${review.product.image}`" class="w-full h-full object-cover">
                                            <svg v-else class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <span class="font-medium text-white text-xs max-w-[120px] truncate">{{ review.product?.name || 'Deleted Product' }}</span>
                                    </div>
                                </td>

                                <!-- Reviewer -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-white text-sm">{{ review.reviewer_name }}</div>
                                    <div class="text-[10px] text-gray-500 truncate max-w-[150px]">{{ review.reviewer_email || '-' }}</div>
                                </td>

                                <!-- Rating Stars -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-0.5">
                                        <svg v-for="star in 5" :key="star" :class="star <= review.rating ? 'text-amber-400' : 'text-gray-600'" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-xs text-gray-400 ml-1">({{ review.rating }})</span>
                                    </div>
                                </td>

                                <!-- Comment -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-300 text-xs max-w-[200px] line-clamp-2 cursor-pointer hover:text-white transition-colors" @click="openDetailModal(review)" :title="review.comment">
                                        {{ review.comment }}
                                    </p>
                                </td>

                                <!-- Verified Status -->
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border', review.is_verified ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20']">
                                        {{ review.is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ formatDate(review.created_at) }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openDetailModal(review)" class="text-gray-400 hover:text-electric transition-colors p-1.5 rounded-lg hover:bg-dark-700" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <button @click="confirmDelete(review)" class="text-gray-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-dark-700" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="reviews.data.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <p>Belum ada review.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="reviews.links && reviews.links.length > 3" class="px-6 py-4 border-t border-dark-600/50 bg-dark-800/20 flex flex-wrap items-center gap-1 justify-center">
                    <template v-for="(link, pIndex) in reviews.links" :key="pIndex">
                        <div v-if="link.url === null" class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-dark-800 border border-dark-600 rounded-md cursor-not-allowed" v-html="link.label"></div>
                        <Link v-else :href="link.url" :class="['px-3 py-1.5 text-xs font-medium rounded-md border transition-all duration-200', link.active ? 'bg-electric text-white border-electric shadow-sm shadow-electric/20' : 'bg-dark-800 text-gray-300 border-dark-600 hover:border-electric/50 hover:bg-dark-700']" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Review Detail Modal -->
        <Modal 
            :is-open="isDetailModalOpen" 
            @close="closeDetailModal" 
            title="Detail Review" 
            max-width="lg"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
            </template>

            <div v-if="selectedReview" class="space-y-5">
                <!-- Product Info -->
                <div class="flex items-center gap-4 p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <div class="w-14 h-14 rounded-xl bg-dark-800 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                        <img v-if="selectedReview.product?.image" :src="`/storage/${selectedReview.product.image}`" class="w-full h-full object-cover">
                        <svg v-else class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Produk</p>
                        <p class="text-white font-semibold">{{ selectedReview.product?.name || 'Deleted Product' }}</p>
                    </div>
                </div>

                <!-- Reviewer & Rating -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Reviewer</p>
                        <p class="text-white font-medium">{{ selectedReview.reviewer_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ selectedReview.reviewer_email || '-' }}</p>
                    </div>
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Rating</p>
                        <div class="flex items-center gap-1 mt-1">
                            <svg v-for="star in 5" :key="star" :class="star <= selectedReview.rating ? 'text-amber-400' : 'text-gray-600'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-sm text-gray-300 ml-1 font-medium">{{ selectedReview.rating }}/5</span>
                        </div>
                    </div>
                </div>

                <!-- Status & Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Status</p>
                        <span :class="['px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg border', selectedReview.is_verified ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20']">
                            {{ selectedReview.is_verified ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                    <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="text-white text-sm">{{ formatDate(selectedReview.created_at) }}</p>
                    </div>
                </div>

                <!-- Comment -->
                <div class="p-4 bg-dark-900/50 rounded-xl border border-dark-600/50">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Komentar</p>
                    <p class="text-gray-200 text-sm leading-relaxed whitespace-pre-wrap">{{ selectedReview.comment }}</p>
                </div>
            </div>

            <template #footer>
                <button @click="closeDetailModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Tutup
                </button>
                <button @click="closeDetailModal(); confirmDelete(selectedReview)" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/20">
                    Hapus Review
                </button>
            </template>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal 
            :is-open="isDeleteModalOpen" 
            @close="closeDeleteModal" 
            title="Hapus Review" 
            max-width="md"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
            </template>

            <div class="text-center py-4">
                <div class="w-16 h-16 mx-auto bg-red-500/10 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-gray-300">Anda yakin ingin menghapus review dari <span class="text-white font-medium">"{{ reviewToDelete?.reviewer_name }}"</span>?</p>
                <p class="text-gray-500 text-xs mt-2">Tindakan ini tidak bisa dibatalkan.</p>
            </div>

            <template #footer>
                <button @click="closeDeleteModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Batal
                </button>
                <button @click="deleteReview" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/20">
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
    reviews: {
        type: Object,
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

// Filters
const searchQuery = ref('');
const activeSortBy = ref(props.filters?.sort_by || 'newest');
const ratingFilter = ref(props.filters?.rating || '');
const verifiedFilter = ref(props.filters?.verified || '');

const hasActiveFilters = computed(() => {
    return activeSortBy.value !== 'newest' || ratingFilter.value !== '' || verifiedFilter.value !== '';
});

const buildParams = (isSearch = false) => {
    const params = {};
    if (isSearch) {
        if (searchQuery.value) params.search = searchQuery.value;
    } else {
        if (searchQuery.value) params.search = searchQuery.value;
        else if (props.filters?.search) params.search = props.filters.search;
    }
    if (activeSortBy.value !== 'newest') params.sort_by = activeSortBy.value;
    if (ratingFilter.value) params.rating = ratingFilter.value;
    if (verifiedFilter.value) params.verified = verifiedFilter.value;
    return params;
};

const handleSearch = () => {
    router.get('/admin/reviews', buildParams(true), { 
        preserveState: true, 
        replace: true,
        onFinish: () => {
            searchQuery.value = '';
        }
    });
};

const applyFilters = () => {
    router.get('/admin/reviews', buildParams(false), { preserveState: true, replace: true });
};

const resetFilters = () => {
    activeSortBy.value = 'newest';
    ratingFilter.value = '';
    verifiedFilter.value = '';
    applyFilters();
};

const clearSearch = () => {
    searchQuery.value = '';
    const params = buildParams(false);
    delete params.search;
    router.get('/admin/reviews', params, { preserveState: true, replace: true });
};

// Detail Modal
const isDetailModalOpen = ref(false);
const selectedReview = ref(null);

const openDetailModal = (review) => {
    selectedReview.value = review;
    isDetailModalOpen.value = true;
};

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    selectedReview.value = null;
};

// Delete Logic
const isDeleteModalOpen = ref(false);
const reviewToDelete = ref(null);
const deleteForm = useForm({});

const confirmDelete = (review) => {
    reviewToDelete.value = review;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    reviewToDelete.value = null;
};

const deleteReview = () => {
    if (reviewToDelete.value) {
        deleteForm.delete(`/admin/reviews/${reviewToDelete.value.id}`, {
            onSuccess: () => closeDeleteModal(),
        });
    }
};

// Utils
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
</script>
