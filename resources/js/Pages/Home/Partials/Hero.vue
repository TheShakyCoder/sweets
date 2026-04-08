<script setup>
defineProps({
    site: { type: Object },
});
</script>

<template>
    <section id="home"
        class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white">
        <!-- Decorative circles -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 bg-brand-800/40 rounded-full blur-3xl"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-2xl">
        </div>

        <!-- Faint background logo -->
        <div class="absolute right-[-60px] top-1/2 -translate-y-1/2 w-[520px] h-[520px] pointer-events-none select-none"
             style="transform: translateY(-50%) rotate(15deg);">
            <img src="/media/logo.png" alt=""
                 class="w-full h-full object-contain opacity-20 md:opacity-30" />
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
            <div class="max-w-3xl">
                <span
                    class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium text-white/90 mb-6 border border-white/20">
                    <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                    {{ site.fullname }}
                </span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    In the Hands of<br />
                    <span class="text-green-300">People who Care</span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 leading-relaxed mb-10 max-w-2xl">
                    A warm and welcoming hub where neighbours become friends.
                    We've been at the heart of Woodvale and Ainsdale for over 30 years,
                    running events, services, and support for everyone.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#events"
                        class="inline-flex items-center gap-2 px-7 py-3.5 bg-white text-brand-700 font-semibold rounded-xl hover:bg-brand-50 transition-colors shadow-lg text-sm">
                        Upcoming Events
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="#contact"
                        class="inline-flex items-center gap-2 px-7 py-3.5 bg-white/15 backdrop-blur-sm text-white font-semibold rounded-xl hover:bg-white/25 transition-colors border border-white/30 text-sm">
                        Get In Touch
                    </a>
                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path
                    d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H1380C1320 60 1200 60 1080 60C960 60 840 60 720 60C600 60 480 60 360 60C240 60 120 60 60 60H0Z"
                    fill="white" />
            </svg>
        </div>
    </section>
</template>