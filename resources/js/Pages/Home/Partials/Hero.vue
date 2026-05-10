<script setup>
defineProps({
    site: { type: Object },
});
</script>

<template>
    <section id="home" class="relative overflow-hidden min-h-[90vh] flex items-center"
        style="background: linear-gradient(160deg, #ff1a75 0%, #d946ef 45%, #8b5cf6 100%);">

        <!-- ── Animated background blobs ── -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Morphing blob top-right -->
            <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-white/10 hero-blob"></div>
            <!-- Warm glow bottom-left -->
            <div class="absolute -bottom-40 -left-20 w-[400px] h-[400px] rounded-full bg-candy-orange/20 blur-3xl"></div>
            <!-- Yellow accent centre -->
            <div class="absolute top-1/3 left-1/3 w-[300px] h-[300px] rounded-full bg-accent-300/10 blur-3xl"></div>
            <!-- Subtle grid overlay for texture -->
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>

        <!-- ── Floating candy icons ── -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
            <span class="absolute top-[8%] left-[3%] text-7xl animate-float opacity-30 rotate-12">🍭</span>
            <span class="absolute top-[12%] right-[8%] text-6xl animate-float-delayed opacity-25 -rotate-12">🍬</span>
            <span class="absolute bottom-[20%] left-[8%] text-5xl animate-float-slow opacity-20 rotate-6">🍫</span>
            <span class="absolute top-[55%] right-[3%] text-6xl animate-float opacity-20 -rotate-45">🌈</span>
            <span class="absolute bottom-[30%] right-[15%] text-5xl animate-float-delayed opacity-25 rotate-12">⭐</span>
            <span class="absolute top-[30%] left-[12%] text-4xl animate-wiggle opacity-15">🍡</span>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 lg:py-28 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">

                <!-- ── Left: Copy ── -->
                <div class="text-white">
                    <!-- Pill badge -->
                    <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-5 py-2 rounded-full text-sm font-semibold text-white mb-8 border border-white/20 shadow-lg shadow-black/5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-accent-300"></span>
                        </span>
                        Penwortham's Sweetest Shop
                    </span>

                    <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.05] mb-8 tracking-tight">
                        Life is
                        <span class="relative inline-block">
                            <span class="relative z-10">Sweet</span>
                            <span class="absolute -bottom-1 left-0 right-0 h-4 bg-accent-300/40 rounded-full -skew-x-3 z-0"></span>
                        </span>
                        <br />at <span class="text-accent-300 drop-shadow-lg">Lollipops</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-white/85 leading-relaxed mb-10 max-w-xl font-medium">
                        The biggest self-serve pick 'n' mix in the North West — plus American candy,
                        retro sweets, and olde world favourites all under one roof.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#sweets"
                            class="group inline-flex items-center gap-2 px-8 py-4 bg-white text-brand-600 font-bold rounded-2xl hover:bg-accent-300 hover:text-warm-900 transition-all duration-200 shadow-xl shadow-black/10 text-sm">
                            Explore Our Sweets
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#contact"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition-all duration-200 border border-white/25 text-sm">
                            📍 Visit Us
                        </a>
                    </div>

                    <!-- Social proof -->
                    <div class="flex items-center gap-6 mt-12 pt-8 border-t border-white/15">
                        <div>
                            <p class="font-display text-2xl font-extrabold text-accent-300">90+</p>
                            <p class="text-xs text-white/60 font-medium">Pick 'n' mix varieties</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div>
                            <p class="font-display text-2xl font-extrabold text-accent-300">5 ⭐</p>
                            <p class="text-xs text-white/60 font-medium">Google rating</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div>
                            <p class="font-display text-2xl font-extrabold text-accent-300">1000s</p>
                            <p class="text-xs text-white/60 font-medium">Happy customers</p>
                        </div>
                    </div>
                </div>

                <!-- ── Right: Candy visual cluster ── -->
                <div class="hidden lg:flex items-center justify-center relative">
                    <!-- Glowing backdrop circle -->
                    <div class="absolute w-[420px] h-[420px] rounded-full bg-white/10 blur-2xl animate-scale-pulse"></div>

                    <!-- Central candy arrangement -->
                    <div class="relative w-[450px] h-[450px]">
                        <!-- Main centre lollipop -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-[160px] drop-shadow-2xl animate-float filter">🍭</span>
                        </div>

                        <!-- Orbiting candy -->
                        <span class="absolute top-[5%] left-[15%] text-7xl animate-float-delayed drop-shadow-lg">🍬</span>
                        <span class="absolute top-[10%] right-[10%] text-6xl animate-float drop-shadow-lg">🍫</span>
                        <span class="absolute bottom-[15%] left-[5%] text-6xl animate-float-slow drop-shadow-lg">🍡</span>
                        <span class="absolute bottom-[8%] right-[12%] text-7xl animate-float-delayed drop-shadow-lg">🧁</span>
                        <span class="absolute top-[45%] right-[0%] text-5xl animate-wiggle drop-shadow-lg">⭐</span>
                        <span class="absolute top-[40%] left-[0%] text-5xl animate-float drop-shadow-lg">🌈</span>

                        <!-- Colourful accent dots -->
                        <div class="absolute top-[25%] right-[25%] w-5 h-5 rounded-full bg-accent-300 animate-scale-pulse shadow-lg shadow-accent-300/50"></div>
                        <div class="absolute bottom-[30%] left-[20%] w-4 h-4 rounded-full bg-candy-blue animate-float shadow-lg shadow-candy-blue/50"></div>
                        <div class="absolute top-[60%] right-[5%] w-3 h-3 rounded-full bg-candy-green animate-wiggle shadow-lg shadow-candy-green/50"></div>
                        <div class="absolute top-[5%] left-[45%] w-4 h-4 rounded-full bg-candy-yellow animate-float-delayed shadow-lg shadow-candy-yellow/50"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Wave divider ── -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full" preserveAspectRatio="none">
                <path d="M0 80L48 68C96 56 192 32 288 24C384 16 480 24 576 32C672 40 768 48 864 48C960 48 1056 40 1152 32C1248 24 1344 16 1392 12L1440 8V80H1392C1344 80 1248 80 1152 80C1056 80 960 80 864 80C768 80 672 80 576 80C480 80 384 80 288 80C192 80 96 80 48 80H0Z"
                    fill="white"/>
            </svg>
        </div>
    </section>
</template>
