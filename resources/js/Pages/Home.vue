<template>
  <div class="min-h-screen bg-dark-900">
    <NavBar :filters="filters" />
    <HeroSection />
    <BrandMarquee />
    <ProductSection :products="products" :categories="categories" :filters="filters" />
    <CategorySection :categories="categories" />
    <TestimonialSection :testimonials="testimonials" />
    <AboutSection />
    <FaqSection />
    <FooterSection />
  </div>
</template>

<script setup>
import NavBar from './Components/NavBar.vue';
import HeroSection from './Components/HeroSection.vue';
import BrandMarquee from './Components/BrandMarquee.vue';
import ProductSection from './Components/ProductSection.vue';
import CategorySection from './Components/CategorySection.vue';
import TestimonialSection from './Components/TestimonialSection.vue';
import AboutSection from './Components/AboutSection.vue';
import FaqSection from './Components/FaqSection.vue';
import FooterSection from './Components/FooterSection.vue';

defineProps({
  products: Object,
  categories: Array,
  testimonials: Array,
  filters: Object
});
</script>