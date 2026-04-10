<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
});
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-warm-50 font-sans antialiased">

        <!-- Top Navigation -->
        <nav class="bg-white border-b border-warm-200 shadow-sm sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">

                    <!-- Logo + Brand -->
                    <div class="flex items-center gap-4">
                        <Link :href="route('home')" class="flex items-center gap-3">
                            <img src="/media/logo.png" alt="WACA" class="h-10 w-auto" />
                            <div class="hidden sm:block">
                                <p class="text-sm font-semibold text-brand-700 leading-tight font-display">WACA</p>
                                <p class="text-xs text-warm-400 leading-tight">{{ title }}</p>
                            </div>
                        </Link>

                        <!-- Desktop nav links -->
                        <div class="hidden md:flex items-center gap-1 ml-6">
                            <Link :href="route('dashboard')"
                                  class="px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                                  :class="route().current('dashboard')
                                      ? 'bg-brand-50 text-brand-700'
                                      : 'text-warm-600 hover:bg-warm-100 hover:text-warm-900'">
                                Dashboard
                            </Link>
                            <Link :href="route('admin.dashboard')"
                                  v-if="$page.props.can?.admin"
                                  class="px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                                  :class="route().current('admin.dashboard')
                                      ? 'bg-brand-50 text-brand-700'
                                      : 'text-warm-600 hover:bg-warm-100 hover:text-warm-900'">
                                Admin
                            </Link>
                            <Link href="/admin/media"
                                  class="px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                                  :class="$page.url.startsWith('/admin/media')
                                      ? 'bg-brand-50 text-brand-700'
                                      : 'text-warm-600 hover:bg-warm-100 hover:text-warm-900'">
                                Media
                            </Link>
                            <Link href="/admin/menu-items"
                                  class="px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                                  :class="$page.url.startsWith('/admin/menu-items')
                                      ? 'bg-brand-50 text-brand-700'
                                      : 'text-warm-600 hover:bg-warm-100 hover:text-warm-900'">
                                Menu
                            </Link>
                        </div>
                    </div>

                    <!-- Right side: user dropdown -->
                    <div class="hidden sm:flex items-center gap-3">
                        <!-- View site -->
                        <a href="/"
                           class="text-xs text-warm-500 hover:text-brand-600 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View site
                        </a>

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button"
                                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-warm-100 transition-colors text-sm font-medium text-warm-700">
                                    <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="max-w-[120px] truncate">{{ $page.props.auth.user.name }}</span>
                                    <svg class="w-4 h-4 text-warm-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <div class="px-4 py-3 border-b border-warm-100">
                                    <p class="text-xs text-warm-500">Signed in as</p>
                                    <p class="text-sm font-medium text-warm-900 truncate">{{ $page.props.auth.user.email }}</p>
                                </div>
                                <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2 text-sm text-warm-700">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    My Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button"
                                              class="flex items-center gap-2 text-sm text-rose-600 w-full">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile hamburger -->
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="sm:hidden p-2 rounded-lg text-warm-500 hover:bg-warm-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile dropdown -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-warm-100">
                <div class="px-4 py-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink href="/admin/media" :active="$page.url.startsWith('/admin/media')">
                        Media
                    </ResponsiveNavLink>
                    <ResponsiveNavLink href="/admin/menu-items" :active="$page.url.startsWith('/admin/menu-items')">
                        Menu
                    </ResponsiveNavLink>
                </div>
                <div class="border-t border-warm-100 px-4 py-3">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-brand-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-warm-900">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-warm-500">{{ $page.props.auth.user.email }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page heading slot -->
        <header v-if="$slots.header" class="bg-white border-b border-warm-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-5">
                <slot name="header" />
            </div>
        </header>

        <!-- Main content -->
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <slot />
        </main>

    </div>
</template>
