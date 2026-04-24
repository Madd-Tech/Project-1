<template>
  <div class="min-h-screen bg-dark-900">
    <NavBar :filters="{}" />

    <!-- Hero Banner -->
    <section class="relative pt-32 pb-20 overflow-hidden">
      <!-- Background Effects -->
      <div class="absolute inset-0">
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-electric/10 rounded-full blur-[128px]"></div>
        <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-neon/10 rounded-full blur-[128px]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.03)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
      </div>

      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-amber bg-amber/10 rounded-full mb-4 animate-fade-in-up">
          Help Center
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-[Outfit] font-black text-white mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
          Pertanyaan yang sering <span class="gradient-text">diajukan</span>
        </h1>
        <p class="text-lg text-gray-400 max-w-2xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
          Temukan jawaban dari pertanyaan yang paling sering ditanyakan pelanggan kami.
          Tidak menemukan jawabannya? Hubungi kami via WhatsApp.
        </p>
      </div>
    </section>

    <!-- FAQ Content -->
    <section class="pb-20 sm:pb-28 relative">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Category Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-12">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            :class="[
              'px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center gap-2',
              activeCategory === cat.id
                ? 'bg-gradient-to-r from-electric to-neon text-white shadow-lg shadow-electric/25'
                : 'glass text-gray-400 hover:text-white hover:bg-white/5'
            ]"
          >
            <component :is="cat.icon" class="w-4 h-4" />
            {{ cat.label }}
          </button>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
          <div
            v-for="(faq, index) in filteredFaqs"
            :key="faq.question"
            class="glass-card rounded-2xl overflow-hidden hover:translate-y-0 hover:shadow-none transition-all duration-300"
            :class="openIndex === index ? 'border-electric/30' : ''"
          >
            <button
              @click="toggle(index)"
              class="w-full flex items-center justify-between gap-4 p-6 text-left group"
              :id="`faq-item-${index}`"
            >
              <div class="flex items-center gap-4 min-w-0">
                <div :class="[
                  'w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300',
                  openIndex === index ? 'bg-electric/20 text-electric' : 'bg-dark-700 text-gray-500 group-hover:text-white group-hover:bg-dark-600'
                ]">
                  <component :is="faq.icon" class="w-5 h-5" />
                </div>
                <h3 :class="[
                  'font-semibold text-sm sm:text-base font-[Outfit] transition-colors duration-300',
                  openIndex === index ? 'text-electric' : 'text-white group-hover:text-electric-light'
                ]">
                  {{ faq.question }}
                </h3>
              </div>
              <div :class="[
                'w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all duration-300',
                openIndex === index ? 'bg-electric/20 rotate-180' : 'bg-dark-700 group-hover:bg-dark-600'
              ]">
                <svg class="w-4 h-4" :class="openIndex === index ? 'text-electric' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </button>

            <!-- Answer -->
            <transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="max-h-0 opacity-0"
              enter-to-class="max-h-96 opacity-100"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="max-h-96 opacity-100"
              leave-to-class="max-h-0 opacity-0"
            >
              <div v-if="openIndex === index" class="overflow-hidden">
                <div class="px-6 pb-6 pl-20">
                  <p class="text-gray-400 leading-relaxed text-sm">{{ faq.answer }}</p>
                </div>
              </div>
            </transition>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredFaqs.length === 0" class="text-center py-16">
          <div class="w-20 h-20 mx-auto bg-dark-700 rounded-3xl flex items-center justify-center mb-4">
            <Search class="w-8 h-8 text-gray-400" />
          </div>
          <p class="text-gray-400">Tidak ada pertanyaan di kategori ini.</p>
        </div>

        <!-- Contact CTA -->
        <div class="mt-16 relative rounded-[2rem] overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-[#25D366]/10 via-transparent to-[#25D366]/10"></div>
          <div class="relative glass-card rounded-[2rem] p-8 sm:p-12 text-center hover:translate-y-0 hover:shadow-none">
            <div class="w-16 h-16 mx-auto bg-[#25D366]/10 rounded-2xl flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </div>
            <h3 class="text-2xl sm:text-3xl font-[Outfit] font-bold text-white mb-3">
              Masih Ada Pertanyaan?
            </h3>
            <p class="text-gray-400 max-w-lg mx-auto mb-8 leading-relaxed">
              Kami siap membantu kapan saja. Hubungi kami langsung via WhatsApp untuk respons cepat.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <a
                :href="`https://wa.me/${waNumber}?text=${encodeURIComponent('Hallo, saya punya pertanyaan mengenai produk di BubuihanStore')}`"
                target="_blank"
                class="inline-flex items-center gap-2 px-8 py-4 bg-[#25D366] hover:bg-[#20BA5A] text-white font-semibold rounded-2xl hover:shadow-xl hover:shadow-[#25D366]/25 transition-all duration-300 transform hover:scale-105"
                id="faq-wa-btn"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Chat di WhatsApp
              </a>
              <a
                href="/"
                class="inline-flex items-center gap-2 px-8 py-4 glass text-gray-300 font-semibold rounded-2xl hover:bg-white/5 hover:text-white transition-all duration-300"
                id="faq-back-btn"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <FooterSection />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { 
  List, ShoppingCart, Footprints, Truck, CreditCard, RefreshCw, 
  Smartphone, FileText, CheckCircle, Ruler, Search, Package, MapPin, 
  Lock, CircleDollarSign, ArrowLeftRight 
} from 'lucide-vue-next';
import NavBar from './Components/NavBar.vue';
import FooterSection from './Components/FooterSection.vue';

const waNumber = import.meta.env.VITE_WHATSAPP_NUMBER || '6285695429616';

const activeCategory = ref('all');
const openIndex = ref(null);

const toggle = (index) => {
  openIndex.value = openIndex.value === index ? null : index;
};

const categories = [
  { id: 'all', label: 'Semua', icon: List },
  { id: 'order', label: 'Pemesanan', icon: ShoppingCart },
  { id: 'product', label: 'Produk', icon: Footprints },
  { id: 'shipping', label: 'Pengiriman', icon: Truck },
  { id: 'payment', label: 'Pembayaran', icon: CreditCard },
  { id: 'return', label: 'Pengembalian', icon: RefreshCw },
];

const faqs = [
  {
    category: 'order',
    icon: ShoppingCart,
    question: 'Bagaimana cara memesan produk di BubuihanStore?',
    answer: 'Anda cukup memilih produk yang diinginkan, tentukan jumlahnya, lalu klik tombol "Pesan via WhatsApp". Pesanan Anda akan otomatis terkirim ke WhatsApp kami beserta detail produk dan jumlah yang dipilih. Tim kami akan segera memproses pesanan Anda.',
  },
  {
    category: 'order',
    icon: Smartphone,
    question: 'Apakah saya perlu membuat akun untuk berbelanja?',
    answer: 'Tidak perlu! Anda bisa langsung berbelanja tanpa perlu mendaftar atau membuat akun. Cukup pilih produk dan pesan langsung via WhatsApp. Mudah dan cepat!',
  },
  {
    category: 'order',
    icon: FileText,
    question: 'Bisakah saya memesan lebih dari satu produk sekaligus?',
    answer: 'Tentu! Anda bisa menambahkan beberapa produk ke keranjang belanja, lalu melakukan checkout sekaligus. Semua produk dalam keranjang akan otomatis masuk ke dalam pesan WhatsApp Anda.',
  },
  {
    category: 'product',
    icon: CheckCircle,
    question: 'Apakah semua produk di BubuihanStore dijamin original?',
    answer: 'Ya, 100% original. Semua produk yang kami jual adalah produk asli dari brand resmi. Kami bekerja sama langsung dengan distributor resmi untuk memastikan keaslian setiap produk. Garansi keaslian berlaku untuk semua produk.',
  },
  {
    category: 'product',
    icon: Ruler,
    question: 'Bagaimana cara menentukan ukuran sepatu yang tepat?',
    answer: 'Setiap halaman produk dilengkapi dengan panduan ukuran. Anda juga bisa menghubungi tim kami via WhatsApp untuk konsultasi ukuran secara personal. Kami akan membantu Anda menemukan ukuran yang paling pas.',
  },
  {
    category: 'product',
    icon: Search,
    question: 'Apakah saya bisa melihat review dari pembeli lain?',
    answer: 'Ya! Setiap produk memiliki halaman detail yang menampilkan ulasan dan rating dari pembeli sebelumnya. Anda bisa mengecek pengalaman pelanggan lain sebelum memutuskan untuk membeli.',
  },
  {
    category: 'shipping',
    icon: Truck,
    question: 'Berapa lama estimasi pengiriman?',
    answer: 'Untuk area Jawa biasanya 2-3 hari kerja, luar Jawa 3-5 hari kerja, dan untuk daerah terpencil bisa memakan waktu 5-7 hari kerja. Kami menggunakan jasa ekspedisi terpercaya untuk memastikan paket sampai dengan aman.',
  },
  {
    category: 'shipping',
    icon: Package,
    question: 'Apakah ada gratis ongkir?',
    answer: 'Ya! Kami memberikan gratis ongkir ke seluruh Indonesia tanpa minimum pembelian. Promo ini berlaku untuk semua produk yang tersedia di toko kami.',
  },
  {
    category: 'shipping',
    icon: MapPin,
    question: 'Bisa kirim ke luar kota / luar pulau?',
    answer: 'Tentu bisa! Kami melayani pengiriman ke seluruh wilayah Indonesia, termasuk daerah-daerah terpencil. Biaya dan estimasi waktu pengiriman akan diinformasikan sebelum Anda melakukan pembayaran.',
  },
  {
    category: 'payment',
    icon: CreditCard,
    question: 'Metode pembayaran apa saja yang tersedia?',
    answer: 'Saat ini kami menerima pembayaran melalui transfer bank (BCA, BNI, Mandiri, BRI), e-wallet (GoPay, OVO, Dana, ShopeePay), dan COD (Cash on Delivery) untuk area tertentu. Detail pembayaran akan diinformasikan saat Anda menghubungi kami via WhatsApp.',
  },
  {
    category: 'payment',
    icon: Lock,
    question: 'Apakah pembayaran di BubuihanStore aman?',
    answer: 'Ya, sangat aman. Semua transaksi dilakukan langsung melalui WhatsApp dan transfer bank resmi. Kami tidak menyimpan data finansial apapun dari pelanggan. Konfirmasi pembayaran juga dilakukan secara real-time.',
  },
  {
    category: 'return',
    icon: RefreshCw,
    question: 'Bagaimana kebijakan pengembalian barang?',
    answer: 'Kami menyediakan kebijakan pengembalian dalam 30 hari sejak barang diterima. Barang harus dalam kondisi belum digunakan, lengkap dengan tag dan packaging asli. Hubungi kami via WhatsApp untuk memulai proses pengembalian.',
  },
  {
    category: 'return',
    icon: CircleDollarSign,
    question: 'Apakah saya bisa mendapat refund?',
    answer: 'Ya! Jika produk yang Anda terima cacat, salah ukuran, atau tidak sesuai deskripsi, kami akan memberikan opsi pengembalian dana penuh atau tukar produk. Proses refund biasanya memakan waktu 3-5 hari kerja setelah barang kami terima kembali.',
  },
  {
    category: 'return',
    icon: ArrowLeftRight,
    question: 'Bisakah saya menukar dengan ukuran lain?',
    answer: 'Tentu bisa! Jika ukuran tidak pas, Anda bisa menukar dengan ukuran lain (selama stok tersedia) tanpa biaya tambahan. Ongkos kirim pengembalian ditanggung oleh kami.',
  },
];

const filteredFaqs = computed(() => {
  if (activeCategory.value === 'all') return faqs;
  return faqs.filter(faq => faq.category === activeCategory.value);
});
</script>
