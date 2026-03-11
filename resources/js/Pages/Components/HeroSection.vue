<template>
  <section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0">
      <div class="absolute top-1/4 -left-32 w-96 h-96 bg-electric/10 rounded-full blur-[128px]"></div>
      <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-neon/10 rounded-full blur-[128px]"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber/5 rounded-full blur-[150px]"></div>
      <!-- Grid Pattern -->
      <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.03)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
        <!-- Left Content -->
        <div class="animate-slide-in-left">
          <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full mb-6">
            <span class="w-2 h-2 bg-neon rounded-full animate-pulse"></span>
            <span class="text-sm text-gray-300 font-medium">New Collection 2026</span>
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-7xl font-[Outfit] font-black leading-[1.1] mb-6">
            <span class="text-white">Step Into</span><br>
            <span class="gradient-text">Your Style</span><br>
            <span class="text-white">Revolution</span>
          </h1>

          <p class="text-lg text-gray-400 max-w-lg mb-8 leading-relaxed">
            Temukan koleksi sepatu premium terbaik untuk setiap gaya dan aktivitas Anda.
            Kualitas premium, desain modern, kenyamanan maksimal.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 mb-12">
            <a
              href="#products"
              class="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-2xl hover:shadow-xl hover:shadow-electric/25 transition-all duration-300 transform hover:scale-105 animate-pulse-glow"
              id="hero-shop-btn"
            >
              Explore Collection
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </a>
            <!-- <a
              href="#about"
              class="inline-flex items-center justify-center gap-2 px-8 py-4 glass text-white font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300"
              id="hero-about-btn"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              </svg>
              Watch Story
            </a> -->
          </div>

          <!-- Stats -->
          <div class="grid grid-cols-3 gap-6">
            <div v-for="stat in stats" :key="stat.label">
              <div class="text-2xl sm:text-3xl font-[Outfit] font-bold text-white">{{ stat.value }}</div>
              <div class="text-sm text-gray-500 mt-1">{{ stat.label }}</div>
            </div>
          </div>
        </div>

        <!-- Right - Hero Image -->
        <div class="relative animate-slide-in-right flex justify-center lg:justify-end">
          <div class="relative">
            <!-- Glow ring behind shoe -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-72 h-72 sm:w-96 sm:h-96 rounded-full border border-electric/20 animate-pulse"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-56 h-56 sm:w-80 sm:h-80 rounded-full border border-neon/10"></div>
            </div>
            <!-- Shoe Image -->
            <img
              :src="heroImage"
              alt="Premium Sneaker"
              class="relative w-full max-w-md lg:max-w-lg animate-float drop-shadow-[0_20px_60px_rgba(59,130,246,0.3)]"
            >
            <!-- Floating badges -->
            <!-- <div class="absolute top-8 right-0 glass rounded-2xl px-4 py-3 animate-fade-in-up" style="animation-delay: 0.6s;">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-neon/20 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-neon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <div>
                  <div class="text-white text-sm font-semibold">4.9 Rating</div>
                  <div class="text-gray-400 text-xs">2.5k+ Reviews</div>
                </div>
              </div>
            </div> -->
            <div class="absolute bottom-12 -left-4 glass rounded-2xl px-4 py-3 animate-fade-in-up" style="animation-delay: 0.9s;">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-amber/20 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-amber" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div>
                  <div class="text-white text-sm font-semibold">Free Shipping</div>
                  <div class="text-gray-400 text-xs">Seluruh Indonesia</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
      <span class="text-xs text-gray-500 tracking-widest uppercase">Scroll</span>
      <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
      </svg>
    </div>
  </section>
</template>

<script setup>
const heroImage = '/images/hero-shoe.png';

// const stats = [
//   { value: '50K+', label: 'Pelanggan' },
//   { value: '200+', label: 'Brand' },
//   { value: '99%', label: 'Kepuasan' },
// ];
</script>
