<template>
  <section class="py-8 border-y border-white/5 overflow-hidden">
    <div class="flex gap-16 animate-[scroll_20s_linear_infinite]" style="width: max-content;">
      <span
        v-for="(brand, i) in displayBrands"
        :key="i"
        class="text-xl sm:text-2xl font-[Outfit] font-bold text-gray-600/40 whitespace-nowrap hover:text-gray-400 transition-colors cursor-default"
      >
        {{ brand }}
      </span>
    </div>
  </section>
</template>

<script setup>
const brands = ['NIKE', 'ADIDAS', 'PUMA', 'NEW BALANCE', 'CONVERSE', 'REEBOK', 'VANS', 'ASICS'];
const displayBrands = [...brands, ...brands];
</script>
