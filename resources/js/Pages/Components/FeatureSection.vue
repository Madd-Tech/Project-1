<template>
  <section class="py-20 sm:py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="features-grid">
        <div
          v-for="feature in features"
          :key="feature.title"
          class="glass-card rounded-3xl p-8 text-center group"
        >
          <div :class="['w-14 h-14 mx-auto rounded-2xl flex items-center justify-center mb-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3', feature.iconBg]">
            <span class="text-2xl">{{ feature.icon }}</span>
          </div>
          <h3 class="text-lg font-bold text-white mb-2 font-[Outfit]">{{ feature.title }}</h3>
          <p class="text-gray-400 text-sm leading-relaxed">{{ feature.desc }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const features = [
  {
    icon: '🚚',
    title: 'Gratis Ongkir',
    desc: 'Pengiriman gratis ke seluruh Indonesia tanpa minimum pembelian.',
    iconBg: 'bg-electric/20',
  },
  {
    icon: '✅',
    title: '100% Original',
    desc: 'Garansi produk original dari brand resmi.',
    iconBg: 'bg-neon/20',
  },
  {
    icon: '🔄',
    title: 'Easy Return',
    desc: 'Pengembalian mudah dalam 30 hari jika tidak sesuai.',
    iconBg: 'bg-amber/20',
  },
  {
    icon: '🔒',
    title: 'Pembayaran Aman',
    desc: 'Transaksi terjamin aman dengan enkripsi terbaik.',
    iconBg: 'bg-purple-500/20',
  },
];
</script>
