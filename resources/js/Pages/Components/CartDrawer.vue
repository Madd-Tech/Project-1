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

      <div class="px-6 pt-4">
        <div class="grid grid-cols-2 p-1 rounded-xl border border-white/10 bg-dark-800/80">
          <button
            @click="activeTab = 'cart'"
            class="py-2 text-xs font-semibold rounded-lg transition-all"
            :class="activeTab === 'cart' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white'"
          >
            Cart
          </button>
          <button
            @click="activeTab = 'orders'"
            class="relative py-2 text-xs font-semibold rounded-lg transition-all"
            :class="activeTab === 'orders' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white'"
          >
            Order Status
            <span
              v-if="pendingOrders.length > 0"
              class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 bg-red-500 text-white text-[10px] leading-4 rounded-full text-center"
            >
              {{ pendingOrders.length > 9 ? '9+' : pendingOrders.length }}
            </span>
          </button>
        </div>
      </div>

      <!-- Cart Items -->
      <div v-if="activeTab === 'cart'" class="flex-1 overflow-y-auto p-6 space-y-4">
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

      <!-- Orders Tab -->
      <div v-else class="flex-1 overflow-y-auto p-6 space-y-3">
        <div class="flex items-center justify-between">
          <p class="text-xs text-gray-400">
            {{ visibleCustomerOrders.length }} order
          </p>
          <button
            v-if="nonPendingOrders.length > 0"
            @click="clearOrderStatus"
            class="px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-red-500/30 text-red-300 hover:bg-red-500/10 transition-colors"
          >
            Clear Non-Pending
          </button>
        </div>

        <div v-if="visibleCustomerOrders.length === 0" class="flex flex-col items-center justify-center h-full text-center py-12">
          <div class="w-20 h-20 rounded-2xl bg-dark-700 flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M8 4h8a2 2 0 012 2v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z" />
            </svg>
          </div>
          <h3 class="text-white font-semibold mb-1">Belum Ada Order</h3>
          <p class="text-gray-500 text-sm">Status checkout akan muncul di sini</p>
        </div>

        <div v-if="pendingOrders.length > 0" class="space-y-3">
          <p class="text-xs font-semibold uppercase tracking-wide text-red-300">Pending Orders</p>
          <div
            v-for="order in pendingOrders"
            :key="order.order_number"
            class="glass-card rounded-2xl p-4 border border-red-500/20"
          >
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-[11px] text-gray-500 uppercase tracking-wide">Order</p>
                <p class="text-white text-sm font-semibold">{{ order.order_number }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ formatDate(order.created_at) }}</p>
              </div>
              <span
                class="px-2.5 py-1 text-[11px] rounded-full border font-semibold uppercase tracking-wide"
                :class="getOrderStatusClass(order.status)"
              >
                {{ getOrderStatusLabel(order.status) }}
              </span>
            </div>
            <div class="mt-3 pt-3 border-t border-white/5 flex justify-between text-xs">
              <span class="text-gray-400">Total</span>
              <span class="text-white font-semibold">Rp {{ formatPrice(order.total_amount) }}</span>
            </div>
            <button
              v-if="canCancelOrder(order)"
              @click="cancelOrder(order.order_number)"
              :disabled="cancellingOrderNumber === order.order_number"
              class="mt-3 w-full px-3 py-2 text-xs font-semibold rounded-lg border border-red-500/30 text-red-300 hover:bg-red-500/10 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              {{ cancellingOrderNumber === order.order_number ? 'Cancelling...' : 'Cancel Order' }}
            </button>
          </div>
        </div>

        <div v-if="nonPendingOrders.length > 0" class="space-y-3">
          <p class="text-xs font-semibold uppercase tracking-wide text-gray-300">Other Status</p>
          <div
            v-for="order in nonPendingOrders"
            :key="order.order_number"
            class="glass-card rounded-2xl p-4 border border-white/10"
          >
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-[11px] text-gray-500 uppercase tracking-wide">Order</p>
                <p class="text-white text-sm font-semibold">{{ order.order_number }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ formatDate(order.created_at) }}</p>
              </div>
              <div class="flex items-center gap-2">
                <span
                  class="px-2.5 py-1 text-[11px] rounded-full border font-semibold uppercase tracking-wide"
                  :class="getOrderStatusClass(order.status)"
                >
                  {{ getOrderStatusLabel(order.status) }}
                </span>
                <button
                  @click="deleteOrderStatus(order.order_number)"
                  class="w-7 h-7 rounded-lg border border-red-500/30 text-red-300 hover:bg-red-500/10 transition-colors flex items-center justify-center"
                  title="Delete status"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/5 flex justify-between text-xs">
              <span class="text-gray-400">Total</span>
              <span class="text-white font-semibold">Rp {{ formatPrice(order.total_amount) }}</span>
            </div>
            <button
              v-if="canCancelOrder(order)"
              @click="cancelOrder(order.order_number)"
              :disabled="cancellingOrderNumber === order.order_number"
              class="mt-3 w-full px-3 py-2 text-xs font-semibold rounded-lg border border-red-500/30 text-red-300 hover:bg-red-500/10 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              {{ cancellingOrderNumber === order.order_number ? 'Cancelling...' : 'Cancel Order' }}
            </button>
          </div>
        </div>

      </div>

      <!-- Footer -->
      <div v-if="activeTab === 'cart' && items.length > 0" class="border-t border-white/5 p-6 space-y-4">
        <!-- Totals -->
        <div class="space-y-2">
         
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
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
          Checkout Sekarang
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
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useCart } from '../../Composables/useCart';

const {
  items,
  totalItems,
  totalAmount,
  isOpen,
  toast,
  removeFromCart,
  incrementQuantity,
  decrementQuantity,
  clearCart,
  closeDrawer,
} = useCart();

const page = usePage();
const activeTab = ref('cart');
const customerOrders = computed(() => page.props.auth?.customer?.orders || []);
const dismissedOrderNumbers = ref([]);
const cancellingOrderNumber = ref(null);
const dismissedStorageKey = computed(() => {
  const customerId = page.props.auth?.customer?.id;
  return customerId ? `dismissed_order_status_${customerId}` : null;
});

if (typeof window !== 'undefined' && dismissedStorageKey.value) {
  try {
    const raw = window.localStorage.getItem(dismissedStorageKey.value);
    dismissedOrderNumbers.value = raw ? JSON.parse(raw) : [];
  } catch {
    dismissedOrderNumbers.value = [];
  }
}

const visibleCustomerOrders = computed(() =>
  customerOrders.value.filter((order) => !dismissedOrderNumbers.value.includes(order.order_number))
);
const pendingOrders = computed(() => visibleCustomerOrders.value.filter((order) => order.status === 'pending'));
const nonPendingOrders = computed(() => visibleCustomerOrders.value.filter((order) => order.status !== 'pending'));

const clearOrderStatus = () => {
  const numbers = nonPendingOrders.value.map((order) => order.order_number);
  dismissedOrderNumbers.value = [...new Set([...dismissedOrderNumbers.value, ...numbers])];

  if (typeof window !== 'undefined' && dismissedStorageKey.value) {
    window.localStorage.setItem(dismissedStorageKey.value, JSON.stringify(dismissedOrderNumbers.value));
  }
};

const deleteOrderStatus = (orderNumber) => {
  dismissedOrderNumbers.value = [...new Set([...dismissedOrderNumbers.value, orderNumber])];
  if (typeof window !== 'undefined' && dismissedStorageKey.value) {
    window.localStorage.setItem(dismissedStorageKey.value, JSON.stringify(dismissedOrderNumbers.value));
  }
};

const cancelOrder = (orderNumber) => {
  if (cancellingOrderNumber.value) return;

  cancellingOrderNumber.value = orderNumber;
  router.put(`/orders/${orderNumber}/cancel`, {}, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    onFinish: () => {
      cancellingOrderNumber.value = null;
    },
  });
};

const canCancelOrder = (order) => {
  return order?.status !== 'completed' && order?.status !== 'cancelled';
};

const getOrderStatusLabel = (status) => {
  if (status === 'pending') return 'Pending';
  if (status === 'cancelled') return 'Cancelled';
  if (status === 'confirmed') return 'Confirmed';
  if (status === 'processing') return 'Processing';
  if (status === 'completed') return 'Completed';
  return status;
};

const getOrderStatusClass = (status) => {
  if (status === 'cancelled') return 'border-red-500/30 bg-red-500/10 text-red-300';
  if (status === 'pending') return 'border-amber-500/30 bg-amber-500/10 text-amber-300';
  if (status === 'confirmed') return 'border-blue-500/30 bg-blue-500/10 text-blue-300';
  if (status === 'processing') return 'border-cyan-500/30 bg-cyan-500/10 text-cyan-300';
  if (status === 'completed') return 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300';
  return 'border-white/20 bg-white/5 text-gray-300';
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const formatDate = (value) => {
  if (!value) return '-';
  return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value));
};
</script>
