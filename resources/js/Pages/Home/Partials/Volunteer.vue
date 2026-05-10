<script setup>
</script>

<template>
    <section class="relative py-24 overflow-hidden"
        style="background: linear-gradient(135deg, #ff1a75 0%, #d946ef 50%, #8b5cf6 100%);">
        <!-- Decorative blobs -->
        <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 rounded-full bg-accent-300/15 blur-3xl"></div>

        <!-- Floating candy -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
            <span class="absolute top-[10%] left-[5%] text-7xl opacity-15 animate-float rotate-12">🍭</span>
            <span class="absolute bottom-[10%] right-[8%] text-7xl opacity-15 animate-float-delayed -rotate-12">🍬</span>
            <span class="absolute top-[50%] left-[50%] text-9xl opacity-[0.06] animate-scale-pulse">🎉</span>
        </div>

        <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-semibold text-white/90 mb-6 border border-white/20 uppercase tracking-widest">
                Weddings &amp; Events
            </span>
            <h2 class="font-display text-3xl sm:text-5xl font-extrabold text-white mb-6 leading-tight">
                Make your event <span class="text-accent-300">unforgettable</span>
            </h2>
            <p class="text-white/80 text-lg max-w-2xl mx-auto mb-12 leading-relaxed">
                From stunning sweet graze tables to our mobile pick 'n' mix trailer — we bring the
                sweetness to your special occasion. Sweet cones, party pouches, and hampers made to order.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#contact"
                    class="group px-8 py-4 bg-white text-brand-600 font-bold rounded-2xl hover:bg-accent-300 hover:text-warm-900 transition-all duration-200 shadow-xl shadow-black/10 text-sm inline-flex items-center gap-2">
                    Get a Quote
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="https://www.instagram.com/lolipops.penwortham/" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition-all duration-200 border border-white/25 text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    See Our Work
                </a>
            </div>
        </div>
    </section>
</template>
