<template>
    <OrdersApp :orders="orders" :filters="filters" :admin="admin" />
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
});
</script>
