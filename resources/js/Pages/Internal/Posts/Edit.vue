<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import { ref } from 'vue';

const props = defineProps({
    post: { type: Object, required: true },
});

const form = useForm({
    _method:     'PUT',
    title:       props.post.title,
    slug:        props.post.slug,
    description: props.post.description ?? '',
    content:     props.post.content,
    thumbnail:   null,
});

const thumbnailPreview = ref(props.post.thumbnail_url ?? null);
const removeThumbnail = ref(false);

function onThumbnail(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.thumbnail = file;
    thumbnailPreview.value = URL.createObjectURL(file);
    removeThumbnail.value = false;
}

function clearThumbnail() {
    form.thumbnail = null;
    thumbnailPreview.value = null;
    removeThumbnail.value = true;
}

function submit() {
    form.post(`/internal/posts/${props.post.id}`, { forceFormData: true });
}
</script>

<template>
    <Head :title="`Edit — ${post.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/posts"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Edit Post</h1>
                    <p class="text-sm text-warm-500 mt-0.5 truncate max-w-xs">{{ post.title }}</p>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="flex flex-col lg:flex-row gap-6 items-start">

            <!-- ── Main content ── -->
            <div class="flex-1 min-w-0 bg-white border border-warm-200 rounded-2xl p-7 shadow-sm space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Title</label>
                    <input v-model="form.title" type="text" required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                           :class="form.errors.title ? 'border-rose-300' : 'border-warm-200'" />
                    <p v-if="form.errors.title" class="mt-1.5 text-xs text-rose-600">{{ form.errors.title }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Slug</label>
                    <input v-model="form.slug" type="text" required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900 font-mono
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                           :class="form.errors.slug ? 'border-rose-300' : 'border-warm-200'" />
                    <p v-if="form.errors.slug" class="mt-1.5 text-xs text-rose-600">{{ form.errors.slug }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Content</label>
                    <MarkdownEditor v-model="form.content" />
                    <p v-if="form.errors.content" class="mt-1.5 text-xs text-rose-600">{{ form.errors.content }}</p>
                </div>
            </div>

            <!-- ── Sidebar ── -->
            <div class="w-full lg:w-72 shrink-0 space-y-4">

                <!-- Actions -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <button type="submit" :disabled="form.processing"
                            class="w-full px-4 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                                   hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                        {{ form.processing ? 'Saving…' : 'Save changes' }}
                    </button>
                    <Link href="/internal/posts"
                          class="block w-full px-4 py-2.5 text-center text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                        Cancel
                    </Link>
                </div>

                <!-- Meta description -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-warm-700 uppercase tracking-wide">SEO</h3>
                    <div>
                        <label class="block text-xs font-medium text-warm-600 mb-1.5">Meta description</label>
                        <textarea v-model="form.description" rows="3"
                                  placeholder="Brief summary for search engines…"
                                  class="w-full px-3 py-2 text-sm border rounded-xl bg-white text-warm-900 resize-none
                                         focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                  :class="form.errors.description ? 'border-rose-300' : 'border-warm-200'"></textarea>
                        <p class="mt-1 text-xs text-warm-400">{{ (form.description ?? '').length }}/160</p>
                        <p v-if="form.errors.description" class="mt-1 text-xs text-rose-600">{{ form.errors.description }}</p>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-warm-700 uppercase tracking-wide">Thumbnail</h3>

                    <!-- Preview -->
                    <div v-if="thumbnailPreview" class="relative rounded-xl overflow-hidden aspect-video bg-warm-100">
                        <img :src="thumbnailPreview" class="w-full h-full object-cover" alt="Thumbnail preview" />
                        <button type="button" @click="clearThumbnail"
                                class="absolute top-2 right-2 p-1 bg-black/50 rounded-full text-white hover:bg-black/70 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <label class="flex flex-col items-center gap-2 p-4 border-2 border-dashed border-warm-200 rounded-xl cursor-pointer hover:border-brand-300 hover:bg-brand-50/50 transition-colors">
                        <svg class="w-6 h-6 text-warm-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-warm-500">{{ thumbnailPreview ? 'Replace image' : 'Upload image' }}</span>
                        <input type="file" accept="image/*" class="sr-only" @change="onThumbnail" />
                    </label>
                    <p v-if="form.errors.thumbnail" class="text-xs text-rose-600">{{ form.errors.thumbnail }}</p>
                </div>

            </div>
        </form>
    </AuthenticatedLayout>
</template>
