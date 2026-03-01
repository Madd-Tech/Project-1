<template>
  <section id="about" class="py-20 sm:py-28">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="glass-card rounded-[2rem] p-8 sm:p-12 text-center relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-60 h-60 bg-electric/10 rounded-full blur-[80px]"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-neon/10 rounded-full blur-[80px]"></div>
        <div class="relative">
          <span class="text-4xl mb-6 block">✉️</span>
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-[Outfit] font-bold text-white mb-4">
            Dapatkan Update <span class="gradient-text">Eksklusif</span>
          </h2>
          <p class="text-gray-400 max-w-lg mx-auto mb-8">
            Berlangganan newsletter kami dan dapatkan akses awal ke koleksi baru, diskon eksklusif, dan tips fashion.
          </p>
          <form @submit.prevent class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto" id="newsletter-form">
            <input
              type="email"
              placeholder="Masukkan email Anda..."
              class="flex-1 px-6 py-4 bg-dark-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-electric transition-colors"
              id="newsletter-email"
            >
            <button
              type="submit"
              class="px-8 py-4 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 transform hover:scale-105 whitespace-nowrap"
              id="newsletter-submit-btn"
            >
              Subscribe
            </button>
          </form>
          <p class="text-gray-500 text-xs mt-4">Gratis & bisa berhenti kapan saja. Kami menghormati privasi Anda.</p>
        </div>
      </div>
    </div>
  </section>
</template>
