<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    activity: { type: Object, required: true },
});

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString();
}
</script>

<template>
    <Head :title="activity.title" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('internal.activities.index')"
                          class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </Link>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">{{ activity.title }}</h1>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('internal.meetings.create') + '?activity_id=' + activity.id"
                          class="px-3 py-2 bg-brand-600 text-white text-xs font-semibold rounded-lg hover:bg-brand-700 transition-colors">
                        Add Meeting
                    </Link>
                    <Link :href="route('internal.activities.edit', activity.id)"
                          class="px-3 py-2 border border-warm-200 text-xs font-medium text-warm-600 rounded-lg hover:bg-warm-50 transition-colors">
                        Edit
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <div class="bg-white border border-warm-200 rounded-2xl p-6 shadow-sm">
                <p v-if="activity.description" class="text-warm-600 mb-4">{{ activity.description }}</p>
                <div v-if="activity.content" class="prose max-w-none" v-html="activity.content"></div>
                <p v-if="!activity.description && !activity.content" class="text-gray-400">No description or content yet.</p>
            </div>

            <div class="bg-white border border-warm-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 flex items-center justify-between">
                    <h2 class="font-semibold text-warm-900 text-sm">Meetings</h2>
                </div>
                <div v-if="activity.meetings?.length" class="divide-y divide-warm-50">
                    <div v-for="m in activity.meetings" :key="m.id" class="px-6 py-4 flex items-center justify-between hover:bg-warm-50">
                        <div>
                            <p class="text-sm font-medium text-warm-900">{{ m.title }}</p>
                            <p class="text-xs text-warm-500 mt-0.5">
                                {{ formatDate(m.starts_at) }}
                                <span v-if="m.location"> · {{ m.location }}</span>
                                <span v-if="m.recurrence" class="ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">{{ m.recurrence }}</span>
                            </p>
                        </div>
                        <Link :href="route('internal.meetings.edit', m.id)" class="text-brand-600 hover:underline text-xs">Edit</Link>
                    </div>
                </div>
                <div v-else class="px-6 py-8 text-center text-gray-400 text-sm">
                    No meetings scheduled for this activity.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
