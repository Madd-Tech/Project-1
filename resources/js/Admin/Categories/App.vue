<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Kelola Kategori Produk</h2>
                    <p class="text-gray-400 text-sm">Tambah, Update, atau Hapus Kategori Produk</p>
                </div>
                <button @click="openCreateModal" class="px-5 py-2.5 bg-electric hover:bg-electric/80 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Category
                </button>
            </div>

            <!-- Flash Success Message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Categories Table/List -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">ID</th>
                                <th scope="col" class="px-6 py-4 font-medium">Nama Kategori</th>
                                <th scope="col" class="px-6 py-4 font-medium">Slug</th>
                                <th scope="col" class="px-6 py-4 font-medium">Dibuat Pada</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in categories" :key="category.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">#{{ category.id }}</td>
                                <td class="px-6 py-4">{{ category.name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[10px] font-medium bg-dark-800 text-gray-300 rounded-md border border-dark-600">
                                        {{ category.slug }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ new Date(category.created_at).toLocaleDateString() }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal(category)" class="text-gray-400 hover:text-electric transition-colors p-1" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="confirmDelete(category)" class="text-gray-400 hover:text-red-400 transition-colors p-1" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="categories.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p>Belum ada kategori, buat kategori baru!</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Modal (Create/Edit) -->
        <Modal 
            :is-open="isModalOpen" 
            @close="closeModal" 
            :title="isEditing ? 'Edit Category' : 'Create Category'" 
            max-width="md"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </template>
            
            <form id="categoryForm" @submit.prevent="submitForm">
                <TextInput 
                    id="name"
                    label="Nama Kategori"
                    v-model="form.name"
                    placeholder="e.g. Sports, Fashion"
                    :error="form.errors.name"
                    required
                />
            </form>

            <template #footer>
                <button type="button" @click="closeModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button type="submit" form="categoryForm" :disabled="form.processing" class="px-5 py-2.5 bg-electric hover:bg-electric/80 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20">
                    {{ form.processing ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                </button>
            </template>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal 
            :is-open="isDeleteModalOpen" 
            @close="closeDeleteModal" 
            title="Delete Category" 
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
                <p class="text-gray-300">Are you sure you want to delete <span class="text-white font-medium">"{{ categoryToDelete?.name }}"</span>? This action cannot be undone.</p>
            </div>

            <template #footer>
                <button @click="closeDeleteModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button @click="deleteCategory" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/20">
                    {{ deleteForm.processing ? 'Deleting...' : 'Delete' }}
                </button>
            </template>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import Modal from '../Components/Modal.vue';
import TextInput from '../Components/TextInput.vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
});

// Create / Edit Logic
const isModalOpen = ref(false);
const isEditing = ref(false);
const currentCategoryId = ref(null);

const form = useForm({
    name: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    currentCategoryId.value = null;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (category) => {
    isEditing.value = true;
    currentCategoryId.value = category.id;
    form.name = category.name;
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/admin/categories/${currentCategoryId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/categories', {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete Logic
const isDeleteModalOpen = ref(false);
const categoryToDelete = ref(null);

const deleteForm = useForm({});

const confirmDelete = (category) => {
    categoryToDelete.value = category;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    categoryToDelete.value = null;
};

const deleteCategory = () => {
    if (categoryToDelete.value) {
        deleteForm.delete(`/admin/categories/${categoryToDelete.value.id}`, {
            onSuccess: () => closeDeleteModal(),
        });
    }
};
</script>
