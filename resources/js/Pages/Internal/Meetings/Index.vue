<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    meetings:   { type: Object, required: true },
    activities: { type: Array,  required: true },
    filters:    { type: Object, default: () => ({}) },
});

const search     = ref(props.filters.search ?? '');
const activityId = ref(props.filters.activity_id ?? '');

let debounce = null;

function applyFilters() {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        router.get(route('internal.meetings.index'), {
            search:      search.value || undefined,
            activity_id: activityId.value || undefined,
        }, { preserveState: true, replace: true });
    }, 300);
}

watch([search, activityId], applyFilters);

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString();
}

const recurrenceBadge = {
    weekly:      'bg-blue-100 text-blue-700',
    fortnightly: 'bg-purple-100 text-purple-700',
    monthly:     'bg-amber-100 text-amber-700',
};
</script>

<template>
    <Head title="Meetings" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-warm-900 font-display">Meetings</h1>
                <Link :href="route('internal.meetings.create')"
                      class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    New Meeting
                </Link>
            </div>
        </template>

        <div class="flex flex-wrap gap-3 mb-4">
            <input v-model="search" type="text" placeholder="Search meetings…"
                   class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64" />
            <select v-model="activityId" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">All activities</option>
                <option v-for="a in activities" :key="a.id" :value="a.id">{{ a.title }}</option>
            </select>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Title</th>
                        <th class="px-4 py-3 font-medium">Activity</th>
                        <th class="px-4 py-3 font-medium">Starts</th>
                        <th class="px-4 py-3 font-medium">Location</th>
                        <th class="px-4 py-3 font-medium">Recurrence</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="m in meetings.data" :key="m.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-warm-900">{{ m.title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ m.activity?.title ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ formatDate(m.starts_at) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ m.location ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span v-if="m.recurrence"
                                  class="px-2 py-0.5 rounded-full text-xs font-medium"
                                  :class="recurrenceBadge[m.recurrence] ?? 'bg-gray-100 text-gray-600'">
                                {{ m.recurrence }}
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="route('internal.meetings.edit', m.id)" class="text-brand-600 hover:underline text-xs">Edit</Link>
                        </td>
                    </tr>
                    <tr v-if="!meetings.data.length">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No meetings yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="meetings.links && meetings.links.length > 3" class="mt-4 flex justify-center gap-1">
            <Link v-for="link in meetings.links" :key="link.label" :href="link.url ?? '#'"
                  class="px-3 py-1 rounded text-sm"
                  :class="{ 'bg-blue-600 text-white': link.active, 'bg-white text-gray-600 hover:bg-gray-100': !link.active && link.url, 'text-gray-300 cursor-default': !link.url }"
                  v-html="link.label" :preserve-state="true" />
        </div>
    </AuthenticatedLayout>
</template>
