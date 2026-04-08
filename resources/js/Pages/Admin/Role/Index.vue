<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();
defineProps({ roles: Object });
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Roles</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Manage staff roles and their permissions</p>
                </div>
                <Link href="/admin/roles/create"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New role
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
        <div v-if="!roles?.data?.length"
             class="text-center py-20 bg-warm-50 rounded-2xl border border-warm-200">
            <span class="text-4xl block mb-3">🔐</span>
            <p class="font-semibold text-warm-800 mb-1">No roles yet</p>
            <p class="text-sm text-warm-500 mb-5">Create your first role to start managing permissions.</p>
            <Link href="/admin/roles/create"
                  class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                Create a role
            </Link>
        </div>

        <!-- Roles grid -->
        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <Link v-for="role in roles.data" :key="role.id"
                  :href="`/admin/roles/${role.id}`"
                  class="group flex items-center gap-4 p-5 bg-white border border-warm-200 rounded-2xl hover:border-brand-300 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-warm-900 text-sm group-hover:text-brand-700 transition-colors truncate">{{ role.name }}</p>
                    <p class="text-xs text-warm-400 font-mono mt-0.5 truncate">{{ role.slug }}</p>
                </div>
                <svg class="w-4 h-4 text-warm-300 group-hover:text-brand-400 group-hover:translate-x-0.5 transition-all shrink-0"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
