<template>
  <section id="products" class="py-20 sm:py-28 relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-electric/5 rounded-full blur-[150px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-neon bg-neon/10 rounded-full mb-4">Trending Now</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-bold text-white mb-4">
          Featured <span class="gradient-text">Collection</span>
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">
          Koleksi sepatu terpopuler pilihan kami. Setiap pasang dirancang untuk kenyamanan dan gaya terbaik.
        </p>
      </div>

      <!-- Filter Tabs -->
      <div class="flex flex-wrap justify-center gap-3 mb-12" id="product-filters">
        <button
          v-for="cat in filterCategories"
          :key="cat"
          @click="activeFilter = cat"
          :class="[
            'px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-300',
            activeFilter === cat
              ? 'bg-gradient-to-r from-electric to-neon text-white shadow-lg shadow-electric/25'
              : 'glass text-gray-400 hover:text-white hover:bg-white/5'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Product Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="product-grid">
        <div
          v-for="(product, index) in products"
          :key="product.id"
          class="glass-card rounded-3xl overflow-hidden group"
          :style="{ animationDelay: `${index * 0.1}s` }"
        >
          <!-- Image Container -->
          <div class="relative p-6 pb-0 overflow-hidden">
            <div class="absolute top-4 left-4 z-10">
              <span
                v-if="product.badge"
                :class="[
                  'px-3 py-1 text-xs font-bold rounded-lg',
                  product.badge === 'NEW' ? 'bg-neon/20 text-neon' :
                  product.badge === 'HOT' ? 'bg-red-500/20 text-red-400' :
                  'bg-amber/20 text-amber'
                ]"
              >
                {{ product.badge }}
              </span>
            </div>
            <button class="absolute top-4 right-4 z-10 p-2 glass rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white/10">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
            <img
              :src="product.image"
              :alt="product.name"
              class="w-full h-48 object-contain transform group-hover:scale-110 group-hover:rotate-[-5deg] transition-all duration-500"
            >
          </div>

          <!-- Info -->
          <div class="p-6 pt-4">
            <span class="text-xs text-gray-500 uppercase tracking-wider">{{ product.category }}</span>
            <h3 class="text-white font-semibold text-lg mt-1 mb-2 group-hover:text-electric-light transition-colors">{{ product.name }}</h3>
            <div class="flex items-center gap-1 mb-3">
              <svg v-for="star in 5" :key="star" class="w-3.5 h-3.5" :class="star <= product.rating ? 'text-amber' : 'text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span class="text-xs text-gray-500 ml-1">({{ product.reviews }})</span>
            </div>
            <div class="flex items-center justify-between">
              <div>
                <span v-if="product.oldPrice" class="text-sm text-gray-500 line-through mr-2">Rp {{ product.oldPrice }}</span>
                <span class="text-lg font-bold text-white">Rp {{ product.price }}</span>
              </div>
              <button class="p-3 bg-gradient-to-r from-electric to-neon rounded-xl text-white hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 transform hover:scale-110 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- View All Button -->
      <div class="text-center mt-12">
        <a
          href="#"
          class="inline-flex items-center gap-2 px-8 py-4 glass text-white font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300 group"
          id="view-all-products-btn"
        >
          Lihat Semua Produk
          <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const activeFilter = ref('All');
const filterCategories = ['All', 'Running', 'Lifestyle', 'Basketball', 'Retro'];

const products = [
  {
    id: 1,
    name: 'AeroSpeed Runner X',
    category: 'Running',
    price: '1.299.000',
    oldPrice: '1.899.000',
    rating: 5,
    reviews: 128,
    badge: 'HOT',
    image: '/images/products/shoe-1.png',
  },
  {
    id: 2,
    name: 'UrbanFlex Pro',
    category: 'Lifestyle',
    price: '999.000',
    rating: 4,
    reviews: 89,
    badge: 'NEW',
    image: '/images/products/shoe-2.png',
  },
  {
    id: 3,
    name: 'SkyDunk Elite',
    category: 'Basketball',
    price: '1.599.000',
    oldPrice: '2.199.000',
    rating: 5,
    reviews: 64,
    badge: 'SALE',
    image: '/images/products/shoe-3.png',
  },
  {
    id: 4,
    name: 'RetroWave Classic',
    category: 'Retro',
    price: '849.000',
    rating: 4,
    reviews: 156,
    badge: 'NEW',
    image: '/images/products/shoe-4.png',
  },
];
</script>
