<template>
  <nav
    :class="[
      'fixed top-0 left-0 right-0 z-50 transition-all duration-500',
      scrolled ? 'glass shadow-lg shadow-black/20 py-3' : 'bg-transparent py-5'
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 group" id="nav-logo">
          <img :src="'/assets/logo.png'" class="w-10 h-10 object-contain" alt="logo">
          <span class="text-xl font-bold font-[Outfit] tracking-tight">
            <span class="text-white">Bubuihan</span><span class="text-electric">Store</span>
          </span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-1" id="nav-desktop-menu">
          <a
            v-for="item in navItems"
            :key="item.label"
            :href="item.href"
            class="relative px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors duration-300 group"
          >
            {{ item.label }}
            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-electric to-neon group-hover:w-3/4 transition-all duration-300 rounded-full"></span>
          </a>
        </div>

        <!-- Right Section -->
        <div class="hidden md:flex items-center gap-4" id="nav-actions">
          <!-- Search -->
          <div class="relative flex items-center">
            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="opacity-0 w-0"
              enter-to-class="opacity-100 w-48"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100 w-48"
              leave-to-class="opacity-0 w-0"
            >
              <form v-if="isSearchOpen" @submit.prevent="performSearch" class="flex items-center" id="nav-search-form">
                <input
                  ref="searchInputRef"
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search products..."
                  @keydown.esc="closeSearch"
                  class="w-48 bg-white/5 border border-white/10 rounded-full py-1.5 pl-3 pr-8 text-sm text-white placeholder-gray-500 outline-none"
                  id="nav-search-input"
                >
                <button type="submit" class="absolute right-2 p-1 text-gray-400 hover:text-white transition-colors" id="nav-search-btn">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </button>
              </form>
            </transition>
            <button
              v-if="!isSearchOpen"
              @click="openSearch"
              class="p-2 text-gray-400 hover:text-white transition-colors"
              id="nav-search-toggle"
              aria-label="Open search"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>

          <!-- Cart -->
          <button
            @click="toggleDrawer"
            class="relative p-2 text-gray-400 hover:text-white transition-colors duration-300"
            id="nav-cart-btn"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="scale-0"
              enter-to-class="scale-100"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="scale-100"
              leave-to-class="scale-0"
            >
              <span
                v-if="totalItems > 0"
                class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-gradient-to-r from-electric to-neon text-[10px] font-bold text-white rounded-full flex items-center justify-center shadow-lg shadow-electric/30"
              >
                {{ totalItems > 99 ? '99+' : totalItems }}
              </span>
            </transition>
          </button>

          <!-- CTA Button -->
          <a
            href="/products"
            class="px-5 py-2.5 bg-gradient-to-r from-electric to-neon text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 transform hover:scale-105"
            id="nav-cta-btn"
          >
            Belanja Sekarang
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center gap-2">
          <!-- Mobile Cart -->
          <button
            @click="toggleDrawer"
            class="relative p-2 text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span
              v-if="totalItems > 0"
              class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-gradient-to-r from-electric to-neon text-[10px] font-bold text-white rounded-full flex items-center justify-center"
            >
              {{ totalItems > 9 ? '9+' : totalItems }}
            </span>
          </button>

          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-2 text-gray-400 hover:text-white transition-colors"
            id="nav-mobile-toggle"
          >
            <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div v-if="mobileMenuOpen" class="md:hidden mt-4 glass rounded-2xl p-4" id="nav-mobile-menu">
          <a
            v-for="item in navItems"
            :key="item.label"
            :href="item.href"
            class="block px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-xl transition-all duration-300"
            @click="mobileMenuOpen = false"
          >
            {{ item.label }}
          </a>
          <div class="mt-3 pt-3 border-t border-white/10 flex flex-col gap-3 px-4">
            <!-- Mobile Search Form -->
            <form @submit.prevent="performMobileSearch" class="flex items-center gap-2 w-full" id="nav-mobile-search-form">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search products..."
                class="flex-1 bg-white/5 border border-white/10 rounded-xl py-2 px-4 text-sm text-white placeholder-gray-500 outline-none focus:border-white/20"
                id="nav-mobile-search-input"
              >
              <button
                type="submit"
                class="p-2 bg-gradient-to-r from-electric to-neon rounded-xl text-white hover:opacity-90 transition-opacity flex-shrink-0"
                id="nav-mobile-search-btn"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </form>
            <div class="flex items-center gap-3">
            <a
              href="/products"
              class="flex-1 text-center px-4 py-2.5 bg-gradient-to-r from-electric to-neon text-white text-sm font-semibold rounded-xl"
              @click="mobileMenuOpen = false"
            >
              Shop Now
            </a>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </nav>

  <!-- Cart Drawer -->
  <CartDrawer />
</template>

<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { useCart } from '../../Composables/useCart';
import CartDrawer from './CartDrawer.vue';

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({ search: '' })
  }
});

const searchQuery = ref('');
const isSearchOpen = ref(false);
const searchInputRef = ref(null);

const openSearch = async () => {
  isSearchOpen.value = true;
  await nextTick();
  searchInputRef.value?.focus();
};

const closeSearch = () => {
  if (!searchQuery.value) {
    isSearchOpen.value = false;
  }
};

const performSearch = () => {
  const q = searchQuery.value.trim();
  if (!q) return;

  const isHome = window.location.pathname === '/';
  if (isHome) {
    // Di halaman Home: filter via Inertia lalu scroll ke #productsection
    router.get('/', { search: q }, {
      preserveState: true,
      replace: true,
      preserveScroll: false,
      onFinish: () => {
        searchQuery.value = '';
        isSearchOpen.value = false;
        const el = document.getElementById('productsection');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  } else {
    // Di halaman lain: arahkan ke /products dengan query search
    router.get('/products', { search: q }, { 
      preserveState: false,
      onFinish: () => {
        searchQuery.value = '';
        isSearchOpen.value = false;
      }
    });
  }
};

const performMobileSearch = () => {
  const q = searchQuery.value.trim();
  mobileMenuOpen.value = false;
  if (!q) return;

  const isHome = window.location.pathname === '/';
  if (isHome) {
    router.get('/', { search: q }, {
      preserveState: true,
      replace: true,
      preserveScroll: false,
      onFinish: () => {
        searchQuery.value = '';
        const el = document.getElementById('productsection');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  } else {
    router.get('/products', { search: q }, { 
      preserveState: false,
      onFinish: () => {
        searchQuery.value = '';
      }
    });
  }
};

const { totalItems, toggleDrawer } = useCart();

const scrolled = ref(false);
const mobileMenuOpen = ref(false);

const navItems = [
  { label: 'Home', href: '/' },
  { label: 'Products', href: '/#productsection' },
  { label: 'Categories', href: '/#categories' },
  // { label: 'Promo', href: '/#promo' },
  { label: 'About', href: '/#about' },
  { label: 'FAQ', href: '/faq' },
];

const handleScroll = () => {
  scrolled.value = window.scrollY > 50;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>