<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
});
</script>

<template>
    <div class="min-h-screen flex font-sans antialiased">

        <!-- ── Left brand panel ── -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-5/12 flex-col bg-gradient-to-br from-brand-600 via-brand-500 to-brand-700 text-white relative overflow-hidden">

            <!-- Decorative blobs -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-brand-500/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-16 w-80 h-80 bg-brand-800/40 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Back to site -->
            <div class="relative px-10 pt-10">
                <Link href="/" class="inline-flex items-center gap-2 text-white/60 hover:text-white text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to website
                </Link>
            </div>

            <!-- Centre content -->
            <div class="relative flex-1 flex flex-col justify-center px-10 xl:px-14">
                <Link href="/" class="flex items-center gap-4 mb-10">
                    <img src="/media/logo.png" alt="Acme Sweets Logo" class="h-16 w-auto brightness-0 invert opacity-90" />
                    <div>
                        <p class="font-display font-bold text-lg leading-tight">Acme Sweets</p>
                    </div>
                </Link>

                <h1 class="font-display text-3xl xl:text-4xl font-bold leading-tight mb-4">
                    Portal
                </h1>
                <p class="text-white/75 text-base leading-relaxed mb-10 max-w-sm">
                    Manage your shop, news, events, and more — all in one place.
                </p>

                <!-- Feature bullets -->
                <ul class="space-y-4">
                    <li v-for="item in [
                        { icon: '📅', text: 'Create and manage community events' },
                        { icon: '📰', text: 'Publish news and announcements' },
                        { icon: '👥', text: 'View and manage memberships' },
                        { icon: '📊', text: 'Track activity across the centre' },
                    ]" :key="item.text" class="flex items-center gap-3 text-white/80 text-sm">
                        <span class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center text-base shrink-0">{{ item.icon }}</span>
                        {{ item.text }}
                    </li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="relative px-10 pb-10">
                <p class="text-white/40 text-xs">&copy; {{ new Date().getFullYear() }} Acme Sweets</p>
            </div>
        </div>

        <!-- ── Right form panel ── -->
        <div class="flex-1 flex flex-col bg-gradient-to-br from-warm-50 via-brand-50 to-accent-50">

            <!-- Mobile header -->
            <div class="lg:hidden flex items-center justify-between px-6 pt-6 pb-4">
                <Link href="/" class="flex items-center gap-3">
                    <img src="/media/logo.png" alt="Acme Sweets Logo" class="h-10 w-auto" />
                    <div>
                        <p class="font-display font-semibold text-brand-600 text-sm leading-tight">Acme Sweets</p>
                    </div>
                </Link>
                <Link href="/" class="text-xs text-warm-500 hover:text-brand-600 transition-colors font-medium">
                    Back to site
                </Link>
            </div>

            <!-- Form area -->
            <div class="flex-1 flex items-center justify-center px-6 py-10 sm:px-10">
                <div class="w-full max-w-md">

                    <!-- Heading slot (title/subtitle set per page) -->
                    <div class="mb-8">
                        <h2 class="font-display text-2xl font-bold text-warm-900">{{ title }}</h2>
                        <p v-if="subtitle" class="text-warm-500 text-sm mt-1">{{ subtitle }}</p>
                    </div>

                    <slot />
                </div>
            </div>
        </div>

    </div>
</template>
