<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import MediaPicker from '@/Components/MediaPicker.vue';
import { ref } from 'vue';

const form = useForm({
    title:        '',
    slug:         '',
    description:  '',
    content:      '',
    thumbnail_id: null,
    status:       'open',
});

const thumbnailUrl = ref(null);

function autoSlug() {
    if (!form.slug) {
        form.slug = form.title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    }
}

function submit() {
    form.post('/internal/competitions');
}
</script>

<template>
    <Head title="New Competition" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/competitions"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">New Competition</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Create a competition for members to enter</p>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="flex flex-col lg:flex-row gap-6 items-start">

            <!-- Main content -->
            <div class="flex-1 min-w-0 bg-white border border-warm-200 rounded-2xl p-7 shadow-sm space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Title</label>
                    <input v-model="form.title" @blur="autoSlug" type="text" required
                           placeholder="e.g. Summer Photo Competition"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                           :class="form.errors.title ? 'border-rose-300' : 'border-warm-200'" />
                    <p v-if="form.errors.title" class="mt-1.5 text-xs text-rose-600">{{ form.errors.title }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Slug</label>
                    <input v-model="form.slug" type="text" required placeholder="summer-photo-competition"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900 font-mono
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                           :class="form.errors.slug ? 'border-rose-300' : 'border-warm-200'" />
                    <p v-if="form.errors.slug" class="mt-1.5 text-xs text-rose-600">{{ form.errors.slug }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Rules &amp; Details</label>
                    <MarkdownEditor v-model="form.content" />
                    <p v-if="form.errors.content" class="mt-1.5 text-xs text-rose-600">{{ form.errors.content }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-72 shrink-0 space-y-4">

                <!-- Actions -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <button type="submit" :disabled="form.processing"
                            class="w-full px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                                   hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                        {{ form.processing ? 'Creating…' : 'Create competition' }}
                    </button>
                    <Link href="/internal/competitions"
                          class="block w-full px-4 py-2.5 text-center text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                        Cancel
                    </Link>
                </div>

                <!-- Status -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-warm-700 uppercase tracking-wide">Status</h3>
                    <select v-model="form.status"
                            class="w-full px-3 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                   focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                            :class="form.errors.status ? 'border-rose-300' : 'border-warm-200'">
                        <option value="open">Open — accepting entries</option>
                        <option value="closed">Closed — no new entries</option>
                        <option value="results">Results — winner announced</option>
                    </select>
                    <p v-if="form.errors.status" class="text-xs text-rose-600">{{ form.errors.status }}</p>
                </div>

                <!-- Meta description -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-warm-700 uppercase tracking-wide">Short description</h3>
                    <textarea v-model="form.description" rows="3"
                              placeholder="Brief summary shown on the competitions list…"
                              class="w-full px-3 py-2 text-sm border rounded-xl bg-white text-warm-900 resize-none
                                     focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                              :class="form.errors.description ? 'border-rose-300' : 'border-warm-200'"></textarea>
                    <p v-if="form.errors.description" class="text-xs text-rose-600">{{ form.errors.description }}</p>
                </div>

                <!-- Thumbnail -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-warm-700 uppercase tracking-wide">Thumbnail</h3>
                    <MediaPicker v-model="form.thumbnail_id" v-model:url="thumbnailUrl" />
                    <p v-if="form.errors.thumbnail_id" class="text-xs text-rose-600">{{ form.errors.thumbnail_id }}</p>
                </div>

            </div>
        </form>
    </AuthenticatedLayout>
</template>
