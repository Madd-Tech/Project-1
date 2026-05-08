<template>
  <section class="py-10 border-y border-white/5 overflow-hidden select-none space-y-4">

    <!-- Row 1: scrolls left -->
    <div class="marquee-track">
      <div class="marquee-content animate-marquee-left">
        <span
          v-for="(brand, i) in [...brands, ...brands]"
          :key="'a' + i"
          class="brand-item"
        >
          <span class="dot">✦</span>
          {{ brand }}
        </span>
      </div>
    </div>

    <!-- Row 2: scrolls right -->
    <div class="marquee-track">
      <div class="marquee-content animate-marquee-right">
        <span
          v-for="(brand, i) in [...brandsReversed, ...brandsReversed]"
          :key="'b' + i"
          class="brand-item"
        >
          <span class="dot">✦</span>
          {{ brand }}
        </span>
      </div>
    </div>

  </section>
</template>

<script setup>
const brands = ['NIKE', 'ADIDAS', 'PUMA', 'NEW BALANCE', 'CONVERSE', 'REEBOK', 'VANS', 'ASICS', 'JORDAN', 'SKECHERS'];
const brandsReversed = [...brands].reverse();
</script>

<style scoped>
.marquee-track {
  overflow: hidden;
  width: 100%;
}

.marquee-content {
  display: flex;
  gap: 3rem;
  width: max-content;
  will-change: transform;
}

.brand-item {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: 'Outfit', sans-serif;
  font-weight: 700;
  font-size: 1.25rem;
  color: rgba(156, 163, 175, 0.25);
  white-space: nowrap;
  cursor: default;
  transition: color 0.3s ease;
}

.brand-item:hover {
  color: rgba(156, 163, 175, 0.75);
}

.dot {
  font-size: 0.5rem;
  color: rgba(99, 102, 241, 0.4);
}

/* Scroll left (default direction) */
.animate-marquee-left {
  animation: marquee-left 25s linear infinite;
}

/* Scroll right (reverse) */
.animate-marquee-right {
  animation: marquee-right 25s linear infinite;
}

@keyframes marquee-left {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

@keyframes marquee-right {
  0%   { transform: translateX(-50%); }
  100% { transform: translateX(0); }
}

/* Pause on hover */
.marquee-track:hover .animate-marquee-left,
.marquee-track:hover .animate-marquee-right {
  animation-play-state: paused;
}
</style>
