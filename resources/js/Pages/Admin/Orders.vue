<template>
    <OrdersApp :orders="orders" :filters="filters" :admin="admin" :stats="stats" />
</template>

<script setup>
import OrdersApp from '../../Admin/Orders/App.vue';

defineProps({
    orders: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    admin: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
});
</script>
