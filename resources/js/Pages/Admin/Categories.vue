<template>
    <CategoryApp :categories="categories" :admin="admin" />
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
});
</script>
