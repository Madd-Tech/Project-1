<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 font-sans text-white">
    <div class="relative w-full max-w-md p-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl">
      
      <div class="absolute -top-12 -left-12 w-32 h-32 bg-indigo-500/30 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-purple-500/30 rounded-full blur-3xl"></div>

      <h1 class="text-3xl font-bold text-center mb-2 tracking-tight">Welcome Back</h1>
      <p class="text-center text-gray-400 mb-8 text-sm">Sign in to access your dashboard</p>

      <form @submit.prevent="handleLogin" class="space-y-6 relative z-10">
        
        <!-- Email Input -->
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-300 ml-1">Email Address</label>
          <input v-model="form.email"
                 type="email"
                 placeholder="name@example.com"
                 :class="{'border-red-500/50 focus:ring-red-500/50': form.errors.email || frontErrors.email}"
                 class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200" />
          <p v-if="frontErrors.email" class="text-red-400 text-xs ml-1">{{ frontErrors.email }}</p>
          <p v-if="form.errors.email" class="text-red-400 text-xs ml-1">{{ form.errors.email }}</p>
        </div>

        <!-- Password Input -->
        <div class="space-y-2">
          <label class="text-sm font-medium text-gray-300 ml-1">Password</label>
          <input v-model="form.password"
                 type="password"
                 placeholder="••••••••"
                 :class="{'border-red-500/50 focus:ring-red-500/50': form.errors.password || frontErrors.password}"
                 class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-transparent transition-all duration-200" />
          <p v-if="frontErrors.password" class="text-red-400 text-xs ml-1">{{ frontErrors.password }}</p>
          <p v-if="form.errors.password" class="text-red-400 text-xs ml-1">{{ form.errors.password }}</p>
        </div>

        <!-- Global Message -->
        <div v-if="$page.props.flash?.error" class="p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-200 text-sm text-center">
          {{ $page.props.flash.error }}
        </div>

        <!-- Login Button -->
        <button type="submit" 
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold py-3 rounded-lg shadow-lg shadow-indigo-500/20 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center">
          <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span v-if="!form.processing">Sign In</span>
          <span v-else>Signing in...</span>
        </button>

      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  email: '',
  password: ''
});

const showPassword = ref(false);
const frontErrors = ref({});

const validate = () => {
  frontErrors.value = {};
  let isValid = true;
  
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

  if (!form.email) {
    frontErrors.value.email = 'Email address is required';
    isValid = false;
  } else if (!emailPattern.test(form.email)) {
    frontErrors.value.email = 'Please enter a valid email address';
    isValid = false;
  }

  if (!form.password) {
    frontErrors.value.password = 'Password is required';
    isValid = false;
  }

  return isValid;
};

const handleLogin = () => {
  if (!validate()) return;
  
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>
