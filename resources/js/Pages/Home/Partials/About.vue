<script setup>
import PrimaryLink from '@/Components/PrimaryLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <section id="about" class="py-20 bg-warm-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-3 block">About Us</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-warm-900 mb-6 leading-tight">
                        More than just<br />a <span class="text-gradient-candy">sweet shop</span>
                    </h2>
                    <p class="text-warm-600 leading-relaxed mb-4">
                        Acme Sweets is a family-run sweet shop bursting with colour, flavour,
                        and a whole lot of fun. We're passionate about bringing you the best sweets from
                        around the world — from nostalgic retro favourites to the latest American imports.
                    </p>
                    <p class="text-warm-600 leading-relaxed mb-8">
                        Pop in and you'll be greeted with a friendly face and aisles packed with over 90
                        self-serve pick 'n' mix varieties, American candy, sour sweets, chocolate, and
                        olde world treats. Whether you're after a gift, party supplies, or just a cheeky
                        treat for yourself — we've got you covered.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#sweets"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition-colors shadow-sm">
                            See What We Sell
                        </a>
                        <a href="#contact"
                            class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-brand-200 text-brand-600 text-sm font-semibold rounded-xl hover:border-brand-400 transition-colors">
                            Find Us
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl p-6 text-white col-span-2" style="background: linear-gradient(135deg, #ff1a75 0%, #d946ef 50%, #8b5cf6 100%)">
                        <p class="text-2xl font-display font-bold mb-2">"An amazing shop!"</p>
                        <p class="text-white/80 text-sm leading-relaxed">
                            "We got a giant bag of sweets, some choc nibbles and two bags of blue takis.
                            The staff were so lovely — highly recommended! We'll definitely be back."
                        </p>
                        <p class="text-white/60 text-xs mt-3 font-medium">— Happy customer via TikTok</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-warm-200 flex flex-col items-start gap-2">
                        <span class="text-3xl">&#127853;</span>
                        <p class="text-sm font-semibold text-warm-800">Self-Serve Pick 'n' Mix</p>
                        <p class="text-xs text-warm-500">90+ varieties to choose from</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-warm-200 flex flex-col items-start gap-2">
                        <span class="text-3xl">&#127758;</span>
                        <p class="text-sm font-semibold text-warm-800">American Imports</p>
                        <p class="text-xs text-warm-500">The latest from the US</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
