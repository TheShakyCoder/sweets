<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({ name: '', slug: '' });

function autoSlug() {
    if (!form.slug) {
        form.slug = form.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    }
}

function submit() {
    form.post('/admin/roles');
}
</script>

<template>
    <Head title="Create Role" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/roles"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Create Role</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Add a new role to the system</p>
                </div>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-warm-700 mb-1.5">Role name</label>
                        <input v-model="form.name" @blur="autoSlug" id="name" type="text" required placeholder="e.g. Editor"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.name ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-rose-600">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-semibold text-warm-700 mb-1.5">Slug</label>
                        <input v-model="form.slug" id="slug" type="text" required placeholder="e.g. editor"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300 font-mono"
                               :class="form.errors.slug ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.slug" class="mt-1.5 text-xs text-rose-600">{{ form.errors.slug }}</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                            {{ form.processing ? 'Creating…' : 'Create role' }}
                        </button>
                        <Link href="/admin/roles"
                              class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
