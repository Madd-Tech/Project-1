<template>
    <StockMovApp :movements="movements" :allProducts="allProducts" :admin="admin" />
</template>

<script setup>
import StockMovApp from '../../Admin/StockMov/App.vue';

defineProps({
    movements: {
        type: Object,
        required: true,
    },
    allProducts: {
        type: Array,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
});
</script>
