<template>
  <section id="promo" class="py-20 sm:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative rounded-[2rem] overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-electric via-[#7c3aed] to-neon opacity-90"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px]"></div>

        <div class="relative grid lg:grid-cols-2 gap-8 items-center p-8 sm:p-12 lg:p-16">
          <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
              <svg class="w-4 h-4 text-amber" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-white/90 font-medium">Limited Time Offer</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-black text-white leading-tight mb-4">
              Diskon Hingga<br>
              <span class="text-amber">50% OFF</span>
            </h2>
            <p class="text-white/70 text-lg mb-8 max-w-lg">
              Dapatkan penawaran terbaik untuk koleksi sepatu pilihan. Gunakan kode promo untuk diskon tambahan!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
              <div class="flex items-center gap-2 px-5 py-3 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                <span class="text-white/60 text-sm">Kode:</span>
                <span class="text-white font-bold text-lg tracking-wider">HAISTORE50</span>
              </div>
              <a
                href="#products"
                class="px-8 py-3.5 bg-white text-dark-900 font-bold rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105"
                id="promo-shop-btn"
              >
                Shop Now →
              </a>
            </div>
          </div>

          <!-- Countdown -->
          <div class="flex justify-center lg:justify-end">
            <div class="grid grid-cols-4 gap-4">
              <div v-for="time in countdown" :key="time.label" class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 mb-2">
                  <span class="text-2xl sm:text-3xl font-[Outfit] font-bold text-white">{{ time.value }}</span>
                </div>
                <span class="text-xs text-white/60 uppercase tracking-wider">{{ time.label }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const countdown = [
  { value: '03', label: 'Hari' },
  { value: '12', label: 'Jam' },
  { value: '45', label: 'Menit' },
  { value: '22', label: 'Detik' },
];
</script>
