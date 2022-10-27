<template>
  <Link :href="link" class="text-white px-4 py-2 rounded-lg">
    <slot />
  </Link>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
  link: {
    type: String,
    required: true,
  },
});
</script>
