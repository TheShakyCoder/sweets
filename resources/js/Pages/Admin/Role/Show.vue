<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ role: Object });

const methodMeta = {
    index:   { label: 'View list',   colour: 'bg-sky-100 text-sky-700' },
    show:    { label: 'View detail', colour: 'bg-purple-100 text-purple-700' },
    store:   { label: 'Create',      colour: 'bg-brand-100 text-brand-700' },
    update:  { label: 'Edit',        colour: 'bg-amber-100 text-amber-700' },
    destroy: { label: 'Delete',      colour: 'bg-rose-100 text-rose-700' },
};

function methodLabel(key) {
    const method = key?.split('@')[1] ?? key;
    return methodMeta[method] ?? { label: method, colour: 'bg-warm-100 text-warm-700' };
}
</script>

<template>
    <Head :title="`Role — ${role.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link href="/admin/roles"
                          class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-xl font-semibold text-warm-900 font-display">{{ role.name }}</h1>
                        <p class="text-sm text-warm-500 font-mono mt-0.5">{{ role.slug }}</p>
                    </div>
                </div>
                <Link :href="`/admin/roles/${role.id}/rights`"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Manage rights
                </Link>
            </div>
        </template>

        <div class="max-w-2xl space-y-6">
            <!-- Details card -->
            <div class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50">
                    <h2 class="text-sm font-semibold text-warm-700">Details</h2>
                </div>
                <div class="divide-y divide-warm-50">
                    <div class="flex items-center gap-4 px-6 py-3.5">
                        <span class="text-xs font-semibold text-warm-400 w-20 shrink-0">Name</span>
                        <span class="text-sm text-warm-900">{{ role.name }}</span>
                    </div>
                    <div class="flex items-center gap-4 px-6 py-3.5">
                        <span class="text-xs font-semibold text-warm-400 w-20 shrink-0">Slug</span>
                        <span class="text-sm text-warm-900 font-mono">{{ role.slug }}</span>
                    </div>
                </div>
            </div>

            <!-- Rights card -->
            <div class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-warm-100 bg-warm-50">
                    <h2 class="text-sm font-semibold text-warm-700">Rights</h2>
                    <span class="text-xs text-warm-400">{{ role.rights?.length ?? 0 }} assigned</span>
                </div>

                <div v-if="!role.rights?.length" class="px-6 py-8 text-center text-sm text-warm-400">
                    No rights assigned yet.
                    <Link :href="`/admin/roles/${role.id}/rights`" class="text-brand-600 hover:text-brand-800 font-medium ml-1">Add some →</Link>
                </div>

                <div v-else class="divide-y divide-warm-50">
                    <div v-for="right in role.rights" :key="right.id"
                         class="flex items-center gap-4 px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shrink-0"
                              :class="methodLabel(right.controller_method_name).colour">
                            {{ methodLabel(right.controller_method_name).label }}
                        </span>
                        <span class="text-sm text-warm-500 font-mono truncate">{{ right.controller_method_name }}</span>
                        <span v-if="right.description" class="ml-auto text-xs text-warm-400 shrink-0">{{ right.description }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
