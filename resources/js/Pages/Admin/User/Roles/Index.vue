<script setup>
import { computed } from 'vue';
import { useForm, Link, Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

const props = defineProps({
    user:  { type: Object, required: true },
    roles: { type: Array,  required: true },
});

const form = useForm({
    role_ids: props.user.roles.map(r => r.id),
});

function toggle(id) {
    const idx = form.role_ids.indexOf(id);
    if (idx === -1) form.role_ids.push(id);
    else form.role_ids.splice(idx, 1);
}

function has(id) {
    return form.role_ids.includes(id);
}

function submit() {
    form.put(`/admin/users/${props.user.id}/roles`);
}
</script>

<template>
    <Head :title="`Roles — ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link href="/admin/users"
                          class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-xl font-semibold text-warm-900 font-display">Manage Roles</h1>
                        <p class="text-sm text-warm-500 mt-0.5">
                            User: <span class="font-medium text-warm-700">{{ user.name }}</span>
                        </p>
                    </div>
                </div>

                <button @click="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                               hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ form.processing ? 'Saving…' : 'Save changes' }}
                </button>
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

        <div class="bg-white border border-warm-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-warm-100 bg-warm-50">
                <p class="text-xs text-warm-500">
                    {{ form.role_ids.length }} role{{ form.role_ids.length !== 1 ? 's' : '' }} assigned
                </p>
            </div>

            <ul class="divide-y divide-warm-100">
                <li v-for="role in roles" :key="role.id"
                    class="flex items-center gap-4 px-6 py-4 hover:bg-warm-50 transition-colors cursor-pointer"
                    @click="toggle(role.id)">
                    <!-- Checkbox -->
                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center shrink-0 transition-colors"
                         :class="has(role.id)
                             ? 'bg-brand-600 border-brand-600'
                             : 'bg-white border-warm-300'">
                        <svg v-if="has(role.id)" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <!-- Role info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-warm-900">{{ role.name }}</p>
                        <p class="text-xs text-warm-400 font-mono">{{ role.slug }}</p>
                    </div>

                    <!-- Badge if assigned -->
                    <span v-if="has(role.id)"
                          class="text-xs font-medium px-2 py-0.5 rounded-full bg-brand-100 text-brand-700">
                        Assigned
                    </span>
                </li>
            </ul>

            <div v-if="roles.length === 0" class="px-6 py-10 text-center text-sm text-warm-400">
                No roles exist yet.
                <Link href="/admin/roles/create" class="text-brand-600 hover:text-brand-800 font-medium ml-1">Create one →</Link>
            </div>
        </div>

        <!-- Sticky save -->
        <div class="sticky bottom-6 mt-6 flex justify-end">
            <button @click="submit" :disabled="form.processing"
                    class="inline-flex items-center gap-2 px-7 py-3 bg-brand-600 text-white font-semibold rounded-xl text-sm
                           hover:bg-brand-700 transition-colors shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                {{ form.processing ? 'Saving…' : 'Save changes' }}
            </button>
        </div>
    </AuthenticatedLayout>
</template>
