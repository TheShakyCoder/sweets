<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();
const navLinks = page.props.site.nav_links;

defineProps({
    competitions: { type: Array,   required: true },
    canLogin:     { type: Boolean },
    canRegister:  { type: Boolean },
});

const statusLabel = {
    open:    { text: 'Open',    cls: 'bg-emerald-100 text-emerald-700' },
    closed:  { text: 'Closed',  cls: 'bg-warm-100 text-warm-600' },
    results: { text: 'Results', cls: 'bg-brand-100 text-brand-700' },
};
</script>

<template>
    <Head title="Competitions — Acme Sweets" />

    <div class="font-sans antialiased text-warm-800 bg-white">

        <Header :navLinks="navLinks" :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-brand-600 via-brand-500 to-brand-700 text-white">
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-brand-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-brand-800/35 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-[-40px] top-1/2 w-72 h-72 pointer-events-none select-none"
                 style="transform: translateY(-50%) rotate(15deg);">
                <img src="/media/logo.png" alt="" class="w-full h-full object-contain opacity-20" />
            </div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <nav class="flex items-center gap-2 text-white/50 text-xs mb-5">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/80">Competitions</span>
                </nav>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">Competitions</h1>
                <p class="text-white/75 text-lg max-w-xl leading-relaxed">
                    Enter our community competitions and show off your talent.
                </p>
            </div>
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 48L60 40C120 32 240 16 360 12C480 8 600 16 720 20C840 24 960 24 1080 20C1200 16 1320 8 1380 4L1440 0V48H0Z" fill="white"/>
                </svg>
            </div>
        </section>

        <!-- Competitions grid -->
        <section class="py-16 lg:py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div v-if="!competitions.length"
                     class="text-center py-24 px-6 bg-warm-50 rounded-2xl border border-warm-200">
                    <span class="text-5xl block mb-4">🏆</span>
                    <h2 class="font-display text-xl font-semibold text-warm-800 mb-2">No competitions yet</h2>
                    <p class="text-warm-500 text-sm">Check back soon — competitions will be posted here.</p>
                </div>

                <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Link v-for="competition in competitions" :key="competition.id"
                          :href="`/competitions/${competition.slug}`"
                          class="group flex flex-col border border-warm-200 rounded-2xl overflow-hidden hover:border-brand-300 hover:shadow-lg transition-all duration-200">

                        <!-- Thumbnail -->
                        <div class="aspect-video bg-warm-100 overflow-hidden">
                            <img v-if="competition.thumbnail_url"
                                 :src="competition.thumbnail_url" :alt="competition.title"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-else class="w-full h-full flex items-center justify-center text-5xl">🏆</div>
                        </div>

                        <div class="p-6 flex flex-col flex-1 gap-3">
                            <div class="flex items-start justify-between gap-3">
                                <h2 class="font-semibold text-warm-900 leading-snug group-hover:text-brand-700 transition-colors">
                                    {{ competition.title }}
                                </h2>
                                <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold"
                                      :class="statusLabel[competition.status].cls">
                                    {{ statusLabel[competition.status].text }}
                                </span>
                            </div>
                            <p v-if="competition.description" class="text-sm text-warm-500 leading-relaxed flex-1">
                                {{ competition.description }}
                            </p>
                            <div class="flex items-center gap-1 text-xs font-semibold text-brand-600 mt-auto">
                                {{ competition.status === 'results' ? 'View results' : 'View &amp; enter' }}
                                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-150"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <Footer :navLinks="navLinks" />
    </div>
</template>
