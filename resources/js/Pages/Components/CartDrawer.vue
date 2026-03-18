<template>
  <!-- Backdrop -->
  <transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]" @click="closeDrawer"></div>
  </transition>

  <!-- Drawer Panel -->
  <transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="translate-x-full"
    enter-to-class="translate-x-0"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="translate-x-0"
    leave-to-class="translate-x-full"
  >
    <div
      v-if="isOpen"
      class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-dark-900 border-l border-white/5 z-[70] flex flex-col shadow-2xl shadow-black/40"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b border-white/5">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-electric/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-white font-[Outfit]">Keranjang</h2>
            <p class="text-xs text-gray-500">{{ totalItems }} item</p>
          </div>
        </div>
        <button
          @click="closeDrawer"
          class="p-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition-all duration-300"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Empty State -->
        <div v-if="items.length === 0" class="flex flex-col items-center justify-center h-full text-center py-12">
          <div class="w-20 h-20 rounded-2xl bg-dark-700 flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <h3 class="text-white font-semibold mb-1">Keranjang Kosong</h3>
          <p class="text-gray-500 text-sm">Tambahkan produk ke keranjang untuk mulai belanja</p>
        </div>

        <!-- Items -->
        <transition-group
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="opacity-0 translate-x-4"
          enter-to-class="opacity-100 translate-x-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 translate-x-0"
          leave-to-class="opacity-0 -translate-x-4"
        >
          <div
            v-for="item in items"
            :key="item.id"
            class="glass-card rounded-2xl p-4 flex gap-4"
          >
            <!-- Product Image -->
            <div class="w-20 h-20 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
              <img
                :src="item.image ? '/storage/' + item.image : '/images/placeholder.png'"
                :alt="item.name"
                class="w-full h-full object-contain p-1"
              />
            </div>

            <!-- Product Info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <p class="text-xs text-gray-500 uppercase tracking-wider">{{ item.category }}</p>
                  <h4 class="text-white font-medium text-sm truncate">{{ item.name }}</h4>
                  <p class="text-electric font-semibold text-sm mt-1">Rp {{ formatPrice(item.price) }}</p>
                </div>
                <button
                  @click="removeFromCart(item.id)"
                  class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all flex-shrink-0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center gap-2 mt-2">
                <button
                  @click="decrementQuantity(item.id)"
                  class="w-7 h-7 flex items-center justify-center bg-dark-800 border border-white/10 rounded-lg text-white hover:bg-white/5 transition-all text-xs"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                </button>
                <span class="w-8 text-center text-white text-sm font-medium">{{ item.quantity }}</span>
                <button
                  @click="incrementQuantity(item.id)"
                  class="w-7 h-7 flex items-center justify-center bg-dark-800 border border-white/10 rounded-lg text-white hover:bg-white/5 transition-all text-xs"
                >
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </button>
                <span class="text-gray-500 text-xs ml-auto">
                  Rp {{ formatPrice(item.price * item.quantity) }}
                </span>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <!-- Footer -->
      <div v-if="items.length > 0" class="border-t border-white/5 p-6 space-y-4">
        <!-- Totals -->
        <div class="space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Subtotal ({{ totalItems }} item)</span>
            <span class="text-gray-300">Rp {{ formatPrice(subtotal) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">PPN (11%)</span>
            <span class="text-gray-300">Rp {{ formatPrice(tax) }}</span>
          </div>
          <div class="flex justify-between text-sm font-bold pt-2 border-t border-white/5">
            <span class="text-white">Total</span>
            <span class="text-white">Rp {{ formatPrice(totalAmount) }}</span>
          </div>
        </div>

        <!-- Checkout Button -->
        <a
          href="/checkout"
          @click="closeDrawer"
          class="w-full py-3.5 bg-gradient-to-r from-electric to-neon rounded-2xl text-white font-semibold flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          Checkout
        </a>

        <!-- Clear Cart -->
        <button
          @click="clearCart"
          class="w-full py-2.5 text-sm text-gray-500 hover:text-red-400 transition-colors flex items-center justify-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Kosongkan Keranjang
        </button>
      </div>
    </div>
  </transition>

  <!-- Toast Notification -->
  <transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0 translate-y-4"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-4"
  >
    <div
      v-if="toast"
      class="fixed bottom-6 right-6 z-[80] glass rounded-2xl px-5 py-3.5 flex items-center gap-3 shadow-2xl shadow-black/30 border border-neon/20"
    >
      <div class="w-8 h-8 rounded-lg bg-neon/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <p class="text-white text-sm font-medium">{{ toast }}</p>
    </div>
  </transition>
</template>

<script setup>
import { useCart } from '../../Composables/useCart';

const {
  items,
  totalItems,
  subtotal,
  tax,
  totalAmount,
  isOpen,
  toast,
  removeFromCart,
  incrementQuantity,
  decrementQuantity,
  clearCart,
  closeDrawer,
} = useCart();

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};
</script>
