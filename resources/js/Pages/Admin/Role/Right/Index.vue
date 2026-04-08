<script setup>
import { computed, onMounted } from 'vue';
import { router, useForm, Link, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Groups from './Partials/Groups.vue';

const page = usePage();

const props = defineProps({
    role: { type: Object, required: true },
    internal: { type: Object, required: true },
    admin: { type: Object, required: true },
});

const form = useForm({
    controller_method_names: {},
});

onMounted(() => {
    for (const r of props.role.rights) {
        form.controller_method_names[r.controller_method_name] = true;
    }
});

const methodMeta = {
    index: { label: 'View list', colour: 'bg-sky-100 text-sky-700 border-sky-200' },
    show: { label: 'View detail', colour: 'bg-purple-100 text-purple-700 border-purple-200' },
    store: { label: 'Create', colour: 'bg-brand-100 text-brand-700 border-brand-200' },
    update: { label: 'Edit', colour: 'bg-amber-100 text-amber-700 border-amber-200' },
    destroy: { label: 'Delete', colour: 'bg-rose-100 text-rose-700 border-rose-200' },
};

const savedCount = computed(() =>
    Object.values(form.controller_method_names).filter(Boolean).length
);

function submit() {
    router.put('/admin/roles/' + props.role.id + '/rights', form);
}
</script>

<template>

    <Head :title="`Rights — ${role.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Back to roles -->
                    <Link :href="'/admin/roles/' + role.id"
                        class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-xl font-semibold text-warm-900 font-display">Manage Rights</h1>
                        <p class="text-sm text-warm-500 mt-0.5">
                            Role: <span class="font-medium text-warm-700">{{ role.name }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-warm-400">
                        {{ savedCount }} permission{{ savedCount !== 1 ? 's' : '' }} enabled
                    </span>
                    <button @click="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                                   hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Saving…' : 'Save changes' }}
                    </button>
                </div>
            </div>
        </template>

        <!-- Server flash success -->
        <div v-if="page.props.flash.success"
            class="mb-6 flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ page.props.flash.success }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <Groups :title="'Internal Routes'" :routes="internal" :form="form" :methodMeta="methodMeta" :submit="submit" />
            <Groups :title="'Admin Routes'" :routes="admin" :form="form" :methodMeta="methodMeta" :submit="submit" />
        </div>
    </AuthenticatedLayout>
</template>
