<script setup>
defineProps({
    site: Object
})
</script>

<template>
    <section id="contact" class="py-20 bg-warm-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-3 block">Find Us</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-warm-900">Come and say <span class="text-gradient-candy">hello</span></h2>
                <p class="text-warm-500 mt-4 max-w-xl mx-auto">Pop in for a browse, call ahead, or drop us a message on social media. We'd love to see you!</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact info -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">&#128205;</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Address</p>
                            <p class="text-warm-500 text-sm mt-0.5" v-html="site.fullname + '<br>' + site.address"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">&#128222;</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Phone</p>
                            <p class="text-warm-500 text-sm mt-0.5">{{ site.telephone }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">&#9993;&#65039;</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Email</p>
                            <p class="text-warm-500 text-sm mt-0.5">{{ site.email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">&#128336;</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Opening Hours</p>
                            <p class="text-warm-500 text-sm mt-0.5" v-html="site.opening_times"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">
                            <svg class="w-5 h-5 text-brand-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Follow Us</p>
                            <div class="flex gap-3 mt-2">
                                <a :href="site.social?.instagram" target="_blank" rel="noopener"
                                   class="text-sm font-semibold text-brand-500 hover:text-brand-700 transition-colors">
                                    Instagram
                                </a>
                                <a :href="site.social?.facebook" target="_blank" rel="noopener"
                                   class="text-sm font-semibold text-[#1877F2] hover:text-blue-700 transition-colors">
                                    Facebook
                                </a>
                                <a :href="site.social?.tiktok" target="_blank" rel="noopener"
                                   class="text-sm font-semibold text-warm-700 hover:text-warm-900 transition-colors">
                                    TikTok
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact form -->
                <form class="bg-white rounded-2xl border border-warm-200 p-7 space-y-5 shadow-sm">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-warm-700 mb-1.5">First name</label>
                            <input type="text" placeholder="Jane"
                                class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-warm-700 mb-1.5">Last name</label>
                            <input type="text" placeholder="Smith"
                                class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Email</label>
                        <input type="email" placeholder="jane@example.com"
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">What's it about?</label>
                        <select
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition text-warm-600">
                            <option>General enquiry</option>
                            <option>Event / trailer hire</option>
                            <option>Party orders</option>
                            <option>Sweet hampers &amp; gifts</option>
                            <option>Wedding sweets</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Message</label>
                        <textarea rows="4" placeholder="Tell us what you're looking for..."
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-3.5 text-white font-bold rounded-xl hover:opacity-90 transition-all text-sm shadow-lg" style="background: linear-gradient(135deg, #ff1a75, #d946ef)">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
