<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    pageViews: { type: Object, required: true },
    stats:     { type: Object, required: true },
    topPages:  { type: Array,  required: true },
    filters:   { type: Object, default: () => ({}) },
});

const search   = ref(props.filters.search ?? '');
const dateFrom = ref(props.filters.date_from ?? '');
const dateTo   = ref(props.filters.date_to ?? '');

let debounce = null;

function applyFilters() {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        router.get(route('internal.page-views.index'), {
            search:    search.value || undefined,
            date_from: dateFrom.value || undefined,
            date_to:   dateTo.value || undefined,
        }, {
            preserveState: true,
            replace: true,
        });
    }, 300);
}

watch([search, dateFrom, dateTo], applyFilters);

function truncate(str, len = 80) {
    if (!str) return '—';
    return str.length > len ? str.slice(0, len) + '…' : str;
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString();
}
</script>

<template>
    <Head title="Page Views" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold mb-6">Page Views</h1>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Today</div>
                    <div class="text-2xl font-semibold">{{ stats.today.toLocaleString() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">This Week</div>
                    <div class="text-2xl font-semibold">{{ stats.this_week.toLocaleString() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">This Month</div>
                    <div class="text-2xl font-semibold">{{ stats.this_month.toLocaleString() }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">All Time</div>
                    <div class="text-2xl font-semibold">{{ stats.total.toLocaleString() }}</div>
                </div>
            </div>

            <!-- Top Pages (last 30 days) -->
            <div class="bg-white rounded-lg shadow p-4 mb-8">
                <h2 class="text-lg font-semibold mb-3">Top Pages (Last 30 Days)</h2>
                <div class="space-y-2">
                    <div v-for="page in topPages" :key="page.url" class="flex justify-between items-center text-sm">
                        <span class="text-gray-700 truncate mr-4" :title="page.url">{{ truncate(page.url, 60) }}</span>
                        <span class="text-gray-500 whitespace-nowrap font-medium">{{ Number(page.views).toLocaleString() }}</span>
                    </div>
                    <div v-if="!topPages.length" class="text-gray-400 text-sm">No data yet.</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mb-4">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search URL, IP, or user agent…"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64"
                />
                <input
                    v-model="dateFrom"
                    type="date"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                />
                <input
                    v-model="dateTo"
                    type="date"
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm"
                />
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">URL</th>
                            <th class="px-4 py-3 font-medium">IP</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">User</th>
                            <th class="px-4 py-3 font-medium">Viewed At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="view in pageViews.data" :key="view.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3 max-w-xs truncate" :title="view.url">{{ truncate(view.url, 60) }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ view.ip ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    :class="{
                                        'text-green-600': view.status_code >= 200 && view.status_code < 300,
                                        'text-yellow-600': view.status_code >= 300 && view.status_code < 400,
                                        'text-red-600': view.status_code >= 400,
                                    }"
                                >{{ view.status_code ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ view.user_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ formatDate(view.viewed_at) }}</td>
                        </tr>
                        <tr v-if="!pageViews.data.length">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">No page views recorded yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="pageViews.links && pageViews.links.length > 3" class="mt-4 flex justify-center gap-1">
                <Link
                    v-for="link in pageViews.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    class="px-3 py-1 rounded text-sm"
                    :class="{
                        'bg-blue-600 text-white': link.active,
                        'bg-white text-gray-600 hover:bg-gray-100': !link.active && link.url,
                        'text-gray-300 cursor-default': !link.url,
                    }"
                    v-html="link.label"
                    :preserve-state="true"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
