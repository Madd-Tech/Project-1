<template>
    <CategoryApp :categories="categories" :admin="admin" :featuredCount="featuredCount" />
</template>

<script setup>
import CategoryApp from '../../Admin/Categories/App.vue';

defineProps({
    categories: {
        type: Array,
        required: true,
    },
    admin: {
        type: Object,
        required: true,
    },
    featuredCount: {
        type: Number,
        default: 0,
    },
});
</script>
