<template>
    <AdminLayout :admin="admin">
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Stock Activity Log</h2>
                    <p class="text-gray-400 text-sm">Monitor all stock inflows and outflows across your inventory.</p>
                </div>
                <button @click="openRecordModal()" class="px-5 py-2.5 bg-electric hover:bg-electric/90 text-white rounded-xl font-semibold transition-all shadow-lg shadow-electric/20 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Record Movement
                </button>
            </div>

            <!-- Flash Success Message -->
            <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Movements History Table -->
            <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-dark-800/50 border-b border-dark-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium">Date & Time</th>
                                <th scope="col" class="px-6 py-4 font-medium">Product</th>
                                <th scope="col" class="px-6 py-4 font-medium">Type</th>
                                <th scope="col" class="px-6 py-4 font-medium">Quantity</th>
                                <th scope="col" class="px-6 py-4 font-medium">Reference</th>
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
                                        <div class="font-medium text-gray-200">{{ move.product?.name || 'Deleted Product' }}</div>
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
                                    <span class="text-gray-400 italic text-xs">{{ move.reference || '-' }}</span>
                                </td>
                            </tr>
                            <tr v-if="movements.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>No stock movements recorded yet.</p>
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

        <!-- Record Movement Modal -->
        <Modal 
            :is-open="isModalOpen" 
            @close="closeModal" 
            title="Record Stock Movement" 
            max-width="sm"
        >
            <template #icon>
                <div class="w-8 h-8 rounded-lg bg-electric/10 border border-electric/20 flex items-center justify-center hidden sm:flex">
                    <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </template>
            
            <form id="stockForm" @submit.prevent="submitForm" class="space-y-5">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Select Product</label>
                        <select v-model="form.product_id" class="w-full bg-dark-900 border border-dark-600 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-electric focus:border-transparent transition-all">
                            <option value="" disabled>Choose a product...</option>
                            <option v-for="p in allProducts" :key="p.id" :value="p.id">
                                {{ p.name }} (Current: {{ p.stock }})
                            </option>
                        </select>
                        <div v-if="form.errors.product_id" class="mt-1 text-xs text-red-500">{{ form.errors.product_id }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Movement Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" 
                                @click="form.type = 'produk masuk'"
                                :class="['px-4 py-2.5 rounded-xl border text-sm font-medium transition-all flex items-center justify-center gap-2', form.type === 'produk masuk' ? 'bg-green-500/10 border-green-500/50 text-green-400' : 'bg-dark-900 border-dark-600 text-gray-400 hover:border-dark-500']"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                In (Masuk)
                            </button>
                            <button type="button" 
                                @click="form.type = 'produk keluar'"
                                :class="['px-4 py-2.5 rounded-xl border text-sm font-medium transition-all flex items-center justify-center gap-2', form.type === 'produk keluar' ? 'bg-red-500/10 border-red-500/50 text-red-400' : 'bg-dark-900 border-dark-600 text-gray-400 hover:border-dark-500']"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                Out (Keluar)
                            </button>
                        </div>
                        <div v-if="form.errors.type" class="mt-1 text-xs text-red-500">{{ form.errors.type }}</div>
                    </div>

                    <TextInput 
                        id="quantity"
                        type="number"
                        label="Quantity"
                        v-model="form.quantity"
                        placeholder="1"
                        :error="form.errors.quantity"
                        min="1"
                        required
                    />

                    <TextInput 
                        id="reference"
                        type="text"
                        label="Reference (Optional)"
                        v-model="form.reference"
                        placeholder="e.g. Sales, Purchase, Damage"
                        :error="form.errors.reference"
                    />
                </div>
            </form>

            <template #footer>
                <button type="button" @click="closeModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button type="submit" form="stockForm" :disabled="form.processing" class="px-5 py-2.5 bg-electric hover:bg-electric/88 disabled:opacity-50 text-white rounded-xl font-medium transition-all shadow-lg shadow-electric/20 flex items-center gap-2">
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Recording...' : 'Record Movement' }}
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
    movements: {
        type: Object, // Laravel Paginated Object
        required: true,
    },
    allProducts: {
        type: Array,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
});

const isModalOpen = ref(false);

const form = useForm({
    product_id: '',
    type: 'produk masuk',
    quantity: 1,
    reference: '',
});

const openRecordModal = () => {
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const submitForm = () => {
    form.post('/admin/stockmov', {
        onSuccess: () => closeModal(),
        preserveScroll: true,
    });
};
</script>
