<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const page = usePage();

const props = defineProps({
    stats:       { type: Object, default: () => ({}) },
    recentPosts: { type: Array,  default: () => [] },
});

const statCards = [
    {
        label:  'Staff Users',
        key:    'users',
        icon:   '👥',
        href:   null,
        colour: 'bg-sky-50 border-sky-200',
        iconBg: 'bg-sky-100',
        value:  'text-sky-700',
    },
    {
        label:  'Roles',
        key:    'roles',
        icon:   '🔐',
        href:   '/admin/roles',
        colour: 'bg-purple-50 border-purple-200',
        iconBg: 'bg-purple-100',
        value:  'text-purple-700',
    },
];

const quickLinks = [
    { label: 'Users',       icon: '👥',  href: '/admin/users',  colour: 'bg-brand-600 hover:bg-brand-700 text-white' },
    { label: 'All Posts',   icon: '📰',  href: '/admin/posts',  colour: 'bg-sky-500 hover:bg-sky-600 text-white' },
    { label: 'Roles',       icon: '🔐',  href: '/admin/roles',  colour: 'bg-purple-600 hover:bg-purple-700 text-white' },
    { label: 'Public Site', icon: '🌐',  href: '/',             colour: 'bg-warm-700 hover:bg-warm-800 text-white' },
];

function formatDate(iso) {
    return new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout title="Admin Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Admin Dashboard</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Welcome back, {{ page.props.auth.user.name }}</p>
                </div>
                <a href="/" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-medium text-warm-600 border border-warm-200 rounded-lg hover:bg-warm-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View public site
                </a>
            </div>
        </template>

        <!-- Quick actions -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
            <Link v-for="link in quickLinks" :key="link.label" :href="link.href"
               class="flex items-center gap-2.5 px-4 py-3 rounded-xl text-sm font-semibold transition-colors shadow-sm"
               :class="link.colour">
                <span class="text-base">{{ link.icon }}</span>
                {{ link.label }}
            </Link>
        </div>

        <!-- Stats -->
        <div class="grid sm:grid-cols-3 gap-5 mb-8">
            <component
                v-for="card in statCards" :key="card.key"
                :is="card.href ? 'a' : 'div'"
                :href="card.href ?? undefined"
                class="group p-5 rounded-2xl border-2 transition-all"
                :class="[card.colour, card.href ? 'hover:shadow-md hover:-translate-y-0.5 cursor-pointer' : '']">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-3" :class="card.iconBg">
                    {{ card.icon }}
                </div>
                <p class="text-2xl font-bold font-display" :class="card.value">
                    {{ props.stats[card.key] ?? 0 }}
                </p>
                <p class="text-xs font-medium text-warm-600 mt-0.5">{{ card.label }}</p>
            </component>
        </div>

        <!-- Content grid -->
        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Recent posts -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-warm-100">
                    <h2 class="font-semibold text-warm-900 text-sm">Recent Posts</h2>
                    <a href="/internal/posts" class="text-xs font-medium text-brand-600 hover:text-brand-800 transition-colors">
                        View all →
                    </a>
                </div>

                <div v-if="recentPosts.length === 0" class="px-6 py-10 text-center text-sm text-warm-400">
                    No posts yet.
                    <a href="/internal/posts/create" class="text-brand-600 hover:text-brand-800 font-medium ml-1">Create one →</a>
                </div>

                <div v-else class="divide-y divide-warm-50">
                    <a v-for="post in recentPosts" :key="post.id"
                       :href="`/internal/posts/${post.id}`"
                       class="flex items-center gap-4 px-6 py-3.5 hover:bg-warm-50 transition-colors group">
                        <div class="w-8 h-8 bg-brand-100 rounded-lg flex items-center justify-center text-sm shrink-0">
                            📰
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-warm-900 truncate group-hover:text-brand-700 transition-colors">
                                {{ post.title }}
                            </p>
                            <p class="text-xs text-warm-400 mt-0.5">{{ formatDate(post.created_at) }}</p>
                        </div>
                        <svg class="w-4 h-4 text-warm-300 group-hover:text-brand-400 group-hover:translate-x-0.5 transition-all shrink-0"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right panel -->
            <div class="space-y-5">

                <!-- Branding card -->
                <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-5 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/media/logo.png" alt="Lollipops" class="h-12 w-auto brightness-0 invert opacity-90" />
                        <div>
                            <p class="font-semibold text-sm font-display leading-tight">Penwortham Lollipops</p>
                        </div>
                    </div>
                    <p class="text-white/70 text-xs leading-relaxed">
                        Manage your site content, user roles, and permissions from this internal panel.
                    </p>
                </div>

                <!-- Navigation shortcuts -->
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-warm-100">
                        <h2 class="font-semibold text-warm-900 text-sm">Manage</h2>
                    </div>
                    <div class="divide-y divide-warm-50">
                        <a v-for="link in quickLinks.slice(0, 3)" :key="link.label"
                           :href="link.href"
                           class="flex items-center gap-3 px-5 py-3 hover:bg-warm-50 transition-colors group">
                            <span class="text-base">{{ link.icon }}</span>
                            <span class="text-sm font-medium text-warm-700 group-hover:text-brand-700 transition-colors flex-1">
                                {{ link.label }}
                            </span>
                            <svg class="w-4 h-4 text-warm-300 group-hover:text-brand-400 transition-colors"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
