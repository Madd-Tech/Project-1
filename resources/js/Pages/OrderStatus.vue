<template>
  <div class="min-h-screen bg-dark-900">
    <!-- Minimal Top Bar -->
    <div class="glass border-b border-white/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group">
          <div class="w-10 h-10 bg-gradient-to-br from-electric to-neon rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <span class="text-xl font-bold font-[Outfit] tracking-tight">
            <span class="text-white">Babuihan</span><span class="text-electric">Store</span>
          </span>
        </a>
        <div class="flex items-center gap-2 text-gray-400 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Order Status</span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

      <!-- Status Banner -->
      <div :class="['rounded-3xl p-8 mb-8 text-center relative overflow-hidden', statusBanner.bgClass]">
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-[100px]"></div>
        </div>
        <div class="relative z-10">
          <div :class="['w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-4', statusBanner.iconBg]">
            <!-- Pending Icon -->
            <svg v-if="order.payment_status === 'pending'" class="w-8 h-8 text-amber animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <!-- Paid Icon -->
            <svg v-else-if="order.payment_status === 'paid'" class="w-8 h-8 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <!-- Expired / Cancelled Icon -->
            <svg v-else class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h1 class="text-2xl sm:text-3xl font-bold text-white font-[Outfit] mb-2">{{ statusBanner.title }}</h1>
          <p class="text-gray-400 text-sm max-w-md mx-auto">{{ statusBanner.subtitle }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Left: Order Info -->
        <div class="lg:col-span-3 space-y-6">

          <!-- Invoice Info -->
          <div class="glass-card rounded-3xl p-7">
            <div class="flex items-center justify-between mb-5">
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Detail Pesanan</h3>
              <span :class="['px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider', statusBadge.class]">
                {{ statusBadge.text }}
              </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
              <div>
                <p class="text-xs text-gray-500 mb-1">No. Invoice</p>
                <p class="text-white text-sm font-mono font-semibold">{{ order.order_number }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Order</p>
                <p class="text-gray-300 text-sm">{{ formatDate(order.created_at) }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">Nama Pelanggan</p>
                <p class="text-gray-300 text-sm">{{ order.customer_name }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="text-gray-300 text-sm">{{ order.customer_email }}</p>
              </div>
            </div>

            <!-- Products -->
            <div class="border-t border-white/5 pt-5 space-y-4">
              <template v-if="order.order_items && order.order_items.length > 0">
                <div v-for="item in order.order_items" :key="item.id" class="flex items-center gap-4">
                  <div class="w-16 h-16 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
                    <img
                      :src="item.product && item.product.image ? '/storage/' + item.product.image : '/images/placeholder.png'"
                      :alt="item.product ? item.product.name : 'Product'"
                      class="w-full h-full object-contain p-1"
                    />
                  </div>
                  <div class="flex-1">
                    <p class="text-white font-medium text-sm">{{ item.product ? item.product.name : 'Produk Dihapus' }}</p>
                    <p class="text-gray-500 text-xs">{{ item.quantity }} x Rp {{ formatPrice(item.unit_price) }}</p>
                  </div>
                  <p class="text-white font-semibold text-sm">Rp {{ formatPrice(item.subtotal) }}</p>
                </div>
              </template>
              <template v-else>
                <div class="flex items-center gap-4">
                  <div class="w-16 h-16 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
                    <img
                      :src="order.product && order.product.image ? '/storage/' + order.product.image : '/images/placeholder.png'"
                      :alt="order.product ? order.product.name : 'Product'"
                      class="w-full h-full object-contain p-1"
                    />
                  </div>
                  <div class="flex-1">
                    <p class="text-white font-medium text-sm">{{ order.product ? order.product.name : '-' }}</p>
                    <p class="text-gray-500 text-xs">{{ order.quantity }} x Rp {{ formatPrice(order.unit_price) }}</p>
                  </div>
                  <p class="text-white font-semibold text-sm">Rp {{ formatPrice(order.subtotal) }}</p>
                </div>
              </template>
            </div>

            <!-- Totals -->
            <div class="border-t border-white/5 mt-5 pt-5 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="text-gray-300">Rp {{ formatPrice(order.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">PPN (11%)</span>
                <span class="text-gray-300">Rp {{ formatPrice(order.tax) }}</span>
              </div>
              <div class="flex justify-between text-sm font-bold pt-2 border-t border-white/5">
                <span class="text-white">Total</span>
                <span class="text-white text-lg">Rp {{ formatPrice(order.total_amount) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Payment Instructions -->
        <div class="lg:col-span-2 space-y-6">

          <!-- VA / QRIS Instructions -->
          <div v-if="order.payment_status === 'pending'" class="glass-card rounded-3xl p-7">
            <h3 class="text-lg font-semibold text-white font-[Outfit] mb-5">Instruksi Pembayaran</h3>

            <!-- Bank Transfer / VA -->
            <div v-if="order.payment_method === 'bank_transfer' && order.va_number" class="space-y-4">
              <div class="p-4 bg-electric/5 border border-electric/10 rounded-2xl">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Virtual Account Number</p>
                <div class="flex items-center justify-between">
                  <p class="text-xl font-mono font-bold text-white tracking-wider">{{ order.va_number }}</p>
                  <button
                    @click="copyVa"
                    class="px-3 py-1.5 text-xs font-medium bg-electric/10 hover:bg-electric/20 text-electric rounded-lg transition-all"
                  >
                    {{ copied ? '✓ Copied' : 'Copy' }}
                  </button>
                </div>
              </div>

              <div class="p-4 bg-dark-800/50 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">Bank</p>
                <p class="text-white text-sm font-semibold uppercase">{{ order.payment_bank }}</p>
              </div>

              <div v-if="order.payment_expired_at" class="p-4 bg-amber/5 border border-amber/10 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">Bayar Sebelum</p>
                <p class="text-amber text-sm font-semibold">{{ formatDate(order.payment_expired_at) }}</p>
              </div>

              <!-- Steps -->
              <div class="space-y-3 mt-4">
                <p class="text-sm text-gray-400 font-medium">Cara Pembayaran:</p>
                <div v-for="(step, i) in vaSteps" :key="i" class="flex gap-3">
                  <div class="w-6 h-6 rounded-full bg-electric/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-[10px] font-bold text-electric">{{ i + 1 }}</span>
                  </div>
                  <p class="text-gray-400 text-sm">{{ step }}</p>
                </div>
              </div>
            </div>

            <!-- QRIS -->
            <div v-else-if="order.payment_method === 'qris' && order.qr_url" class="space-y-4 text-center">
              <div class="bg-white rounded-2xl p-6 inline-block mx-auto">
                <img :src="order.qr_url" alt="QR Code" class="w-48 h-48 mx-auto" />
              </div>
              <p class="text-gray-400 text-sm">Scan QR code di atas menggunakan aplikasi e-wallet atau mobile banking Anda.</p>

              <div v-if="order.payment_expired_at" class="p-4 bg-amber/5 border border-amber/10 rounded-xl">
                <p class="text-xs text-gray-500 mb-1">Berlaku Hingga</p>
                <p class="text-amber text-sm font-semibold">{{ formatDate(order.payment_expired_at) }}</p>
              </div>
            </div>

            <!-- Fallback -->
            <div v-else class="text-center py-6">
              <p class="text-gray-400 text-sm">Instruksi pembayaran sedang diproses...</p>
            </div>
          </div>

          <!-- Paid Success -->
          <div v-else-if="order.payment_status === 'paid'" class="glass-card rounded-3xl p-7 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-neon/10 flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Pembayaran Berhasil!</h3>
            <p class="text-gray-400 text-sm mb-4">Terima kasih. Pesanan Anda sedang diproses.</p>
            <p v-if="order.paid_at" class="text-xs text-gray-500">Dibayar pada: {{ formatDate(order.paid_at) }}</p>
          </div>

          <!-- Expired / Cancelled -->
          <div v-else class="glass-card rounded-3xl p-7 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-red-500/10 flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">
              {{ order.payment_status === 'expired' ? 'Pembayaran Kedaluwarsa' : 'Pembayaran Dibatalkan' }}
            </h3>
            <p class="text-gray-400 text-sm">Silakan buat pesanan baru jika Anda masih ingin membeli produk ini.</p>
          </div>

          <!-- Back Button -->
          <a
            href="/"
            class="flex items-center justify-center gap-2 w-full py-3.5 glass text-gray-300 font-medium rounded-2xl hover:bg-white/5 transition-all duration-300"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  order: {
    type: Object,
    required: true,
  }
});

const copied = ref(false);

const statusBanner = computed(() => {
  switch (props.order.payment_status) {
    case 'pending':
      return {
        title: 'Menunggu Pembayaran',
        subtitle: 'Segera selesaikan pembayaran Anda sebelum batas waktu yang ditentukan.',
        bgClass: 'glass-card border border-amber/10',
        iconBg: 'bg-amber/10',
      };
    case 'paid':
      return {
        title: 'Pembayaran Berhasil!',
        subtitle: 'Pesanan Anda telah terkonfirmasi dan sedang diproses untuk pengiriman.',
        bgClass: 'glass-card border border-neon/10',
        iconBg: 'bg-neon/10',
      };
    default:
      return {
        title: props.order.payment_status === 'expired' ? 'Pembayaran Kedaluwarsa' : 'Pembayaran Dibatalkan',
        subtitle: 'Pesanan Anda telah dibatalkan atau kedaluwarsa. Silakan buat pesanan baru.',
        bgClass: 'glass-card border border-red-500/10',
        iconBg: 'bg-red-500/10',
      };
  }
});

const statusBadge = computed(() => {
  switch (props.order.payment_status) {
    case 'pending':
      return { text: 'Pending', class: 'bg-amber/20 text-amber' };
    case 'paid':
      return { text: 'Lunas', class: 'bg-neon/20 text-neon' };
    case 'expired':
      return { text: 'Expired', class: 'bg-red-500/20 text-red-400' };
    default:
      return { text: 'Cancelled', class: 'bg-red-500/20 text-red-400' };
  }
});

const vaSteps = [
  'Buka aplikasi mobile banking atau ATM terdekat.',
  'Pilih menu Transfer > Virtual Account.',
  'Masukkan nomor Virtual Account di atas.',
  'Masukkan jumlah yang harus dibayar.',
  'Konfirmasi dan selesaikan pembayaran.',
];

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const copyVa = () => {
  if (props.order.va_number) {
    navigator.clipboard.writeText(props.order.va_number);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
  }
};
</script>
