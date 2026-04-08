<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: { type: Object, required: true },
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

function submit() {
    form.patch(`/admin/users/${props.user.id}`);
}
</script>

<template>
    <Head :title="`Edit — ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/admin/users"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Edit User</h1>
                    <p class="text-sm text-warm-500 mt-0.5">{{ user.email }}</p>
                </div>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-warm-700 mb-1.5">Name</label>
                        <input v-model="form.name" id="name" type="text" required
                               class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300" />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-rose-600">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-warm-700 mb-1.5">Email</label>
                        <input v-model="form.email" id="email" type="email" required
                               class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300" />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-rose-600">{{ form.errors.email }}</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                            {{ form.processing ? 'Saving…' : 'Save changes' }}
                        </button>
                        <Link href="/admin/users"
                              class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
