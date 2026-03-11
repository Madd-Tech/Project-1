<template>
  <section id="categories" class="py-20 sm:py-28 relative">
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-neon/5 rounded-full blur-[150px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-electric bg-electric/10 rounded-full mb-4">Browse</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-bold text-white mb-4">
          Shop by <span class="gradient-text">Category</span>
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto">Temukan sepatu yang sempurna untuk setiap kebutuhan Anda.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="categories-grid">
        <div
          v-for="(category, index) in categories"
          :key="category.id"
          class="glass-card rounded-3xl p-8 text-center group cursor-pointer relative overflow-hidden"
        >
          <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-10 transition-opacity duration-500" :class="getCategoryStyles(index).gradient"></div>
          <div :class="['w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-5 transition-transform duration-300 group-hover:scale-110', getCategoryStyles(index).iconBg]">
            <span class="text-3xl">{{ getCategoryStyles(index).icon }}</span>
          </div>
          <h3 class="text-xl font-[Outfit] font-bold text-white mb-2">{{ category.name }}</h3>
          <p class="text-gray-400 text-sm mb-4">{{ getCategoryStyles(index).desc }}</p>
          <span :class="['text-sm font-semibold', getCategoryStyles(index).textColor]">{{ category.products_count || 0 }} Products →</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  }
});

const getCategoryStyles = (index) => {
  const styles = [
    { icon: '🏃', iconBg: 'bg-electric/20', textColor: 'text-electric-light', gradient: 'from-electric to-blue-900', desc: 'Sepatu lari yang ringan dan responsif for performance.' },
    { icon: '👟', iconBg: 'bg-neon/20', textColor: 'text-neon-light', gradient: 'from-neon to-emerald-900', desc: 'Gaya sehari-hari yang nyaman dan stylish.' },
    { icon: '🏀', iconBg: 'bg-amber/20', textColor: 'text-amber-light', gradient: 'from-amber to-orange-900', desc: 'Performa tinggi di lapangan dengan support maksimal.' },
    { icon: '💪', iconBg: 'bg-red-500/20', textColor: 'text-red-400', gradient: 'from-red-500 to-red-900', desc: 'Fleksibel dan stabil untuk latihan intense.' },
    { icon: '✨', iconBg: 'bg-purple-500/20', textColor: 'text-purple-400', gradient: 'from-purple-500 to-purple-900', desc: 'Sneaker kasual untuk aktivitas santai.' },
    { icon: '💎', iconBg: 'bg-pink-500/20', textColor: 'text-pink-400', gradient: 'from-pink-500 to-pink-900', desc: 'Koleksi eksklusif edisi terbatas yang langka.' },
  ];
  return styles[index % styles.length];
};
</script>
