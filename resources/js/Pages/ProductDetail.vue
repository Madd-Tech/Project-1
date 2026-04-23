<template>
  <div class="min-h-screen bg-dark-900 pt-24">
    <NavBar />

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
      <nav class="flex items-center gap-2 text-sm text-gray-500">
        <Link href="/" class="hover:text-electric transition-colors">Home</Link>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <Link href="/products" class="hover:text-electric transition-colors">Produk</Link>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-white truncate max-w-xs">{{ product.name }}</span>
      </nav>
    </div>

    <section class="py-10 relative">
      <div class="absolute top-0 right-0 w-96 h-96 bg-electric/5 rounded-full blur-[150px]"></div>
      <div class="absolute bottom-0 left-0 w-64 h-64 bg-neon/5 rounded-full blur-[120px]"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
          <!-- Image -->
          <div class="glass-card rounded-3xl p-8 flex items-center justify-center group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-electric/5 to-neon/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            <!-- Stock badge -->
            <div class="absolute top-5 left-5 z-10">
              <span v-if="product.stock <= 5 && product.stock > 0" class="px-3 py-1 text-xs font-bold rounded-lg bg-amber/20 text-amber animate-pulse">
                Stok Terbatas ({{ product.stock }})
              </span>
              <span v-else-if="product.stock === 0" class="px-3 py-1 text-xs font-bold rounded-lg bg-red-500/20 text-red-400">
                Sold Out
              </span>
              <span v-else class="px-3 py-1 text-xs font-bold rounded-lg bg-neon/20 text-neon">
                In Stock
              </span>
            </div>

            <img
              :src="product.image ? '/storage/' + product.image : '/images/placeholder.png'"
              :alt="product.name"
              class="w-full max-w-sm h-80 object-contain transform group-hover:scale-105 transition-transform duration-500 relative z-10"
            >
          </div>

          <!-- Info -->
          <div class="flex flex-col justify-center space-y-6">
            <div>
              <span class="inline-block px-3 py-1 text-xs font-semibold tracking-widest uppercase text-neon bg-neon/10 rounded-full mb-3">
                {{ product.category ? product.category.name : 'Uncategorized' }}
              </span>
              <h1 class="text-3xl sm:text-4xl font-[Outfit] font-bold text-white leading-tight mb-3">
                {{ product.name }}
              </h1>
              <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-1">
                  <svg
                    v-for="star in 5"
                    :key="star"
                    :class="['w-5 h-5 transition-colors', star <= Math.round(averageRating) ? 'text-amber' : 'text-gray-600']"
                    fill="currentColor" viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
                <span class="text-white font-semibold text-lg">{{ averageRating > 0 ? averageRating : '–' }}</span>
                <span class="text-gray-400 text-sm">
                  {{ totalReviews > 0 ? `(${totalReviews} ulasan)` : 'Belum ada ulasan' }}
                </span>
              </div>
            </div>
            <div class="glass rounded-2xl p-5 border border-white/5">
              <p class="text-gray-400 text-sm mb-1">Harga</p>
              <p class="text-3xl font-bold text-white">Rp {{ formatPrice(product.price) }}</p>
            </div>

            <!-- Description -->
            <div>
              <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-2">Deskripsi Produk</h3>
              <p class="text-gray-300 leading-relaxed">{{ product.description }}</p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
              <button
                @click="handleAddToCart"
                :disabled="product.stock === 0"
                :class="[
                  'flex-1 flex items-center justify-center gap-2 py-4 px-6 rounded-2xl font-semibold text-white transition-all duration-300 transform hover:scale-[1.02] active:scale-95',
                  product.stock === 0
                    ? 'bg-gray-700 cursor-not-allowed'
                    : 'bg-gradient-to-r from-electric to-neon hover:shadow-xl hover:shadow-electric/30'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ product.stock === 0 ? 'Habis Terjual' : 'Tambah ke Keranjang' }}
              </button>
             
            </div>
          </div>
        </div>
        <div id="reviews" class="border-t border-white/5 pt-16">
          <h2 class="text-2xl sm:text-3xl font-[Outfit] font-bold text-white mb-10">
            Ulasan <span class="gradient-text">Pelanggan</span>
          </h2>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-16">
            <!-- Rating Overview Card -->
            <div class="glass-card rounded-3xl p-8 flex flex-col items-center justify-center text-center">
              <p class="text-7xl font-[Outfit] font-bold text-white mb-2">{{ averageRating > 0 ? averageRating : '–' }}</p>
              <div class="flex items-center gap-1 mb-3">
                <svg
                  v-for="star in 5"
                  :key="star"
                  :class="['w-6 h-6', star <= Math.round(averageRating) ? 'text-amber' : 'text-gray-700']"
                  fill="currentColor" viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </div>
              <p class="text-gray-400 text-sm">{{ totalReviews }} ulasan</p>

              <!-- Distribution bars -->
              <div class="w-full mt-6 space-y-2">
                <div v-for="i in [5,4,3,2,1]" :key="i" class="flex items-center gap-2">
                  <span class="text-xs text-gray-400 w-4 text-right flex-shrink-0">{{ i }}</span>
                  <svg class="w-3 h-3 text-amber flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <div class="flex-1 h-2 bg-dark-700 rounded-full overflow-hidden">
                    <div
                      class="h-full bg-gradient-to-r from-amber to-amber/60 rounded-full transition-all duration-700"
                      :style="{ width: ratingDistribution[i] ? ratingDistribution[i].percentage + '%' : '0%' }"
                    ></div>
                  </div>
                  <span class="text-xs text-gray-500 w-6 flex-shrink-0">{{ ratingDistribution[i] ? ratingDistribution[i].count : 0 }}</span>
                </div>
              </div>
            </div>

            <!-- Write a Review Form -->
            <div class="lg:col-span-2 glass-card rounded-3xl p-8">
              <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Tulis Ulasan
              </h3>

              <!-- Flash success -->
              <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 p-4 rounded-xl bg-neon/10 border border-neon/20 text-neon text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $page.props.flash.success }}
              </div>

              <form @submit.prevent="submitReview" class="space-y-4">
                <!-- Star picker -->
                <div>
                  <label class="block text-sm text-gray-400 mb-2">Rating *</label>
                  <div class="flex items-center gap-1">
                    <button
                      v-for="star in 5"
                      :key="star"
                      type="button"
                      @click="form.rating = star"
                      @mouseover="hoverRating = star"
                      @mouseleave="hoverRating = 0"
                      :class="['w-9 h-9 transition-all duration-150 transform hover:scale-125 focus:outline-none', (hoverRating || form.rating) >= star ? 'text-amber' : 'text-gray-600']"
                    >
                      <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                    </button>
                    <span class="ml-2 text-sm text-gray-400">{{ ratingLabels[form.rating] || 'Pilih rating' }}</span>
                  </div>
                  <p v-if="errors.rating" class="mt-1 text-xs text-red-400">{{ errors.rating }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Nama *</label>
                    <input
                      v-model="form.reviewer_name"
                      type="text"
                      placeholder="Nama Anda"
                      class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-electric/50 focus:border-electric/50 transition-colors text-sm"
                    >
                    <p v-if="errors.reviewer_name" class="mt-1 text-xs text-red-400">{{ errors.reviewer_name }}</p>
                  </div>
                  <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Email (opsional)</label>
                    <input
                      v-model="form.reviewer_email"
                      type="email"
                      placeholder="email@contoh.com"
                      class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-electric/50 focus:border-electric/50 transition-colors text-sm"
                    >
                  </div>
                </div>

                <div>
                  <label class="block text-sm text-gray-400 mb-1.5">Ulasan *</label>
                  <textarea
                    v-model="form.comment"
                    rows="4"
                    placeholder="Ceritakan pengalaman Anda dengan produk ini..."
                    class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-electric/50 focus:border-electric/50 transition-colors text-sm resize-none"
                  ></textarea>
                  <p v-if="errors.comment" class="mt-1 text-xs text-red-400">{{ errors.comment }}</p>
                  <p class="mt-1 text-xs text-gray-600">{{ form.comment.length }}/1000 karakter</p>
                </div>

                <button
                  type="submit"
                  :disabled="submitting"
                  class="w-full py-3 px-6 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all duration-300 transform hover:scale-[1.01] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                  <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                  {{ submitting ? 'Mengirim...' : 'Kirim Ulasan' }}
                </button>
              </form>
            </div>
          </div>

          <!-- Review List -->
          <div class="space-y-5">
            <h3 class="text-lg font-semibold text-white">
              Semua Ulasan
              <span class="ml-2 text-sm font-normal text-gray-500">({{ totalReviews }})</span>
            </h3>

            <!-- Empty state -->
            <div v-if="product.reviews.length === 0" class="glass-card rounded-3xl p-12 text-center">
              <div class="w-16 h-16 bg-dark-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
              </div>
              <p class="text-gray-400">Belum ada ulasan untuk produk ini.</p>
              <p class="text-gray-600 text-sm mt-1">Jadilah yang pertama memberikan ulasan!</p>
            </div>

            <!-- Review cards -->
            <div
              v-for="(review, idx) in product.reviews"
              :key="review.id"
              class="glass-card rounded-2xl p-6 hover:border-white/10 transition-all duration-300"
              :style="{ animationDelay: `${idx * 0.05}s` }"
            >
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                  <!-- Avatar -->
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-electric to-neon flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                    {{ review.reviewer_name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <p class="text-white font-medium text-sm">{{ review.reviewer_name }}</p>
                      <span v-if="review.is_verified" class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-neon/10 text-neon">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verified
                      </span>
                    </div>
                    <p class="text-gray-600 text-xs">{{ formatDate(review.created_at) }}</p>
                  </div>
                </div>
                <!-- Stars -->
                <div class="flex items-center gap-0.5 flex-shrink-0">
                  <svg
                    v-for="star in 5"
                    :key="star"
                    :class="['w-4 h-4', star <= review.rating ? 'text-amber' : 'text-gray-700']"
                    fill="currentColor" viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
              </div>
              <p class="mt-4 text-gray-300 text-sm leading-relaxed">{{ review.comment }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <FooterSection />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import NavBar from './Components/NavBar.vue';
import FooterSection from './Components/FooterSection.vue';
import { useCart } from '../Composables/useCart';

const { addToCart } = useCart();

const props = defineProps({
  product:            { type: Object, required: true },
  averageRating:      { type: Number, default: 0 },
  totalReviews:       { type: Number, default: 0 },
  ratingDistribution: { type: Object, default: () => ({}) },
});

// ── Review form state ──────────────────────────────────────────
const hoverRating = ref(0);
const submitting  = ref(false);
const errors      = ref({});

const form = ref({
  reviewer_name:  '',
  reviewer_email: '',
  rating:         0,
  comment:        '',
});

const ratingLabels = {
  1: 'Sangat Buruk',
  2: 'Buruk',
  3: 'Cukup',
  4: 'Bagus',
  5: 'Luar Biasa!',
};

const submitReview = () => {
  errors.value = {};

  // Client-side validation
  if (!form.value.rating) { errors.value.rating = 'Pilih rating terlebih dahulu.'; }
  if (!form.value.reviewer_name.trim()) { errors.value.reviewer_name = 'Nama wajib diisi.'; }
  if (!form.value.comment.trim() || form.value.comment.length < 10) { errors.value.comment = 'Ulasan minimal 10 karakter.'; }
  if (Object.keys(errors.value).length) return;

  submitting.value = true;

  router.post(`/products/${props.product.slug}/review`, {
    reviewer_name:  form.value.reviewer_name,
    reviewer_email: form.value.reviewer_email,
    rating:         form.value.rating,
    comment:        form.value.comment,
  }, {
    onSuccess: () => {
      form.value = { reviewer_name: '', reviewer_email: '', rating: 0, comment: '' };
      submitting.value = false;
    },
    onError: (e) => {
      errors.value = e;
      submitting.value = false;
    },
  });
};

// ── Cart ──────────────────────────────────────────────────────
const handleAddToCart = () => {
  addToCart(props.product);
};

// ── Helpers ───────────────────────────────────────────────────
const formatPrice = (price) => new Intl.NumberFormat('id-ID').format(price);

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    year: 'numeric', month: 'long', day: 'numeric',
  });
};
</script>
