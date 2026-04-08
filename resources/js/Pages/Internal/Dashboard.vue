<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    can: Object,
});

const stats = [
    { label: 'Upcoming Events', value: '4', icon: '📅', change: '+2 this week', trend: 'up', color: 'bg-brand-50 border-brand-200', iconBg: 'bg-brand-100', valueColor: 'text-brand-700' },
    { label: 'Active Members', value: '1,248', icon: '👥', change: '+12 this month', trend: 'up', color: 'bg-sky-50 border-sky-200', iconBg: 'bg-sky-100', valueColor: 'text-sky-700' },
    { label: 'News Articles', value: '18', icon: '📰', change: '3 drafts pending', trend: 'neutral', color: 'bg-purple-50 border-purple-200', iconBg: 'bg-purple-100', valueColor: 'text-purple-700' },
    { label: 'Volunteers', value: '152', icon: '🤝', change: '+5 new sign-ups', trend: 'up', color: 'bg-accent-50 border-orange-200', iconBg: 'bg-orange-100', valueColor: 'text-orange-700' },
];

const recentActivity = [
    { action: 'New member registered', detail: 'Sarah Thompson joined the community', time: '2 mins ago', icon: '👤', color: 'bg-brand-100 text-brand-700' },
    { action: 'Event created', detail: '"Spring Community Fair" added for 12 Apr', time: '1 hour ago', icon: '📅', color: 'bg-sky-100 text-sky-700' },
    { action: 'News article published', detail: 'Sensory garden opening published', time: '3 hours ago', icon: '📰', color: 'bg-purple-100 text-purple-700' },
    { action: 'Donation received', detail: '£50 received via online form', time: 'Yesterday', icon: '💚', color: 'bg-green-100 text-green-700' },
    { action: 'Venue hire booking', detail: 'Main hall booked for 19 Apr', time: 'Yesterday', icon: '🏛️', color: 'bg-amber-100 text-amber-700' },
];

const quickLinks = [
    { label: 'Events', icon: '📅', href: '#', color: 'bg-brand-600 hover:bg-brand-700 text-white' },
    { label: 'News', icon: '✍️', href: route('internal.posts.index'), color: 'bg-sky-500 hover:bg-sky-600 text-white' },
    { label: 'Posts', icon: '👤', href: route('internal.posts.index'), color: 'bg-purple-600 hover:bg-purple-700 text-white', permission: 'view_posts' },
    { label: 'View Reports', icon: '📊', href: '#', color: 'bg-warm-700 hover:bg-warm-800 text-white' },
];
</script>

<template>
    <Head title="Dashboard — WACA Admin" />

    <AuthenticatedLayout title="Internal Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Dashboard</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Welcome back, {{ $page.props.auth.user.name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-medium text-warm-600 border border-warm-200 rounded-lg hover:bg-warm-50 transition-colors">
                        <!-- <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg> -->
                        View Public Site
                    </a>
                </div>
            </div>
        </template>

        <!-- Quick actions -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
            <template v-for="link in quickLinks" :key="link.label">
                <a v-if="can[link.permission]" :href="link.href"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors shadow-sm"
                   :class="link.color">
                    <span class="text-base">{{ link.icon }}</span>
                    {{ link.label }}
                </a>
            </template>
        </div>

        <!-- Stats grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div v-for="stat in stats" :key="stat.label"
                 class="p-5 rounded-2xl border-2 transition-shadow hover:shadow-md"
                 :class="stat.color">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl" :class="stat.iconBg">
                        {{ stat.icon }}
                    </div>
                </div>
                <p class="text-2xl font-bold font-display" :class="stat.valueColor">{{ stat.value }}</p>
                <p class="text-xs font-medium text-warm-600 mt-0.5">{{ stat.label }}</p>
                <p class="text-xs text-warm-400 mt-1">{{ stat.change }}</p>
            </div>
        </div>

        <!-- Main content grid -->
        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Recent activity -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 flex items-center justify-between">
                    <h2 class="font-semibold text-warm-900 text-sm">Recent Activity</h2>
                    <a href="#" class="text-xs text-brand-600 hover:text-brand-800 font-medium transition-colors">View all</a>
                </div>
                <div class="divide-y divide-warm-50">
                    <div v-for="item in recentActivity" :key="item.action"
                         class="flex items-start gap-3 px-6 py-4 hover:bg-warm-50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0 mt-0.5" :class="item.color">
                            {{ item.icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-warm-900">{{ item.action }}</p>
                            <p class="text-xs text-warm-500 mt-0.5 truncate">{{ item.detail }}</p>
                        </div>
                        <span class="text-xs text-warm-400 shrink-0 ml-2 mt-0.5">{{ item.time }}</span>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="space-y-5">

                <!-- Charity info card -->
                <div class="bg-gradient-to-br from-brand-600 to-brand-700 rounded-2xl p-5 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/media/logo.png" alt="WACA" class="h-12 w-auto brightness-0 invert opacity-90" />
                        <div>
                            <p class="font-semibold text-sm font-display">Woodvale &amp; Ainsdale</p>
                            <p class="text-white/60 text-xs">Community Centre</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white/15 rounded-xl p-3">
                            <p class="text-lg font-bold font-display">1,248</p>
                            <p class="text-white/70 text-xs">Members</p>
                        </div>
                        <div class="bg-white/15 rounded-xl p-3">
                            <p class="text-lg font-bold font-display">152</p>
                            <p class="text-white/70 text-xs">Volunteers</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming events mini list -->
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-warm-100">
                        <h2 class="font-semibold text-warm-900 text-sm">Next Events</h2>
                    </div>
                    <div class="divide-y divide-warm-50">
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="text-center shrink-0">
                                <p class="text-xs font-bold text-brand-600">APR</p>
                                <p class="text-lg font-bold text-warm-900 leading-none">12</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-warm-800 truncate">Spring Community Fair</p>
                                <p class="text-xs text-warm-400">10:00 – Main Hall</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="text-center shrink-0">
                                <p class="text-xs font-bold text-sky-600">APR</p>
                                <p class="text-lg font-bold text-warm-900 leading-none">16</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-warm-800 truncate">Senior Coffee Morning</p>
                                <p class="text-xs text-warm-400">09:30 – Café</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="text-center shrink-0">
                                <p class="text-xs font-bold text-purple-600">APR</p>
                                <p class="text-lg font-bold text-warm-900 leading-none">25</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-warm-800 truncate">Youth Film Night</p>
                                <p class="text-xs text-warm-400">18:00 – Activity Room</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-warm-100">
                        <a href="#" class="text-xs font-medium text-brand-600 hover:text-brand-800 transition-colors">Manage events →</a>
                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
