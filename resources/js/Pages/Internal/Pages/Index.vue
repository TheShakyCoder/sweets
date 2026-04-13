<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const page = usePage();

defineProps({
    pages: Object,
});

function destroy(id, title) {
    if (confirm(`Delete "${title}"? This cannot be undone.`)) {
        router.delete(`/internal/pages/${id}`);
    }
}
</script>

<template>
    <Head title="Pages" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Pages</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Manage static content pages</p>
                </div>
                <Link href="/internal/pages/create"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New page
                </Link>
            </div>
        </template>

        <!-- Flash -->
        <div v-if="page.props.flash.success"
             class="mb-6 flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ page.props.flash.success }}
        </div>

        <!-- Empty state -->
        <div v-if="!pages?.data?.length"
             class="text-center py-20 bg-warm-50 rounded-2xl border border-warm-200">
            <span class="text-4xl block mb-3">📄</span>
            <p class="font-semibold text-warm-800 mb-1">No pages yet</p>
            <p class="text-sm text-warm-500 mb-5">Create your first page to get started.</p>
            <Link href="/internal/pages/create"
                  class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                Create a page
            </Link>
        </div>

        <!-- Pages list -->
        <div v-else class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="divide-y divide-warm-100">
                <div v-for="p in pages.data" :key="p.id"
                     class="flex items-center gap-4 px-6 py-4">
                    <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center text-sm shrink-0">
                        📄
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-warm-900 truncate">{{ p.title }}</p>
                        <p class="text-xs text-warm-400 font-mono mt-0.5 truncate">{{ p.slug }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <Link :href="`/internal/pages/${p.id}/edit`"
                              class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-warm-600 border border-warm-200 rounded-lg hover:bg-warm-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </Link>
                        <button @click="destroy(p.id, p.title)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="pages.last_page > 1"
                 class="flex items-center justify-between px-6 py-4 border-t border-warm-100 bg-warm-50">
                <p class="text-xs text-warm-500">
                    Showing {{ pages.from }}–{{ pages.to }} of {{ pages.total }}
                </p>
                <div class="flex gap-1">
                    <Link v-for="link in pages.links" :key="link.label"
                          :href="link.url ?? '#'"
                          v-html="link.label"
                          class="px-3 py-1.5 text-xs rounded-lg border transition-colors"
                          :class="link.active
                              ? 'bg-brand-600 text-white border-brand-600'
                              : link.url
                                  ? 'border-warm-200 text-warm-600 hover:bg-warm-100'
                                  : 'border-warm-100 text-warm-300 cursor-not-allowed pointer-events-none'" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
