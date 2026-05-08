<template>
  <div class="min-h-screen bg-dark-900 flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full text-center">
      <!-- Success Animation -->
      <div class="relative mb-8">
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-electric/20 to-neon/20 rounded-full flex items-center justify-center animate-pulse">
          <div class="w-16 h-16 bg-gradient-to-br from-electric to-neon rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>
      </div>

      <h1 class="text-3xl font-bold text-white font-[Outfit] mb-3">Pesanan Berhasil! 🎉</h1>
      <p class="text-gray-400 mb-8">Terima kasih atas pesanan Anda. Pesanan Anda sedang menunggu konfirmasi dari admin.</p>

      <!-- Order Card -->
      <div class="glass-card rounded-3xl p-8 text-left mb-8">
        <div class="flex items-center justify-between mb-6">
          <div>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Nomor Pesanan</p>
            <p class="text-lg font-bold text-electric font-mono mt-1">{{ order.order_number }}</p>
          </div>
          <span class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
            {{ order.status }}
          </span>
        </div>

        <div class="space-y-4 border-t border-white/5 pt-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-500 mb-1">Nama</p>
              <p class="text-sm text-white font-medium">{{ order.customer_name }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-1">Telepon</p>
              <p class="text-sm text-white font-medium">{{ order.customer_phone }}</p>
            </div>
          </div>
          <div>
            <p class="text-xs text-gray-500 mb-1">Alamat</p>
            <p class="text-sm text-white font-medium">{{ order.customer_address }}</p>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
              <p class="text-sm text-white font-medium">{{ paymentLabel(order.payment_method) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 mb-1">Total</p>
              <p class="text-sm text-white font-bold">Rp {{ formatPrice(order.total_amount) }}</p>
            </div>
          </div>
        </div>

        <!-- Items -->
        <div class="mt-6 border-t border-white/5 pt-4">
          <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Produk Dipesan</p>
          <div
            v-for="item in order.items"
            :key="item.id"
            class="flex items-center justify-between py-2"
          >
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-dark-700 rounded-lg overflow-hidden flex-shrink-0">
                <img
                  :src="item.product?.image ? '/storage/' + item.product.image : '/images/placeholder.png'"
                  :alt="item.product_name"
                  class="w-full h-full object-contain p-0.5"
                />
              </div>
              <div>
                <p class="text-sm text-white">{{ item.product_name }}</p>
                <p class="text-xs text-gray-500">x{{ item.quantity }}</p>
              </div>
            </div>
            <p class="text-sm text-gray-300">Rp {{ formatPrice(item.subtotal) }}</p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="/" class="px-6 py-3 glass text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
          Kembali ke Home
        </a>
        <a href="/products" class="px-6 py-3 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
          Belanja Lagi
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  order: {
    type: Object,
    required: true,
  },
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const paymentLabel = (method) => {
  const labels = {
    transfer: 'Transfer Bank',
    cod: 'COD (Bayar di Tempat)',
    datang_ke_toko: 'Datang ke Toko',
  };
  return labels[method] || method;
};
</script>
