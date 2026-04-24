<template>
  <footer class="border-t border-white/5 pt-16 pb-8" id="footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <div class="flex items-center gap-3 mb-5">
           <img :src="'/assets/logo.png'" alt="logo" class="w-20 h-20 object-contain">
            <span class="text-xl font-bold font-[Outfit]">
              <span class="text-white">Bubuihan</span><span class="text-electric">Store</span>
            </span>
          </div>
          <p class="text-gray-400 text-sm leading-relaxed mb-6">
            Toko sepatu premium online terpercaya di Indonesia. Kualitas terjamin, harga terbaik.
          </p>
          <div class="flex items-center gap-3">
            <a
              v-for="social in socials"
              :key="social.name"
              :href="social.url"
              class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-all duration-300"
            >
              <span class="text-sm">{{ social.icon }}</span>
            </a>
          </div>
        </div>

        <!-- Links -->
        <div v-for="section in footerSections" :key="section.title">
          <h4 class="text-white font-semibold font-[Outfit] mb-4">{{ section.title }}</h4>
          <ul class="space-y-3">
            <li v-for="link in section.links" :key="link.text">
              <a :href="link.href" class="text-gray-400 text-sm hover:text-neon transition-colors duration-300">{{ link.text }}</a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-gray-500 text-sm">© 2026 BubuihanStore. All rights reserved.</p>
      
      </div>
    </div>
  </footer>
</template>

<script setup>
const waNumber = import.meta.env.VITE_WHATSAPP_NUMBER || '6285695429616';

const footerSections = [
  {
    title: 'Produk',
    links: [
      { text: 'Running Shoes', href: '/products' },
      { text: 'Lifestyle', href: '/products' },
      { text: 'Basketball', href: '/products' },
      { text: 'Training', href: '/products' },
      { text: 'Limited Edition', href: '/products' },
    ],
  },
  {
    title: 'Layanan',
    links: [
      { text: 'Ukuran Sepatu', href: '/faq' },
      { text: 'FAQ', href: '/faq' },
      { text: 'Hubungi Kami', href: `https://wa.me/${waNumber}` },
    ],
  },
  {
    title: 'Perusahaan',
    links: [
      { text: 'Tentang Kami', href: '/#about' },
      { text: 'Karir', href: '#' },
      { text: 'Blog', href: '#' },
    ],
  },
];
</script>
