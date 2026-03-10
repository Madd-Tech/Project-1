<template>
    <StockApp :products="products" :admin="admin" />
</template>

<script setup>
import StockApp from '../../Admin/Stock/App.vue';

defineProps({
    products: {
        type: Object,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
});
</script>
