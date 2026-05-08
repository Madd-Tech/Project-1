<template>
    <StockMovApp :movements="movements" :stats="stats" :admin="admin" />
</template>

<script setup>
import StockMovApp from '../../Admin/StockMov/App.vue';

defineProps({
    movements: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, masuk: 0, keluar: 0 }),
    },
    admin: {
        type: Object,
        required: true,
    },
});
</script>
