<template>
  <div class="min-h-screen bg-dark-900">
    <NavBar :filters="filters" />
    <HeroSection />
    <BrandMarquee />
    <ProductSection :products="products" :categories="categories" :filters="filters" />
    <CategorySection :categories="categories" />
    <!-- <PromoSection /> -->
    <FeatureSection />
    <!-- <TestimonialSection /> -->
    <!-- <NewsletterSection /> -->
    <FooterSection />
  </div>
</template>

<script setup>
import NavBar from './Components/NavBar.vue';
import HeroSection from './Components/HeroSection.vue';
import BrandMarquee from './Components/BrandMarquee.vue';
import ProductSection from './Components/ProductSection.vue';
import CategorySection from './Components/CategorySection.vue';
import PromoSection from './Components/PromoSection.vue';
import FeatureSection from './Components/FeatureSection.vue';
import TestimonialSection from './Components/TestimonialSection.vue';
import NewsletterSection from './Components/NewsletterSection.vue';
import FooterSection from './Components/FooterSection.vue';

defineProps({
  products: Object,
  categories: Array,
  filters: Object
});
</script>