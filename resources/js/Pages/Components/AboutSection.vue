<template>
  <section id="about" class="py-20 sm:py-28 relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-electric/5 rounded-full blur-[160px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-neon/5 rounded-full blur-[140px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-electric bg-electric/10 rounded-full mb-4">Tentang Kami</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-bold text-white mb-4">
          Kenapa Harus <span class="gradient-text">BabuihanStore</span>?
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto leading-relaxed">
          Kami hadir untuk memberikan pengalaman belanja sepatu premium terbaik di Indonesia.
          Setiap produk kami pilih dengan teliti demi kenyamanan dan gaya Anda.
        </p>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto">
        <!-- Story Card -->
        <div class="relative group">
          <div class="absolute -inset-1 bg-gradient-to-r from-electric via-neon to-amber rounded-[2rem] blur-sm opacity-20 group-hover:opacity-40 transition-opacity duration-700"></div>
          <div class="relative glass-card rounded-[2rem] p-8 sm:p-10 hover:translate-y-0 hover:shadow-none">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 bg-gradient-to-br from-electric to-neon rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white font-[Outfit]">Cerita Kami</h3>
                <p class="text-gray-500 text-xs">Sejak 2020</p>
              </div>
            </div>
            <p class="text-gray-300 leading-relaxed mb-6">
              <span class="text-white font-semibold">BabuihanStore</span> dimulai dari passion sederhana terhadap dunia sneakers. 
              Bermula dari garasi kecil di Tangerang, kami kini telah melayani ribuan pelanggan di seluruh Indonesia 
              dengan koleksi sepatu terkurasi dari brand-brand ternama.
            </p>
            <p class="text-gray-400 leading-relaxed">
              Misi kami sederhana: memberikan akses mudah ke sepatu berkualitas premium 
              dengan harga transparan dan pelayanan yang ramah. Setiap pasang sepatu yang kami jual 
              telah melalui proses seleksi ketat untuk memastikan keaslian dan kualitasnya.
            </p>

            <!-- Timeline Dots -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/5">
              <div v-for="milestone in milestones" :key="milestone.year" class="flex-1 text-center group/item cursor-default">
                <div class="w-3 h-3 mx-auto rounded-full mb-2 transition-all duration-300" :class="milestone.color"></div>
                <div class="text-white font-bold text-sm font-[Outfit]">{{ milestone.year }}</div>
                <div class="text-gray-500 text-[10px] uppercase tracking-wider mt-0.5">{{ milestone.label }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const milestones = [
  { year: '2020', label: 'Founded', color: 'bg-electric' },
  { year: '2022', label: 'Expansion', color: 'bg-neon' },
  { year: '2024', label: 'Online', color: 'bg-amber' },
  { year: '2026', label: 'Sekarang', color: 'bg-gradient-to-r from-electric to-neon' },
];
</script>
