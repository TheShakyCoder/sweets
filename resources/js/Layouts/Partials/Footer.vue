<script setup>
import { usePage } from '@inertiajs/vue3';

defineProps({
    navLinks: { type: Array },
});

const page = usePage();
</script>

<template>
    <footer class="bg-warm-900 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid md:grid-cols-4 gap-10 mb-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/media/logo.png" alt="WACA Logo" class="h-14 w-auto brightness-0 invert opacity-90" />
                        <div>
                            <p class="font-semibold text-white font-display">Woodvale &amp; Ainsdale</p>
                            <p class="text-xs text-white/50">Community Association</p>
                        </div>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed max-w-xs">
                        A registered charity serving the communities of Woodvale and Ainsdale, Southport since 1993.
                    </p>
                    <p class="text-white/40 text-xs mt-3">Registered Charity No. {{ page.props.site.charity_number }}</p>
                </div>

                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-4">Quick Links</p>
                    <ul class="space-y-2">
                        <li v-for="link in navLinks" :key="link.label">
                            <a :href="link.href" class="text-white/60 text-sm hover:text-white transition-colors">{{
                                link.label }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-4">Get Involved</p>
                    <ul class="space-y-2 text-sm text-white/60 mb-6">
                        <li><a href="#" class="hover:text-white transition-colors">Volunteer with us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Make a donation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Hire our venue</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Join our mailing list</a></li>
                    </ul>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-3">Follow Us</p>
                    <div class="flex gap-3">
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors text-sm">f</a>
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors text-sm">in</a>
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors text-sm">tw</a>
                    </div>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/40">
                <p>&copy; Fig Limited {{ new Date().getFullYear() }}. All rights reserved.
                </p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-white/70 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white/70 transition-colors">Terms of Use</a>
                    <a href="#" class="hover:text-white/70 transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
</template>