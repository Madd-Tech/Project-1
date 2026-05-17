<template>
  <div class="min-h-screen bg-dark-900 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
      <div class="bg-gradient-to-br from-electric/20 via-dark-900 to-neon/20 p-8 lg:p-10">
        <a href="/" class="inline-flex items-center gap-3 mb-6">
          <img :src="'/assets/logo.png'" class="w-10 h-10 object-contain" alt="logo">
          <span class="text-xl font-bold font-[Outfit] tracking-tight text-white">BubuihanStore</span>
        </a>
        <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">Login untuk Checkout</h1>
        <p class="text-gray-300 text-sm leading-relaxed">
          Buat akun pelanggan atau login untuk melanjutkan checkout. Data akun akan otomatis terisi di halaman checkout.
        </p>
      </div>

      <div class="bg-dark-800 p-6 lg:p-8">
        <div class="flex rounded-xl bg-dark-900 p-1 border border-white/10 mb-6">
          <button type="button" @click="mode = 'login'" :class="tabClass('login')" class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all">Login</button>
          <button type="button" @click="mode = 'register'" :class="tabClass('register')" class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all">Register</button>
        </div>

        <form v-if="mode === 'login'" @submit.prevent="submitLogin" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-300 mb-1.5">Email</label>
            <input v-model="loginForm.email" type="email" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="nama@email.com">
            <p v-if="loginForm.errors.email" class="text-red-400 text-xs mt-1">{{ loginForm.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1.5">Password</label>
            <div class="relative">
              <input v-model="loginForm.password" :type="showLoginPassword ? 'text' : 'password'" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 pr-11 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="******">
              <button type="button" @click="showLoginPassword = !showLoginPassword" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-white">
                <svg v-if="showLoginPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7 1.06-2.38 2.84-4.34 5-5.5m3.13-1.27A9.97 9.97 0 0112 5c5 0 9.27 3.11 11 7a11.98 11.98 0 01-4.06 4.9M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 9L3 3"/></svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </div>
            <p v-if="loginForm.errors.password" class="text-red-400 text-xs mt-1">{{ loginForm.errors.password }}</p>
          </div>
          <label class="inline-flex items-center gap-2 text-sm text-gray-300">
            <input v-model="loginForm.remember" type="checkbox" class="rounded border-white/20 bg-dark-900">
            Ingat saya
          </label>
          <button :disabled="loginForm.processing" class="w-full py-3 rounded-xl bg-gradient-to-r from-electric to-neon text-white font-semibold disabled:opacity-60">
            {{ loginForm.processing ? 'Memproses...' : 'Login' }}
          </button>
          <p class="text-sm text-gray-400 text-center">
            Belum punya akun?
            <button
              type="button"
              @click="mode = 'register'"
              class="text-electric hover:text-neon font-semibold ml-1"
            >
              Daftar di sini
            </button>
          </p>
        </form>

        <form v-else @submit.prevent="submitRegister" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-300 mb-1.5">Nama</label>
            <input v-model="registerForm.name" type="text" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="Nama lengkap">
            <p v-if="registerForm.errors.name" class="text-red-400 text-xs mt-1">{{ registerForm.errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1.5">Email</label>
            <input v-model="registerForm.email" type="email" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="nama@email.com">
            <p v-if="registerForm.errors.email" class="text-red-400 text-xs mt-1">{{ registerForm.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1.5">Nomor Telepon</label>
            <input v-model="registerForm.phone" type="text" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="08xxxxxxxxxx">
            <p v-if="registerForm.errors.phone" class="text-red-400 text-xs mt-1">{{ registerForm.errors.phone }}</p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-300 mb-1.5">Password</label>
              <div class="relative">
                <input v-model="registerForm.password" :type="showRegisterPassword ? 'text' : 'password'" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 pr-11 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="******">
                <button type="button" @click="showRegisterPassword = !showRegisterPassword" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-white">
                  <svg v-if="showRegisterPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7 1.06-2.38 2.84-4.34 5-5.5m3.13-1.27A9.97 9.97 0 0112 5c5 0 9.27 3.11 11 7a11.98 11.98 0 01-4.06 4.9M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 9L3 3"/></svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
              <p v-if="registerForm.errors.password" class="text-red-400 text-xs mt-1">{{ registerForm.errors.password }}</p>
            </div>
            <div>
              <label class="block text-sm text-gray-300 mb-1.5">Konfirmasi</label>
              <div class="relative">
                <input v-model="registerForm.password_confirmation" :type="showRegisterConfirmation ? 'text' : 'password'" class="w-full bg-dark-900 border border-white/10 rounded-xl px-4 py-3 pr-11 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-electric/40" placeholder="******">
                <button type="button" @click="showRegisterConfirmation = !showRegisterConfirmation" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-white">
                  <svg v-if="showRegisterConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7 1.06-2.38 2.84-4.34 5-5.5m3.13-1.27A9.97 9.97 0 0112 5c5 0 9.27 3.11 11 7a11.98 11.98 0 01-4.06 4.9M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 9L3 3"/></svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
            </div>
          </div>
          <button :disabled="registerForm.processing" class="w-full py-3 rounded-xl bg-gradient-to-r from-electric to-neon text-white font-semibold disabled:opacity-60">
            {{ registerForm.processing ? 'Memproses...' : 'Buat Akun & Checkout' }}
          </button>
          <p class="text-sm text-gray-400 text-center">
            Sudah punya akun?
            <button
              type="button"
              @click="mode = 'login'"
              class="text-electric hover:text-neon font-semibold ml-1"
            >
              Login di sini
            </button>
          </p>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  redirect: {
    type: String,
    default: '/',
  },
});

const mode = ref('login');
const showLoginPassword = ref(false);
const showRegisterPassword = ref(false);
const showRegisterConfirmation = ref(false);

const loginForm = useForm({
  email: '',
  password: '',
  remember: false,
  redirect: props.redirect,
});

const registerForm = useForm({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: '',
  remember: true,
  redirect: props.redirect,
});

const submitLogin = () => {
  loginForm.post('/customer/login', {
    onFinish: () => loginForm.reset('password'),
  });
};

const submitRegister = () => {
  registerForm.post('/customer/register', {
    onFinish: () => registerForm.reset('password', 'password_confirmation'),
  });
};

const tabClass = (tab) => (
  mode.value === tab
    ? 'bg-gradient-to-r from-electric to-neon text-white'
    : 'text-gray-400 hover:text-white'
);
</script>
