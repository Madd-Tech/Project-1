<template>
  <section class="py-20 sm:py-28 relative">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber/5 rounded-full blur-[200px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-amber bg-amber/10 rounded-full mb-4">Testimonials</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-bold text-white mb-4">
          Apa Kata <span class="gradient-text">Pelanggan</span>
        </h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="testimonials-grid">
        <div
          v-for="testimonial in testimonials"
          :key="testimonial.name"
          class="glass-card rounded-3xl p-8"
        >
          <div class="flex items-center gap-1 mb-4">
            <svg v-for="star in 5" :key="star" class="w-4 h-4 text-amber" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <p class="text-gray-300 leading-relaxed mb-6 italic">"{{ testimonial.text }}"</p>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold" :class="testimonial.avatarBg">
              {{ testimonial.name.charAt(0) }}
            </div>
            <div>
              <div class="text-white font-semibold">{{ testimonial.name }}</div>
              <div class="text-gray-500 text-sm">{{ testimonial.role }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const testimonials = [
  {
    name: 'Ahmad Rizky',
    role: 'Verified Buyer',
    text: 'Sepatu yang saya beli kualitasnya luar biasa! Packaging rapi dan pengiriman cepat. Pasti balik lagi belanja di HaiStore.',
    avatarBg: 'bg-electric/20 text-electric',
  },
  {
    name: 'Sarah Putri',
    role: 'Verified Buyer',
    text: 'Akhirnya nemu toko online yang jual sepatu original dengan harga yang reasonable. Customer service-nya juga super responsif!',
    avatarBg: 'bg-neon/20 text-neon',
  },
  {
    name: 'Budi Santoso',
    role: 'Verified Buyer',
    text: 'Sudah beli 3 kali di HaiStore dan selalu puas. Koleksinya lengkap, update terus dengan model terbaru. Recommended banget!',
    avatarBg: 'bg-amber/20 text-amber',
  },
];
</script>
