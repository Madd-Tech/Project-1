<template>
  <CustomersApp :customers="customers" :filters="filters" :admin="admin" :stats="stats" />
</template>

<script setup>
import CustomersApp from '../../Admin/Customers/App.vue';

defineProps({
  customers: {
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
