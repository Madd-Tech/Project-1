<template>
  <section class="py-20 sm:py-28 relative overflow-hidden" id="testimonial-section">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-amber/5 rounded-full blur-[200px]"></div>
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 text-xs font-semibold tracking-widest uppercase text-amber bg-amber/10 rounded-full mb-4">Ulasan Pilihan</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[Outfit] font-bold text-white mb-4">
          Apa Kata <span class="gradient-text">Pelanggan</span>
        </h2>
      </div>

      <div v-if="duplicatedTestimonials.length > 0" class="relative">
        <!-- Fading Edges Mask -->
        <div class="absolute top-0 left-0 bottom-0 w-16 sm:w-48 bg-gradient-to-r from-dark-900 to-transparent pointer-events-none z-10 opacity-70"></div>
        <div class="absolute top-0 right-0 bottom-0 w-16 sm:w-48 bg-gradient-to-l from-dark-900 to-transparent pointer-events-none z-10 opacity-70"></div>

        <!-- Marquee Container -->
        <div 
          class="flex gap-6 overflow-x-auto hide-scrollbar pb-8 cursor-grab active:cursor-grabbing select-none" 
          ref="scrollContainer"
          @mouseenter="mouseEnter"
          @mouseleave="mouseLeave"
          @touchstart="touchStart"
          @touchend="touchEnd"
          @mousedown="startDrag"
          @mousemove="onDrag"
          @mouseup="endDrag"
        >
          <!-- Set 1 -->
          <div class="flex gap-6 shrink-0" ref="scrollContent">
            <div
              v-for="(testimonial, index) in duplicatedTestimonials"
              :key="`set1-${testimonial.id}-${index}`"
              class="glass-card rounded-3xl p-8 shrink-0 w-[85vw] md:w-[400px] flex flex-col pointer-events-none"
            >
              <!-- Stars -->
              <div class="flex items-center gap-1 mb-4">
                <svg 
                  v-for="star in 5" 
                  :key="star" 
                  :class="star <= Math.round(testimonial.rating || 0) ? 'text-amber' : 'text-gray-700'" 
                  class="w-4 h-4 transition-colors" 
                  fill="currentColor" viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <p class="text-gray-300 leading-relaxed mb-6 italic line-clamp-4">"{{ testimonial.comment }}"</p>
              <div class="flex items-center gap-4 mt-auto">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-electric to-neon flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                  {{ testimonial.reviewer_name ? testimonial.reviewer_name.charAt(0).toUpperCase() : 'U' }}
                </div>
                <div class="overflow-hidden">
                  <div class="text-white font-semibold truncate">{{ testimonial.reviewer_name }}</div>
                  <div class="text-gray-500 text-xs truncate">
                     Produk: <span class="text-neon">{{ testimonial.product ? testimonial.product.name : 'Produk' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Set 2 -->
          <div class="flex gap-6 shrink-0">
            <div
              v-for="(testimonial, index) in duplicatedTestimonials"
              :key="`set2-${testimonial.id}-${index}`"
              class="glass-card rounded-3xl p-8 shrink-0 w-[85vw] md:w-[400px] flex flex-col pointer-events-none"
            >
              <!-- Stars -->
              <div class="flex items-center gap-1 mb-4">
                <svg 
                  v-for="star in 5" 
                  :key="star" 
                  :class="star <= Math.round(testimonial.rating || 0) ? 'text-amber' : 'text-gray-700'" 
                  class="w-4 h-4 transition-colors" 
                  fill="currentColor" viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <p class="text-gray-300 leading-relaxed mb-6 italic line-clamp-4">"{{ testimonial.comment }}"</p>
              <div class="flex items-center gap-4 mt-auto">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-electric to-neon flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                  {{ testimonial.reviewer_name ? testimonial.reviewer_name.charAt(0).toUpperCase() : 'U' }}
                </div>
                <div class="overflow-hidden">
                  <div class="text-white font-semibold truncate">{{ testimonial.reviewer_name }}</div>
                  <div class="text-gray-500 text-xs truncate">
                     Produk: <span class="text-neon">{{ testimonial.product ? testimonial.product.name : 'Produk' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-12 text-gray-500">
        Belum ada ulasan yang tersedia.
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  testimonials: {
    type: Array,
    default: () => []
  }
});

// Calculate duplicate amounts based on available entries
const duplicatedTestimonials = computed(() => {
  if (!props.testimonials || props.testimonials.length === 0) return [];
  // At least duplicate up to 8 items to ensure marquee continuity on large screens
  const minimalTotalItems = 8;
  const multiplier = Math.max(1, Math.ceil(minimalTotalItems / props.testimonials.length));
  
  const result = [];
  for (let i = 0; i < multiplier; i++) {
    result.push(...props.testimonials);
  }
  return result;
});

const scrollContainer = ref(null);
const scrollContent = ref(null);
let animationFrame = null;
const isHoveringOrDragging = ref(false);

const mouseEnter = () => { isHoveringOrDragging.value = true; };
const mouseLeave = () => {
  isHoveringOrDragging.value = false;
  endDrag();
};
const touchStart = () => { isHoveringOrDragging.value = true; };
const touchEnd = () => { isHoveringOrDragging.value = false; };

// Custom Drag-to-Scroll implementation
let isDragging = false;
let startX = 0;
let scrollLeft = 0;

const startDrag = (e) => {
  if (!scrollContainer.value) return;
  isDragging = true;
  isHoveringOrDragging.value = true;
  startX = e.pageX - scrollContainer.value.offsetLeft;
  scrollLeft = scrollContainer.value.scrollLeft;
};

const onDrag = (e) => {
  if (!isDragging || !scrollContainer.value) return;
  e.preventDefault();
  const x = e.pageX - scrollContainer.value.offsetLeft;
  const walk = (x - startX) * 2; // Scroll fast adjustment
  scrollContainer.value.scrollLeft = scrollLeft - walk;
};

const endDrag = () => {
  isDragging = false;
};

const autoScroll = () => {
  if (scrollContainer.value && scrollContent.value && !isHoveringOrDragging.value && !isDragging) {
    scrollContainer.value.scrollLeft += 1; // Scroll speed
    
    // reset point: width of the first set + 24px gap between the two sets (gap-6 = 1.5rem = 24px)
    const resetPoint = scrollContent.value.offsetWidth + 24; 
    
    // Infinite loop jump visually seamlessly
    if (scrollContainer.value.scrollLeft >= resetPoint) {
      scrollContainer.value.scrollLeft -= resetPoint;
    }
  }
  animationFrame = requestAnimationFrame(autoScroll);
};

onMounted(() => {
  if (duplicatedTestimonials.value.length > 0) {
    animationFrame = requestAnimationFrame(autoScroll);
  }
});

onUnmounted(() => {
  if (animationFrame) cancelAnimationFrame(animationFrame);
});
</script>

<style scoped>
/* Hide scrollbar completely to look like a pure marquee, it still works by dragging */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
