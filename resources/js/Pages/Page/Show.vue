<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { marked } from 'marked';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const sitePage = usePage();
const navLinks = sitePage.props.site.nav_links;

const props = defineProps({
    page:        { type: Object, required: true },
    canLogin:    { type: Boolean },
    canRegister: { type: Boolean },
});
</script>

<template>
    <Head
        :title="`${page.title} — Woodvale &amp; Ainsdale Community Centre`"
        :description="page.description"
    />

    <div class="font-sans antialiased text-warm-800 bg-white">

        <Header :navLinks="navLinks" :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero banner -->
        <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white">
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-brand-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-brand-800/35 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-[-40px] top-1/2 w-72 h-72 pointer-events-none select-none"
                 style="transform: translateY(-50%) rotate(15deg);">
                <img src="/media/logo.png" alt="" class="w-full h-full object-contain opacity-20" />
            </div>

            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-white/50 text-xs mb-5">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/80 truncate max-w-xs">{{ page.title }}</span>
                </nav>

                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight">
                    {{ page.title }}
                </h1>
            </div>

            <!-- Wave divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 48L60 40C120 32 240 16 360 12C480 8 600 16 720 20C840 24 960 24 1080 20C1200 16 1320 8 1380 4L1440 0V48H0Z" fill="white"/>
                </svg>
            </div>
        </section>

        <!-- Page body -->
        <section class="py-16 lg:py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-[1fr_300px] lg:gap-12 xl:gap-16">

                    <!-- Main content -->
                    <div class="prose prose-lg max-w-none
                                prose-headings:font-display prose-headings:text-warm-900
                                prose-p:text-warm-700 prose-p:leading-relaxed
                                prose-a:text-brand-600 prose-a:no-underline hover:prose-a:underline
                                prose-strong:text-warm-900 prose-ul:text-warm-700 prose-ol:text-warm-700"
                         v-html="marked.parse(page.content ?? '')">
                    </div>

                    <!-- Sidebar thumbnail -->
                    <aside v-if="page.thumbnail_url" class="mt-10 lg:mt-0">
                        <div class="sticky top-8">
                            <img :src="page.thumbnail_url" :alt="page.title"
                                 class="w-full rounded-2xl shadow-md object-cover" />
                        </div>
                    </aside>

                </div>
            </div>
        </section>

        <Footer :navLinks="navLinks" />

    </div>
</template>
