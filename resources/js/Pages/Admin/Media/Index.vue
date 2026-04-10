<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const page = usePage();

const props = defineProps({
    media:  { type: Object, required: true },
    search: { type: String, default: '' },
});

// ── Upload ──────────────────────────────────────────────
const uploading   = ref(false);
const uploadQueue = ref([]);   // { file, progress, error }
const dropActive  = ref(false);

function onFilePick(e) {
    uploadFiles([...e.target.files]);
    e.target.value = '';
}

function onDrop(e) {
    dropActive.value = false;
    uploadFiles([...e.dataTransfer.files]);
}

async function uploadFiles(files) {
    uploading.value = true;
    uploadQueue.value = files.map(f => ({ name: f.name, progress: 0, error: null }));

    const formData = new FormData();
    files.forEach(f => formData.append('files[]', f));

    try {
        await axios.post('/admin/media', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (e) => {
                const pct = Math.round((e.loaded / e.total) * 100);
                uploadQueue.value.forEach(q => q.progress = pct);
            },
        });
        router.reload({ only: ['media'] });
    } catch (err) {
        const errors = err.response?.data?.errors?.['files.0'] ?? ['Upload failed'];
        uploadQueue.value.forEach(q => q.error = errors[0]);
    } finally {
        uploading.value = false;
        setTimeout(() => uploadQueue.value = [], 2000);
    }
}

// ── Selected / detail panel ──────────────────────────────
const selected = ref(null);
const altDraft = ref('');

function select(item) {
    selected.value = item;
    altDraft.value = item.alt ?? '';
}

function deselect() { selected.value = null; }

async function saveAlt() {
    await axios.patch(`/admin/media/${selected.value.id}`, { alt: altDraft.value });
    selected.value.alt = altDraft.value;
}

function confirmDelete(item) {
    if (!confirm(`Delete "${item.filename}"? This cannot be undone.`)) return;
    router.delete(`/admin/media/${item.id}`, {
        onSuccess: () => { selected.value = null; },
    });
}

// ── Search ───────────────────────────────────────────────
const searchQuery = ref(props.search);
function doSearch() {
    router.get('/admin/media', { search: searchQuery.value }, { preserveState: true, replace: true });
}

// ── Helpers ──────────────────────────────────────────────
function isImage(mime) { return mime?.startsWith('image/'); }
function fileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}
function copyUrl(url) {
    navigator.clipboard.writeText(url);
}
</script>

<template>
    <Head title="Media Library" />

    <AuthenticatedLayout :title="'Media Library'">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Media Library</h1>
                    <p class="text-sm text-warm-500 mt-0.5">{{ media.total }} file{{ media.total !== 1 ? 's' : '' }}</p>
                </div>
                <label class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                              hover:bg-brand-700 transition-colors shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload
                    <input type="file" multiple accept="image/*,.pdf" class="sr-only" @change="onFilePick" />
                </label>
            </div>
        </template>

        <!-- Flash -->
        <div v-if="page.props.flash.success"
             class="mb-6 flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ page.props.flash.success }}
        </div>

        <!-- Upload progress -->
        <div v-if="uploadQueue.length" class="mb-6 space-y-2">
            <div v-for="q in uploadQueue" :key="q.name"
                 class="flex items-center gap-3 px-4 py-3 bg-white border border-warm-200 rounded-xl text-sm">
                <svg class="w-4 h-4 text-warm-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="flex-1 truncate text-warm-700">{{ q.name }}</span>
                <span v-if="q.error" class="text-rose-600 text-xs">{{ q.error }}</span>
                <div v-else class="w-32 h-1.5 bg-warm-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500 transition-all duration-300" :style="{ width: q.progress + '%' }"></div>
                </div>
            </div>
        </div>

        <div class="flex gap-6 items-start">

            <!-- ── Grid ── -->
            <div class="flex-1 min-w-0">

                <!-- Search -->
                <div class="mb-4 flex gap-2">
                    <input v-model="searchQuery" @keydown.enter="doSearch" type="search"
                           placeholder="Search by filename…"
                           class="flex-1 px-4 py-2 text-sm border border-warm-200 rounded-xl bg-white text-warm-900
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300" />
                    <button @click="doSearch"
                            class="px-4 py-2 text-sm font-medium bg-white border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors text-warm-600">
                        Search
                    </button>
                </div>

                <!-- Drop zone overlay -->
                <div class="relative"
                     @dragover.prevent="dropActive = true"
                     @dragleave.prevent="dropActive = false"
                     @drop.prevent="onDrop">

                    <div v-if="dropActive"
                         class="absolute inset-0 z-10 flex items-center justify-center bg-brand-50/90 border-2 border-dashed border-brand-400 rounded-2xl">
                        <p class="text-brand-600 font-semibold text-sm">Drop files to upload</p>
                    </div>

                    <!-- Empty -->
                    <div v-if="!media.data.length"
                         class="flex flex-col items-center justify-center py-24 bg-warm-50 rounded-2xl border border-warm-200 text-center">
                        <svg class="w-12 h-12 text-warm-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-warm-500">No files yet. Upload or drag &amp; drop.</p>
                    </div>

                    <!-- Grid -->
                    <div v-else class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                        <button v-for="item in media.data" :key="item.id"
                                type="button"
                                @click="select(item)"
                                class="group relative aspect-square rounded-xl overflow-hidden border-2 transition-all"
                                :class="selected?.id === item.id
                                    ? 'border-brand-500 shadow-md'
                                    : 'border-warm-200 hover:border-brand-300'">

                            <!-- Image preview -->
                            <img v-if="isImage(item.mime_type)"
                                 :src="item.url" :alt="item.alt ?? item.filename"
                                 class="w-full h-full object-cover" loading="lazy" />

                            <!-- Non-image placeholder -->
                            <div v-else class="w-full h-full bg-warm-100 flex flex-col items-center justify-center gap-1 p-2">
                                <svg class="w-8 h-8 text-warm-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-xs text-warm-500 truncate w-full text-center">{{ item.filename }}</span>
                            </div>

                            <!-- Selected check -->
                            <div v-if="selected?.id === item.id"
                                 class="absolute top-1.5 right-1.5 w-5 h-5 bg-brand-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="media.last_page > 1" class="mt-6 flex items-center justify-center gap-2">
                    <Link v-for="link in media.links" :key="link.label"
                          :href="link.url ?? '#'"
                          v-html="link.label"
                          class="px-3 py-1.5 text-xs rounded-lg border transition-colors"
                          :class="link.active
                              ? 'bg-brand-600 text-white border-brand-600'
                              : link.url
                                  ? 'border-warm-200 text-warm-600 hover:bg-warm-100'
                                  : 'border-warm-100 text-warm-300 pointer-events-none'" />
                </div>
            </div>

            <!-- ── Detail panel ── -->
            <transition enter-from-class="opacity-0 translate-x-4" enter-active-class="transition duration-150"
                        leave-to-class="opacity-0 translate-x-4" leave-active-class="transition duration-150">
                <aside v-if="selected" class="w-64 shrink-0 space-y-4">

                    <!-- Preview -->
                    <div class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="aspect-video bg-warm-100 flex items-center justify-center">
                            <img v-if="isImage(selected.mime_type)"
                                 :src="selected.url" :alt="selected.alt ?? selected.filename"
                                 class="max-w-full max-h-full object-contain" />
                            <svg v-else class="w-12 h-12 text-warm-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div class="p-4 space-y-3">
                            <p class="text-xs font-medium text-warm-900 break-all">{{ selected.filename }}</p>
                            <div class="text-xs text-warm-500 space-y-1">
                                <p>{{ fileSize(selected.size) }}</p>
                                <p>{{ selected.mime_type }}</p>
                            </div>

                            <!-- Alt text -->
                            <div>
                                <label class="block text-xs font-semibold text-warm-700 mb-1">Alt text</label>
                                <input v-model="altDraft" type="text" placeholder="Describe this image…"
                                       class="w-full px-3 py-1.5 text-xs border border-warm-200 rounded-lg bg-white text-warm-900
                                              focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                            </div>
                            <button @click="saveAlt"
                                    class="w-full px-3 py-1.5 text-xs font-semibold bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition-colors">
                                Save alt text
                            </button>

                            <!-- URL copy -->
                            <button @click="copyUrl(selected.url)"
                                    class="w-full px-3 py-1.5 text-xs font-medium border border-warm-200 text-warm-600 rounded-lg hover:bg-warm-50 transition-colors">
                                Copy URL
                            </button>

                            <!-- Delete -->
                            <button @click="confirmDelete(selected)"
                                    class="w-full px-3 py-1.5 text-xs font-medium border border-rose-200 text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                Delete file
                            </button>
                        </div>
                    </div>

                    <button @click="deselect" class="text-xs text-warm-400 hover:text-warm-600 transition-colors">
                        ← Deselect
                    </button>
                </aside>
            </transition>

        </div>
    </AuthenticatedLayout>
</template>
