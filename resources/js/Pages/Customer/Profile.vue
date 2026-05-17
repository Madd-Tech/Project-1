<template>
  <div class="min-h-screen bg-dark-900 pt-24 pb-12">
    <NavBar />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
      <div>
        <h1 class="text-3xl font-bold text-white">Edit Profile</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola nama, password, dan akun Anda.</p>
      </div>

      <div v-if="$page.props.flash?.success" class="px-4 py-3 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl text-sm">
        {{ $page.props.flash.success }}
      </div>

      <div class="glass-card rounded-2xl p-6">
        <h2 class="text-white font-semibold mb-4">Ganti Nama</h2>
        <form @submit.prevent="submitName" class="space-y-4">
          <input v-model="nameForm.name" type="text" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Nama baru">
          <p v-if="nameForm.errors.name" class="text-red-400 text-xs">{{ nameForm.errors.name }}</p>
          <button :disabled="nameForm.processing" class="px-5 py-2.5 bg-gradient-to-r from-electric to-neon text-white rounded-xl text-sm font-semibold disabled:opacity-50">
            Simpan Nama
          </button>
        </form>
      </div>

      <div class="glass-card rounded-2xl p-6">
        <h2 class="text-white font-semibold mb-4">Ganti Password</h2>
        <form @submit.prevent="submitPassword" class="space-y-4">
          <input v-model="passwordForm.current_password" type="password" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Password saat ini">
          <p v-if="passwordForm.errors.current_password" class="text-red-400 text-xs">{{ passwordForm.errors.current_password }}</p>

          <input v-model="passwordForm.password" type="password" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Password baru">
          <p v-if="passwordForm.errors.password" class="text-red-400 text-xs">{{ passwordForm.errors.password }}</p>

          <input v-model="passwordForm.password_confirmation" type="password" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Konfirmasi password baru">

          <button :disabled="passwordForm.processing" class="px-5 py-2.5 bg-gradient-to-r from-electric to-neon text-white rounded-xl text-sm font-semibold disabled:opacity-50">
            Simpan Password
          </button>
        </form>
      </div>

      <div class="glass-card rounded-2xl p-6 border border-red-500/30">
        <h2 class="text-white font-semibold mb-2">Logout</h2>
        <p class="text-gray-400 text-sm mb-4">Keluar dari akun customer saat ini.</p>
        <button @click="submitLogout" :disabled="logoutForm.processing" class="mb-6 px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl text-sm font-semibold disabled:opacity-50">
          {{ logoutForm.processing ? 'Memproses...' : 'Logout' }}
        </button>

        <h2 class="text-red-400 font-semibold mb-2">Hapus Akun</h2>
        <p class="text-gray-400 text-sm mb-4">Akun akan dihapus permanen.</p>
        <button @click="isDeleteModalOpen = true" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold">
          Hapus Akun
        </button>
      </div>

      <Modal :is-open="isDeleteModalOpen" @close="closeDeleteModal" title="Konfirmasi Hapus Akun" max-width="md">
        <div class="space-y-4">
          <p class="text-gray-300 text-sm">
            Tindakan ini permanen dan tidak dapat dibatalkan. Masukkan password untuk melanjutkan.
          </p>
          <input v-model="deleteForm.password" type="password" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Masukkan password">
          <p v-if="deleteForm.errors.delete_password" class="text-red-400 text-xs">{{ deleteForm.errors.delete_password }}</p>
          <p v-if="deleteForm.errors.password" class="text-red-400 text-xs">{{ deleteForm.errors.password }}</p>
        </div>
        <template #footer>
          <button @click="closeDeleteModal" class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-white rounded-xl text-sm font-semibold">
            Batal
          </button>
          <button @click="submitDelete" :disabled="deleteForm.processing" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold disabled:opacity-50">
            {{ deleteForm.processing ? 'Menghapus...' : 'Hapus Akun' }}
          </button>
        </template>
      </Modal>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import NavBar from '../Components/NavBar.vue';
import Modal from '../../Admin/Components/Modal.vue';

const props = defineProps({
  customer: {
    type: Object,
    required: true,
  },
});

const nameForm = useForm({
  name: props.customer.name || '',
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const deleteForm = useForm({
  password: '',
});
const logoutForm = useForm({});
const isDeleteModalOpen = ref(false);

const submitName = () => {
  nameForm.put('/customer/profile/name');
};

const submitPassword = () => {
  passwordForm.put('/customer/profile/password', {
    onFinish: () => passwordForm.reset('current_password', 'password', 'password_confirmation'),
  });
};

const submitDelete = () => {
  deleteForm.delete('/customer/profile', {
    onSuccess: () => {
      isDeleteModalOpen.value = false;
    },
  });
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
  deleteForm.clearErrors();
  deleteForm.reset('password');
};

const submitLogout = () => {
  logoutForm.post('/customer/logout');
};
</script>
