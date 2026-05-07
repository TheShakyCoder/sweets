<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    activities: { type: Object, required: true },
    search:     { type: String, default: '' },
});

const search = ref(props.search);
let debounce = null;

watch(search, (val) => {
    clearTimeout(debounce);
    debounce = setTimeout(() => {
        router.get(route('internal.activities.index'), { search: val || undefined }, { preserveState: true, replace: true });
    }, 300);
});

const statusBadge = {
    active:   'bg-green-100 text-green-700',
    inactive: 'bg-gray-100 text-gray-500',
};
</script>

<template>
    <Head title="Activities" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold text-warm-900 font-display">Activities</h1>
                <Link :href="route('internal.activities.create')"
                      class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    New Activity
                </Link>
            </div>
        </template>

        <div class="mb-4">
            <input v-model="search" type="text" placeholder="Search activities…"
                   class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64" />
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Title</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Meetings</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="a in activities.data" :key="a.id" class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-warm-900">{{ a.title }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statusBadge[a.status]">{{ a.status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ a.meetings_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <Link :href="route('internal.activities.edit', a.id)" class="text-brand-600 hover:underline text-xs">Edit</Link>
                            <Link :href="route('internal.activities.show', a.id)" class="text-gray-500 hover:underline text-xs">View</Link>
                        </td>
                    </tr>
                    <tr v-if="!activities.data.length">
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">No activities yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="activities.links && activities.links.length > 3" class="mt-4 flex justify-center gap-1">
            <Link v-for="link in activities.links" :key="link.label" :href="link.url ?? '#'"
                  class="px-3 py-1 rounded text-sm"
                  :class="{ 'bg-blue-600 text-white': link.active, 'bg-white text-gray-600 hover:bg-gray-100': !link.active && link.url, 'text-gray-300 cursor-default': !link.url }"
                  v-html="link.label" :preserve-state="true" />
        </div>
    </AuthenticatedLayout>
</template>
