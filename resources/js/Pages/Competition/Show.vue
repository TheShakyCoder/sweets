<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { marked } from 'marked';
import { ref } from 'vue';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();
const navLinks = page.props.site.nav_links;
const auth = page.props.auth;

const props = defineProps({
    competition:    { type: Object,  required: true },
    submissions:    { type: Array,   required: true },
    userSubmission: { type: Object,  default: null },
    canLogin:       { type: Boolean },
    canRegister:    { type: Boolean },
});

const form = useForm({
    name:        '',
    description: '',
    image:       null,
});

const imagePreview = ref(null);

function onFileChange(e) {
    const file = e.target.files[0];
    form.image = file ?? null;
    imagePreview.value = file ? URL.createObjectURL(file) : null;
}

function submit() {
    form.post(`/competitions/${props.competition.slug}/submit`, {
        forceFormData: true,
    });
}

const statusLabel = {
    open:    'Open — accepting entries',
    closed:  'Closed — entries are no longer accepted',
    results: 'Results announced',
};

const winner = props.submissions.find(s => s.is_winner);
const otherSubmissions = props.submissions.filter(s => !s.is_winner);
</script>

<template>
    <Head :title="`${competition.title} — Penwortham Lollipops`"
          :description="competition.description" />

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
            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
                <nav class="flex items-center gap-2 text-white/50 text-xs mb-5">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="/competitions" class="hover:text-white transition-colors">Competitions</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white/80 truncate max-w-xs">{{ competition.title }}</span>
                </nav>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight">
                    {{ competition.title }}
                </h1>
                <p class="mt-3 text-white/70 text-sm">{{ statusLabel[competition.status] }}</p>
            </div>
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 48L60 40C120 32 240 16 360 12C480 8 600 16 720 20C840 24 960 24 1080 20C1200 16 1320 8 1380 4L1440 0V48H0Z" fill="white"/>
                </svg>
            </div>
        </section>

        <section class="py-16 lg:py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-[1fr_360px] lg:gap-12 xl:gap-16 items-start">

                    <!-- Left: details + results -->
                    <div class="space-y-12">

                        <!-- Flash -->
                        <div v-if="page.props.flash.success"
                             class="flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ page.props.flash.success }}
                        </div>

                        <!-- Competition details -->
                        <div v-if="competition.content"
                             class="prose prose-lg max-w-none
                                    prose-headings:font-display prose-headings:text-warm-900
                                    prose-p:text-warm-700 prose-p:leading-relaxed
                                    prose-a:text-brand-600 prose-a:no-underline hover:prose-a:underline
                                    prose-strong:text-warm-900 prose-ul:text-warm-700 prose-ol:text-warm-700"
                             v-html="marked.parse(competition.content)">
                        </div>

                        <!-- ── WINNER SHOWCASE ── -->
                        <div v-if="competition.status === 'results' && winner">
                            <h2 class="font-display text-2xl font-bold text-warm-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                Winner
                            </h2>
                            <div class="rounded-2xl border-2 border-amber-300 overflow-hidden shadow-lg bg-amber-50/30">
                                <img v-if="winner.image_url" :src="winner.image_url" :alt="winner.name"
                                     class="w-full h-auto block" />
                                <div class="p-6">
                                    <p class="font-display text-xl font-bold text-warm-900">{{ winner.name }}</p>
                                    <p v-if="winner.description" class="text-warm-600 mt-2 text-sm">{{ winner.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- ── ALL ENTRIES ── -->
                        <div v-if="submissions.length && competition.status !== 'open'">
                            <h2 class="font-display text-xl font-bold text-warm-900 mb-5">
                                {{ competition.status === 'results' ? 'All entries' : 'Entries' }}
                            </h2>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div v-for="submission in otherSubmissions" :key="submission.id"
                                     class="rounded-xl border border-warm-200 overflow-hidden bg-white shadow-sm">
                                    <img v-if="submission.image_url" :src="submission.image_url" :alt="submission.name"
                                         class="w-full h-auto block" />
                                    <div class="p-4">
                                        <p class="font-semibold text-warm-900 text-sm">{{ submission.name }}</p>
                                        <p v-if="submission.description" class="text-xs text-warm-500 mt-1">{{ submission.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right: submission form -->
                    <div class="mt-12 lg:mt-0">

                        <!-- Already entered — show their submission -->
                        <div v-if="userSubmission"
                             class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm"
                             :class="userSubmission.is_winner ? 'border-amber-300 ring-2 ring-amber-200' : ''">

                            <!-- Winner banner -->
                            <div v-if="userSubmission.is_winner"
                                 class="flex items-center gap-2 px-5 py-3 bg-amber-50 border-b border-amber-200 text-sm font-semibold text-amber-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                Your entry won!
                            </div>

                            <!-- Image -->
                            <img v-if="userSubmission.image_url" :src="userSubmission.image_url" :alt="userSubmission.name"
                                 class="w-full h-auto block" />

                            <div class="p-5 space-y-3">
                                <!-- Confirmation badge -->
                                <div class="flex items-center gap-2 text-sm font-semibold text-brand-700">
                                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Your entry
                                </div>
                                <p class="font-semibold text-warm-900">{{ userSubmission.name }}</p>
                                <p v-if="userSubmission.description" class="text-sm text-warm-500">{{ userSubmission.description }}</p>
                                <p v-if="!userSubmission.is_winner" class="text-xs text-warm-400">Good luck!</p>
                            </div>
                        </div>

                        <!-- Competition closed / results -->
                        <div v-else-if="competition.status !== 'open'"
                             class="bg-warm-50 border border-warm-200 rounded-2xl p-6 text-center">
                            <p class="font-semibold text-warm-700 mb-1">
                                {{ competition.status === 'results' ? 'Competition closed' : 'No longer accepting entries' }}
                            </p>
                            <p class="text-sm text-warm-500">This competition is not currently open for entries.</p>
                        </div>

                        <!-- Not logged in -->
                        <div v-else-if="!auth.user"
                             class="bg-white border border-warm-200 rounded-2xl p-6 shadow-sm text-center space-y-3">
                            <p class="font-semibold text-warm-800">Want to enter?</p>
                            <p class="text-sm text-warm-500">You need to be logged in to submit an entry.</p>
                            <a href="/login"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                                Log in to enter
                            </a>
                        </div>

                        <!-- Submission form -->
                        <div v-else class="bg-white border border-warm-200 rounded-2xl p-6 shadow-sm">
                            <h2 class="font-display text-lg font-semibold text-warm-900 mb-5">Submit your entry</h2>
                            <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
                                <div>
                                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Entry title</label>
                                    <input v-model="form.name" type="text" required
                                           placeholder="Give your entry a title"
                                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                           :class="form.errors.name ? 'border-rose-300' : 'border-warm-200'" />
                                    <p v-if="form.errors.name" class="mt-1.5 text-xs text-rose-600">{{ form.errors.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Description <span class="font-normal text-warm-400">(optional)</span></label>
                                    <textarea v-model="form.description" rows="3"
                                              placeholder="Tell us about your entry…"
                                              class="w-full px-3 py-2 text-sm border rounded-xl bg-white text-warm-900 resize-none
                                                     focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                              :class="form.errors.description ? 'border-rose-300' : 'border-warm-200'"></textarea>
                                    <p v-if="form.errors.description" class="mt-1 text-xs text-rose-600">{{ form.errors.description }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Photo <span class="font-normal text-warm-400">(optional)</span></label>
                                    <div class="space-y-2">
                                        <div v-if="imagePreview" class="rounded-xl overflow-hidden border border-warm-200 aspect-video">
                                            <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                                        </div>
                                        <label class="flex items-center gap-2 px-4 py-2.5 border border-dashed border-warm-300 rounded-xl cursor-pointer
                                                      hover:border-brand-400 hover:bg-brand-50 transition-colors text-sm text-warm-500">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ imagePreview ? 'Change photo' : 'Upload a photo' }}
                                            <input type="file" accept="image/jpeg,image/png,image/webp"
                                                   class="sr-only" @change="onFileChange" />
                                        </label>
                                    </div>
                                    <p v-if="form.errors.image" class="mt-1.5 text-xs text-rose-600">{{ form.errors.image }}</p>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                        class="w-full px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                                               hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                                    {{ form.processing ? 'Submitting…' : 'Submit entry' }}
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <Footer :navLinks="navLinks" />
    </div>
</template>
