<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    navLinks: Array,
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object,
});

const mobileMenuOpen = ref(false);
const openDropdown = ref(null);

function closeDropdowns() {
    openDropdown.value = null;
}

function handleClickOutside(e) {
    if (e.target.closest('nav') === null) closeDropdowns();
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <header>
        <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm border-b border-warm-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between">

                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-3 shrink-0">
                        <img src="/media/logo.png" alt="Acme Sweets Logo" class="h-14 w-auto" />
                        <div class="hidden md:block">
                            <p class="text-lg font-semibold text-brand-600 leading-tight font-display">Acme</p>
                            <p class="text-md text-candy-purple tracking-wider leading-tight font-display">Sweets</p>
                        </div>
                    </a>

                    <!-- Desktop nav -->
                    <div class="hidden lg:flex items-center gap-1">
                        <div v-for="(link, idx) in navLinks" :key="idx" class="relative group">
                            <Link v-if="!link.children || link.children.length === 0" :href="link.href"
                                class="px-2 py-2 text-sm font-medium text-warm-700 rounded-lg hover:bg-brand-50 hover:text-brand-600 transition-colors">
                                {{ link.label }}
                            </Link>
                            <button v-else type="button"
                                class="px-2 py-2 text-sm font-medium text-warm-700 rounded-lg hover:bg-brand-50 hover:text-brand-600 transition-colors flex items-center gap-1"
                                @click="openDropdown = openDropdown === idx ? null : idx">
                                {{ link.label }}
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openDropdown === idx }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </button>

                            <div v-if="link.children && link.children.length > 0 && openDropdown === idx"
                                 class="absolute left-0 mt-0 w-48 bg-white border border-warm-200 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <Link v-for="(child, cidx) in link.children" :key="cidx" :href="child.href"
                                   class="block px-4 py-2.5 text-sm text-warm-700 hover:bg-brand-50 hover:text-brand-600 transition-colors first:rounded-t-xl last:rounded-b-xl">
                                    {{ child.label }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- CTA buttons -->
                    <div class="hidden lg:flex items-center gap-3">
                        <Link v-if="canLogin && !$page.props.auth?.user" :href="route('login')"
                            class="px-2 py-2 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                        Login
                        </Link>
                        <Link v-if="canRegister && !$page.props.auth?.user" :href="route('register')"
                            class="px-2 py-2 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                        Register
                        </Link>
                        <Link v-if="canLogin && $page.props.auth?.user" :href="route('dashboard')"
                            class="px-2 py-2 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                        Dashboard
                        </Link>
                        <a href="#contact"
                            class="px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition-all shadow-md" style="background: linear-gradient(135deg, #ff1a75, #d946ef)">
                            Visit Us
                        </a>
                    </div>

                    <!-- Mobile hamburger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-lg text-warm-600 hover:bg-warm-100 transition-colors">
                        <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileMenuOpen" class="lg:hidden border-t border-warm-100 bg-white px-4 pb-4 pt-2 space-y-1">
                <template v-for="(link, idx) in navLinks" :key="idx">
                    <a v-if="!link.children || link.children.length === 0" :href="link.href" @click="mobileMenuOpen = false"
                        class="block px-4 py-2.5 text-sm font-medium text-warm-700 rounded-lg hover:bg-brand-50 hover:text-brand-600 transition-colors">
                        {{ link.label }}
                    </a>
                    <div v-else>
                        <button @click="openDropdown = openDropdown === idx ? null : idx"
                            class="w-full text-left px-4 py-2.5 text-sm font-medium text-warm-700 rounded-lg hover:bg-brand-50 hover:text-brand-600 transition-colors flex items-center justify-between">
                            {{ link.label }}
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openDropdown === idx }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>
                        <div v-if="openDropdown === idx" class="ml-4 mt-1 space-y-1 border-l-2 border-warm-100">
                            <a v-for="(child, cidx) in link.children" :key="cidx" :href="child.href" @click="mobileMenuOpen = false"
                               class="block px-4 py-2.5 text-sm text-warm-600 hover:text-brand-600 transition-colors">
                                {{ child.label }}
                            </a>
                        </div>
                    </div>
                </template>
                <div class="pt-2 border-t border-warm-100 flex flex-col gap-2">
                    <Link v-if="canLogin && !$page.props.auth?.user" :href="route('login')"
                        class="block px-4 py-2.5 text-sm font-medium text-brand-600 rounded-lg hover:bg-brand-50 transition-colors">
                    Login
                    </Link>
                    <a href="#contact"
                        class="block text-center px-4 py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition-colors">
                        Visit Us
                    </a>
                </div>
            </div>
        </nav>
    </header>
</template>
