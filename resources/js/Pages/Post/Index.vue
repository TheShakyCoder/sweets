<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const props = defineProps({
    posts:       { type: Object, required: true },
    canLogin:    { type: Boolean },
    canRegister: { type: Boolean },
});

const page = usePage();

const navLinks = page.props.site.nav_links;

const tagColours = [
    'bg-brand-100 text-brand-700',
    'bg-sky-100 text-sky-700',
    'bg-purple-100 text-purple-700',
    'bg-amber-100 text-amber-700',
    'bg-rose-100 text-rose-700',
];

// Gives each post a consistent colour based on its position
function tagColour(index) {
    return tagColours[index % tagColours.length];
}

</script>

<template>
    <Head title="News &amp; Updates — Acme Sweets" />

    <div class="font-sans antialiased text-warm-800 bg-white">

        <Header :navLinks="navLinks" :canLogin="canLogin" :canRegister="canRegister" />

        <!-- ── PAGE HERO BANNER ── -->
        <section class="relative overflow-hidden bg-gradient-to-br from-brand-600 via-brand-500 to-brand-700 text-white">
            <!-- Decorative blobs -->
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-brand-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-brand-800/35 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Faint background logo -->
            <div class="absolute right-[-40px] top-1/2 w-72 h-72 pointer-events-none select-none"
                 style="transform: translateY(-50%) rotate(15deg);">
                <img src="/media/logo.png" alt="" class="w-full h-full object-contain opacity-20" />
            </div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-white/50 text-xs mb-5">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/80">News &amp; Updates</span>
                </nav>

                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                    News &amp; Updates
                </h1>
                <p class="text-white/75 text-lg max-w-xl leading-relaxed">
                    The latest stories, announcements, and news from Acme Sweets.
                </p>
            </div>

            <!-- Wave divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 48L60 40C120 32 240 16 360 12C480 8 600 16 720 20C840 24 960 24 1080 20C1200 16 1320 8 1380 4L1440 0V48H0Z" fill="white"/>
                </svg>
            </div>
        </section>

        <!-- ── POST GRID ── -->
        <section class="py-16 lg:py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Results count -->
                <p v-if="props.posts.total > 0" class="text-sm text-warm-400 mb-10">
                    Showing {{ props.posts.from }}–{{ props.posts.to }} of {{ props.posts.total }} articles
                </p>

                <!-- Empty state -->
                <div v-if="props.posts.data.length === 0"
                     class="text-center py-24 px-6 bg-warm-50 rounded-2xl border border-warm-200">
                    <span class="text-5xl block mb-4">📭</span>
                    <h2 class="font-display text-xl font-semibold text-warm-800 mb-2">Nothing here yet</h2>
                    <p class="text-warm-500 text-sm mb-6">Check back soon — we'll be posting news and updates regularly.</p>
                    <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                        Back to home
                    </a>
                </div>

                <!-- Post list -->
                <div v-else class="flex flex-col gap-5">
                    <Link v-for="(post, index) in props.posts.data" :key="post.id"
                          :href="`/news-updates/${post.slug}`"
                          class="group flex border border-warm-200 rounded-2xl overflow-hidden hover:border-brand-300 hover:shadow-lg transition-all duration-200">

                        <!-- Thumbnail or colour bar -->
                        <div v-if="post.thumbnail_url"
                             class="w-36 sm:w-48 shrink-0 self-stretch bg-warm-100 overflow-hidden">
                            <img :src="post.thumbnail_url" :alt="post.title"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        </div>
                        <div v-else class="w-1.5 shrink-0"
                             :class="[
                                 index % 5 === 0 ? 'bg-brand-500' :
                                 index % 5 === 1 ? 'bg-sky-500' :
                                 index % 5 === 2 ? 'bg-purple-500' :
                                 index % 5 === 3 ? 'bg-amber-500' : 'bg-rose-500'
                             ]">
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1 p-6">
                            <!-- Date — shown as a column on desktop -->
                            <div class="sm:w-28 sm:shrink-0 sm:text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold" :class="tagColour(index)">
                                    News
                                </span>
                                <time class="block text-xs text-warm-400 font-medium mt-2">{{ post.created_at }}</time>
                            </div>

                            <!-- Vertical divider (desktop only) -->
                            <div class="hidden sm:block w-px self-stretch bg-warm-100"></div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-warm-900 text-base leading-snug mb-2 group-hover:text-brand-700 transition-colors">
                                    {{ post.title }}
                                </h2>
                                <p class="text-sm text-warm-500 leading-relaxed">
                                    {{ post.excerpt }}
                                </p>
                            </div>

                            <!-- Read more -->
                            <div class="sm:shrink-0 flex items-center gap-1 text-xs font-semibold text-brand-600 whitespace-nowrap">
                                Read article
                                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform duration-150"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="props.posts.last_page > 1"
                     class="mt-14 flex items-center justify-center gap-2">

                    <!-- Previous -->
                    <Link v-if="props.posts.prev_page_url" :href="props.posts.prev_page_url"
                          class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-700 border border-warm-200 rounded-xl hover:bg-warm-50 hover:border-warm-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </Link>
                    <span v-else class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-300 border border-warm-100 rounded-xl cursor-default">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </span>

                    <!-- Page numbers -->
                    <div class="flex items-center gap-1">
                        <template v-for="link in props.posts.links.slice(1, -1)" :key="link.label">
                            <Link v-if="link.url" :href="link.url"
                                  class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-medium transition-colors"
                                  :class="link.active
                                      ? 'bg-brand-600 text-white shadow-sm'
                                      : 'text-warm-600 hover:bg-warm-100 border border-warm-200'">
                                {{ link.label }}
                            </Link>
                            <span v-else class="w-9 h-9 flex items-center justify-center text-sm text-warm-300">
                                {{ link.label }}
                            </span>
                        </template>
                    </div>

                    <!-- Next -->
                    <Link v-if="props.posts.next_page_url" :href="props.posts.next_page_url"
                          class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-700 border border-warm-200 rounded-xl hover:bg-warm-50 hover:border-warm-300 transition-colors">
                        Next
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </Link>
                    <span v-else class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-300 border border-warm-100 rounded-xl cursor-default">
                        Next
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>

            </div>
        </section>

        <Footer :navLinks="navLinks" />

    </div>
</template>
