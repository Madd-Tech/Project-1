<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Manage Products</h2>
                    <p class="text-gray-400 text-sm">Create, update, or delete products.</p>
                </div>
                <button @click="openCreateModal" class="px-5 py-2.5 bg-electric hover:bg-electric/80 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Product
                </button>
            </div>

            <!-- Flash Success Message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Products Table -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Image</th>
                                <th scope="col" class="px-6 py-4 font-medium">Name</th>
                                <th scope="col" class="px-6 py-4 font-medium">Category</th>
                                <th scope="col" class="px-6 py-4 font-medium">Price</th>
                                <th scope="col" class="px-6 py-4 font-medium">Status</th>
                                <th scope="col" class="px-6 py-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products.data" :key="product.id" class="border-b border-dark-600/30 hover:bg-dark-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="w-12 h-12 rounded-lg bg-dark-800 border border-dark-600 overflow-hidden flex items-center justify-center shrink-0">
                                        <img v-if="product.image" :src="`/storage/${product.image}`" class="w-full h-full object-cover">
                                        <svg v-else class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-white mb-0.5">{{ product.name }}</div>
                                    <div class="text-[10px] text-gray-500 max-w-xs truncate">{{ product.slug }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-medium bg-dark-800 text-gray-300 rounded-md border border-dark-600">
                                        {{ product.category?.name || 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-electric">Rp. {{ product.price }}</td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border', product.status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20']">
                                        {{ product.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal(product)" class="text-gray-400 hover:text-electric transition-colors p-1" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="confirmDelete(product)" class="text-gray-400 hover:text-red-400 transition-colors p-1" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="products.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p>No products found. Create your first product!</p>
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

        <!-- Product Modal (Create/Edit) -->
        <Modal 
            :is-open="isModalOpen" 
            @close="closeModal" 
            :title="isEditing ? 'Edit Product' : 'Create Product'" 
            max-width="lg"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </template>
            
            <form id="productForm" @submit.prevent="submitForm" class="space-y-5">
                <!-- Image Upload (Custom Drag/Drop UI) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Image <span v-if="!isEditing" class="text-electric">*</span></label>
                    <div class="relative group mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dark-600 border-dashed rounded-2xl hover:border-electric transition-colors bg-dark-800 overflow-hidden" @click="$refs.fileInput.click()">
                        <div v-if="imagePreview" class="absolute inset-0 bg-dark-900 border border-dark-600">
                             <img :src="imagePreview" class="w-full h-full object-contain">
                             <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                 <span class="text-white text-sm font-medium">Change Image</span>
                             </div>
                        </div>
                        <div v-else class="space-y-1 text-center cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-electric transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex justify-center text-sm text-gray-400 mt-2">
                                <span class="relative cursor-pointer rounded-md font-medium text-electric hover:text-electric/80 outline-none">
                                    <span>Upload a file</span>
                                </span>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                        <input id="file-upload" type="file" ref="fileInput" class="hidden" accept="image/*" @change="handleImageUpload">
                    </div>
                    <p v-if="form.errors.image" class="text-red-400 text-xs mt-2">{{ form.errors.image }}</p>
                </div>

                <TextInput 
                    id="name"
                    label="Product Name"
                    v-model="form.name"
                    placeholder="e.g. Wireless Headphones"
                    :error="form.errors.name"
                    required
                />
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                        <select v-model="form.category_id" class="w-full bg-dark-900 border border-dark-600 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all shadow-inner appearance-none" required>
                            <option value="" disabled>Select a category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                         <p v-if="form.errors.category_id" class="text-red-400 text-xs mt-2">{{ form.errors.category_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                        <select v-model="form.status" class="w-full bg-dark-900 border border-dark-600 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all shadow-inner appearance-none" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                         <p v-if="form.errors.status" class="text-red-400 text-xs mt-2">{{ form.errors.status }}</p>
                    </div>
                </div>

                <div>
                    <TextInput 
                        id="price"
                        type="number"
                        step="0.01"
                        label="Harga (Rp)"
                        v-model="form.price"
                        placeholder="0"
                        :error="form.errors.price"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea 
                        v-model="form.description" 
                        rows="4" 
                        class="w-full bg-dark-900 border border-dark-600 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric transition-all shadow-inner resize-none placeholder-gray-500"
                        placeholder="Deskripsi produk..."
                        required
                    ></textarea>
                     <p v-if="form.errors.description" class="text-red-400 text-xs mt-2">{{ form.errors.description }}</p>
                </div>
            </form>

            <template #footer>
                <button type="button" @click="closeModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button type="submit" form="productForm" :disabled="form.processing" class="px-5 py-2.5 bg-electric hover:bg-electric/80 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20">
                    {{ form.processing ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                </button>
            </template>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal 
            :is-open="isDeleteModalOpen" 
            @close="closeDeleteModal" 
            title="Delete Product" 
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
                <p class="text-gray-300">Are you sure you want to delete <span class="text-white font-medium">"{{ productToDelete?.name }}"</span>? This action cannot be undone.</p>
            </div>

            <template #footer>
                <button @click="closeDeleteModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button @click="deleteProduct" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/20">
                    {{ deleteForm.processing ? 'Deleting...' : 'Delete' }}
                </button>
            </template>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '../Components/AdminLayout.vue';
import Modal from '../Components/Modal.vue';
import TextInput from '../Components/TextInput.vue';

const props = defineProps({
    products: {
        type: Object, // Laravel Paginated Object
        required: true,
    },
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
const currentProductId = ref(null);
const fileInput = ref(null);
const imagePreview = ref(null);

const form = useForm({
    name: '',
    category_id: '',
    status: 'active',
    price: '',
    description: '',
    image: null,
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        
        // Setup image preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const openCreateModal = () => {
    isEditing.value = false;
    currentProductId.value = null;
    form.reset();
    form.clearErrors();
    
    // Clear image
    imagePreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
    
    isModalOpen.value = true;
};

const openEditModal = (product) => {
    isEditing.value = true;
    currentProductId.value = product.id;
    form.name = product.name;
    form.category_id = product.category_id;
    form.status = product.status;
    form.price = product.price;
    form.description = product.description;
    form.image = null; // Don't require image on edit unless changing
    form.clearErrors();
    
    // Show existing image
    if (product.image) {
        imagePreview.value = `/storage/${product.image}`;
    } else {
        imagePreview.value = null;
    }
    
    if (fileInput.value) fileInput.value.value = '';
    
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const submitForm = () => {
    if (isEditing.value) {
        // Inertia specific standard for sending files with PUT requests
        form.post(`/admin/products/${currentProductId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/products', {
            onSuccess: () => closeModal(),
        });
    }
};

// Delete Logic
const isDeleteModalOpen = ref(false);
const productToDelete = ref(null);

const deleteForm = useForm({});

const confirmDelete = (product) => {
    productToDelete.value = product;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    productToDelete.value = null;
};

const deleteProduct = () => {
    if (productToDelete.value) {
        deleteForm.delete(`/admin/products/${productToDelete.value.id}`, {
            onSuccess: () => closeDeleteModal(),
        });
    }
};
</script>
