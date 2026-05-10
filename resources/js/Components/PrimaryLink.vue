<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
});
</script>

<template>
    <Link :href="href"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition-colors">
        <slot />
    </Link>
</template>