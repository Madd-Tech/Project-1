<template>
  <div class="min-h-screen bg-dark-900">
    <!-- Loading Overlay Popup -->
    <Teleport to="body">
      <Transition name="overlay-fade">
        <div v-if="isLoadingOverlay" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm">
          <div class="bg-dark-800 border border-white/10 rounded-2xl p-8 flex flex-col items-center gap-4 shadow-2xl shadow-electric/10 animate-scaleIn">
            <div class="relative w-14 h-14">
              <div class="absolute inset-0 rounded-full border-[3px] border-electric/20"></div>
              <div class="absolute inset-0 rounded-full border-[3px] border-transparent border-t-electric animate-spin"></div>
              <div class="absolute inset-2 rounded-full border-[2px] border-transparent border-b-neon animate-spin" style="animation-direction:reverse;animation-duration:0.8s"></div>
            </div>
            <p class="text-white font-medium text-sm">{{ loadingMessage }}</p>
            <p class="text-gray-500 text-xs">Mohon tunggu sebentar...</p>
          </div>
        </div>
      </Transition>
    </Teleport>
    <!-- Minimal Top Bar -->
    <div class="glass border-b border-white/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group">
          <img :src="'/assets/logo.png'" class="w-10 h-10 object-contain" alt="logo">
          <span class="text-xl font-bold font-[Outfit] tracking-tight">
            <span class="text-white">Bubuihan</span><span class="text-electric">Store</span>
          </span>
        </a>
        <div class="flex items-center gap-2 text-electric text-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
          <span>Checkout</span>
        </div>
      </div>
    </div>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="/" class="hover:text-electric transition-colors">Home</a>
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-300">Checkout</span>
      </nav>
    </div>

    <!-- Empty Cart -->
    <div v-if="cartItems.length === 0" class="max-w-lg mx-auto px-4 py-20 text-center">
      <div class="w-24 h-24 mx-auto rounded-3xl bg-dark-700 flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-white font-[Outfit] mb-2">Keranjang Kosong</h2>
      <p class="text-gray-400 mb-6">Anda belum menambahkan produk ke keranjang.</p>
      <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Lihat Produk
      </a>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <!-- Error message -->
      <div v-if="form.errors.order" class="mb-6 px-4 py-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3 animate-fade-in-up">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-sm font-medium">{{ form.errors.order }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT COLUMN -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Customer Data Form -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-electric/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Data Pemesan</h3>
            </div>

            <div class="space-y-5">
              <!-- Name -->
              <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-400 mb-2">Nama Lengkap</label>
                <input
                  id="customer_name"
                  v-model="form.customer_name"
                  type="text"
                  placeholder="Masukkan nama lengkap"
                  class="w-full bg-dark-800/60 border rounded-xl py-3 px-4 text-white placeholder-gray-600 outline-none transition-all duration-200"
                  :class="form.errors.customer_name ? 'border-red-500/50 focus:border-red-500' : 'border-white/10 focus:border-electric/50 focus:ring-2 focus:ring-electric/20'"
                >
                <p v-if="form.errors.customer_name" class="mt-1.5 text-xs text-red-400">{{ form.errors.customer_name }}</p>
              </div>

              <!-- Phone -->
              <div>
                <label for="customer_phone" class="block text-sm font-medium text-gray-400 mb-2">Nomor Telepon</label> <span class="text-xs text-gray-500 ml-1">isi nomor telepon yang terdaftar di WhatsApp</span>
                <input
                  id="customer_phone"
                  v-model="form.customer_phone"
                  type="tel"
                  placeholder="08xxxxxxxxxx"
                  class="w-full bg-dark-800/60 border rounded-xl py-3 px-4 text-white placeholder-gray-600 outline-none transition-all duration-200"
                  :class="form.errors.customer_phone ? 'border-red-500/50 focus:border-red-500' : 'border-white/10 focus:border-electric/50 focus:ring-2 focus:ring-electric/20'"
                >
                <p v-if="form.errors.customer_phone" class="mt-1.5 text-xs text-red-400">{{ form.errors.customer_phone }}</p>
              </div>

              <!-- Address -->
              <div>
                <label for="customer_address" class="block text-sm font-medium text-gray-400 mb-2">Alamat Lengkap</label>
                <div class="relative">
                  <textarea
                    id="customer_address"
                    v-model="form.customer_address"
                    rows="3"
                    placeholder="Masukkan alamat lengkap pengiriman"
                    class="w-full bg-dark-800/60 border rounded-xl py-3 px-4 text-white placeholder-gray-600 outline-none transition-all duration-200 resize-none mb-3"
                    :class="[form.errors.customer_address ? 'border-red-500/50 focus:border-red-500' : 'border-white/10 focus:border-electric/50 focus:ring-2 focus:ring-electric/20', isFetchingAddress ? 'opacity-50' : '']"
                    :disabled="isFetchingAddress"
                  ></textarea>
                  <div v-if="isFetchingAddress" class="absolute inset-0 flex items-center justify-center rounded-xl bg-dark-800/30">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-dark-900/90 rounded-lg border border-electric/20">
                      <svg class="w-4 h-4 text-electric animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                      <span class="text-xs text-electric font-medium">Mengambil alamat...</span>
                    </div>
                  </div>
                </div>
                <p v-if="form.errors.customer_address" class="mt-1.5 text-xs text-red-400 mb-3">{{ form.errors.customer_address }}</p>

                <!-- Map Section -->
                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-400">Pilih Lokasi di Peta</label>
                    <button type="button" @click="getCurrentLocation" :disabled="isLocating" class="text-xs flex items-center gap-1 text-electric hover:text-neon transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                      <svg v-if="!isLocating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                      <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                      {{ isLocating ? 'Mencari lokasi...' : 'Gunakan Lokasi Saat Ini' }}
                    </button>
                  </div>
                  <div id="map" class="w-full h-64 rounded-xl border border-white/10 z-0"></div>
                  <p class="text-[10px] text-gray-500">*Geser pin merah untuk menyesuaikan lokasi akurat pengiriman.</p>
                  <p v-if="form.errors.latitude || form.errors.longitude" class="text-xs text-red-400">Silakan pilih lokasi pada peta.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-neon/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Metode Pembayaran</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <button
                v-for="method in paymentMethods"
                :key="method.value"
                type="button"
                @click="form.payment_method = method.value"
                :class="[
                  'relative p-5 rounded-2xl border-2 transition-all duration-300 text-left group',
                  form.payment_method === method.value
                    ? 'border-electric bg-electric/5 shadow-lg shadow-electric/10'
                    : 'border-white/10 hover:border-white/20 hover:bg-white/2'
                ]"
              >
                <!-- Check icon -->
                <div v-if="form.payment_method === method.value" class="absolute top-3 right-3 w-6 h-6 rounded-full bg-electric flex items-center justify-center">
                  <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div :class="['w-10 h-10 rounded-xl flex items-center justify-center mb-3 transition-all duration-300', form.payment_method === method.value ? 'bg-electric/20' : 'bg-dark-700']">
                  <svg class="w-5 h-5" :class="form.payment_method === method.value ? 'text-electric' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="method.icon" />
                  </svg>
                </div>
                <p :class="['font-semibold text-sm mb-1 transition-colors', form.payment_method === method.value ? 'text-white' : 'text-gray-300']">{{ method.label }}</p>
                <p class="text-xs text-gray-500">{{ method.desc }}</p>
              </button>
            </div>
            <p v-if="form.errors.payment_method" class="mt-3 text-xs text-red-400">{{ form.errors.payment_method }}</p>
          </div>

          <!-- Order Items -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-electric/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Detail Pesanan</h3>
              <span class="ml-auto px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-electric/10 text-electric">{{ cartItems.length }} produk</span>
            </div>

            <!-- Table Header -->
            <div class="hidden sm:grid grid-cols-12 gap-4 text-xs text-gray-500 uppercase tracking-wider font-semibold pb-3 border-b border-white/5 mb-2">
              <div class="col-span-5">Produk</div>
              <div class="col-span-2 text-center">Qty</div>
              <div class="col-span-2 text-right">Harga</div>
              <div class="col-span-3 text-right">Total</div>
            </div>

            <!-- Product Rows -->
            <div
              v-for="(item, index) in cartItems"
              :key="item.id"
              class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center py-4"
              :class="index < cartItems.length - 1 ? 'border-b border-white/5' : ''"
            >
              <div class="sm:col-span-5 flex items-center gap-4">
                <div class="w-14 h-14 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
                  <img
                    :src="item.image ? '/storage/' + item.image : '/images/placeholder.png'"
                    :alt="item.name"
                    class="w-full h-full object-contain p-1"
                  />
                </div>
                <div class="min-w-0">
                  <p class="text-white font-medium text-sm truncate">{{ item.name }}</p>
                  <p class="text-gray-500 text-xs">{{ item.category }}</p>
                </div>
              </div>
              <div class="sm:col-span-2 flex items-center justify-center gap-1.5">
                <button type="button" @click="decrementQuantity(item.id)" class="w-7 h-7 flex items-center justify-center bg-dark-700 rounded-lg text-gray-400 hover:text-white hover:bg-dark-600 transition-all">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <span class="w-8 text-center text-white text-sm font-medium">{{ item.quantity }}</span>
                <button type="button" @click="incrementQuantity(item.id)" class="w-7 h-7 flex items-center justify-center bg-dark-700 rounded-lg text-gray-400 hover:text-white hover:bg-dark-600 transition-all">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
              </div>
              <div class="sm:col-span-2 text-right text-gray-300 text-sm">
                <span class="sm:hidden text-gray-500">Harga: </span>Rp {{ formatPrice(item.price) }}
              </div>
              <div class="sm:col-span-3 text-right flex items-center justify-end gap-2">
                <span class="text-white font-semibold text-sm">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                <button type="button" @click="removeFromCart(item.id)" class="p-1 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Notes -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-9 h-9 rounded-xl bg-amber/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Catatan <span class="text-gray-500 text-sm font-normal">(Opsional)</span></h3>
            </div>
            <textarea
              v-model="form.notes"
              rows="2"
              placeholder="Tambahkan catatan untuk pesanan Anda..."
              class="w-full bg-dark-800/60 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-gray-600 outline-none focus:border-electric/50 focus:ring-2 focus:ring-electric/20 transition-all duration-200 resize-none"
            ></textarea>
          </div>
        </div>

        <!-- RIGHT COLUMN: Summary -->
        <div class="lg:col-span-1">
          <div class="sticky top-6 space-y-6">
            <div class="glass-card rounded-3xl p-7">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white font-[Outfit]">Ringkasan</h3>
                <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-electric/10 text-electric">IDR</span>
              </div>

              <div class="text-center mb-6 py-5 bg-gradient-to-br from-electric/5 to-neon/5 rounded-2xl border border-white/5">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Total Pembayaran</p>
                <p class="text-3xl font-bold text-white font-[Outfit]">Rp {{ formatPrice(totalAmount) }}</p>
              </div>

              <!-- Items summary -->
              <div class="space-y-2 mb-4">
                <div v-for="item in cartItems" :key="'s-'+item.id" class="flex justify-between text-xs">
                  <span class="text-gray-500 truncate mr-2">{{ item.name }} x{{ item.quantity }}</span>
                  <span class="text-gray-400 flex-shrink-0">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                </div>
              </div>

              <div class="space-y-3 mb-6 border-t border-white/5 pt-4">
                <div class="flex justify-between text-sm font-semibold">
                  <span class="text-gray-300">Total</span>
                  <span class="text-white">Rp {{ formatPrice(totalAmount) }}</span>
                </div>
              </div>

              <!-- Selected Payment -->
              <div v-if="form.payment_method" class="mb-4 px-4 py-3 bg-dark-800/60 rounded-xl border border-white/5">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                <p class="text-sm text-white font-medium">{{ paymentMethods.find(m => m.value === form.payment_method)?.label }}</p>
              </div>

              <!-- Place Order Button -->
              <button
                @click="placeOrder"
                :disabled="form.processing || cartItems.length === 0"
                class="w-full py-4 rounded-2xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2 bg-gradient-to-r from-electric to-neon hover:shadow-lg hover:shadow-electric/30 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
              >
                <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                {{ form.processing ? 'Memproses...' : 'Buat Pesanan' }}
              </button>
            </div>

            <div class="glass-card rounded-2xl p-5">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-electric/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-white text-sm font-medium mb-1">Informasi Pesanan</p>
                  <p class="text-gray-500 text-xs leading-relaxed">Pesanan Anda akan menunggu konfirmasi dari admin. Anda akan mendapatkan info lebih lanjut setelah pesanan dikonfirmasi.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.overlay-fade-enter-active, .overlay-fade-leave-active { transition: opacity 0.25s ease; }
.overlay-fade-enter-from, .overlay-fade-leave-to { opacity: 0; }
@keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.animate-scaleIn { animation: scaleIn 0.3s ease-out; }
</style>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useCart } from '../Composables/useCart';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix leaflet default icon issue
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

L.Icon.Default.mergeOptions({
  iconRetinaUrl,
  iconUrl,
  shadowUrl,
});

const {
  items: cartItems,
  totalAmount,
  incrementQuantity,
  decrementQuantity,
  removeFromCart,
  clearCart,
} = useCart();

const form = useForm({
  customer_name: '',
  customer_phone: '',
  customer_address: '',
  latitude: '',
  longitude: '',
  payment_method: '',
  notes: '',
  items: [],
});

let map = null;
let marker = null;

const isLocating = ref(false);
const isFetchingAddress = ref(false);
const isLoadingOverlay = ref(false);
const loadingMessage = ref('');

const showOverlay = (msg) => { loadingMessage.value = msg; isLoadingOverlay.value = true; };
const hideOverlay = () => { isLoadingOverlay.value = false; };

const getAddressFromCoordinates = async (lat, lng) => {
  isFetchingAddress.value = true;
  try {
    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`);
    const data = await response.json();
    if (data && data.display_name) {
      form.customer_address = data.display_name;
    }
  } catch (error) {
    console.error("Error fetching address:", error);
  } finally {
    isFetchingAddress.value = false;
    hideOverlay();
  }
};

onMounted(() => {
  // Default to Indonesia center
  const defaultLat = -0.789275;
  const defaultLng = 113.921327;

  map = L.map('map').setView([defaultLat, defaultLng], 5);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

  // Update form when marker is dragged
  marker.on('dragend', function (event) {
    const position = marker.getLatLng();
    form.latitude = position.lat;
    form.longitude = position.lng;
    getAddressFromCoordinates(position.lat, position.lng);
  });

  // Map click
  map.on('click', function(e) {
      marker.setLatLng(e.latlng);
      form.latitude = e.latlng.lat;
      form.longitude = e.latlng.lng;
      getAddressFromCoordinates(e.latlng.lat, e.latlng.lng);
  });
});

const getCurrentLocation = () => {
  if (navigator.geolocation) {
    isLocating.value = true;
    showOverlay('Mengambil lokasi Anda...');
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        
        map.setView([lat, lng], 15);
        marker.setLatLng([lat, lng]);
        
        form.latitude = lat;
        form.longitude = lng;
        isLocating.value = false;
        loadingMessage.value = 'Mengambil alamat dari lokasi...';
        getAddressFromCoordinates(lat, lng);
      },
      (error) => {
        console.error("Error getting location: ", error);
        isLocating.value = false;
        hideOverlay();
        alert("Tidak dapat mengambil lokasi. Pastikan izin lokasi diaktifkan.");
      }
    );
  } else {
    alert("Geolocation tidak didukung oleh browser ini.");
  }
};

const paymentMethods = [
  {
    value: 'transfer',
    label: 'Transfer Bank',
    desc: 'Bayar via transfer rekening bank',
    icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
  },
  {
    value: 'cod',
    label: 'COD',
    desc: 'Bayar saat barang diterima',
    icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
  },
  {
    value: 'datang_ke_toko',
    label: 'Datang ke Toko',
    desc: 'Ambil dan bayar langsung di toko',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
  },
];

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const placeOrder = () => {
  // Build items from cart
  form.items = cartItems.value.map(item => ({
    id: item.id,
    quantity: item.quantity,
  }));

  form.post('/checkout', {
    onSuccess: () => {
      clearCart();
    },
  });
};
</script>
