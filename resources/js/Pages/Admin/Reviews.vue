<template>
    <ReviewsApp :reviews="reviews" :filters="filters" :admin="admin" />
</template>

<script setup>
import ReviewsApp from '../../Admin/Reviews/App.vue';

defineProps({
    reviews: {
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
