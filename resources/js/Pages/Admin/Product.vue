<template>
    <ProductApp :products="products" :categories="categories" :admin="admin" />
</template>

<script setup>
import ProductApp from '../../Admin/Product/App.vue';

defineProps({
    products: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
});
</script>
