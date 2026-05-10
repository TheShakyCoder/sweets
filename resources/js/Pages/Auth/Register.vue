<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout title="Create an account" subtitle="Register for access to the Lollipops portal">
        <Head title="Register" />

        <form @submit.prevent="submit" class="space-y-5">

            <!-- Full name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-warm-700 mb-1.5">
                    Full name
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Jane Smith"
                    class="w-full px-4 py-2.5 text-sm border rounded-xl transition
                           focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent
                           placeholder-warm-300 text-warm-900"
                    :class="form.errors.name ? 'border-rose-400 bg-rose-50' : 'border-warm-200 bg-white'"
                />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-warm-700 mb-1.5">
                    Email address
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="w-full px-4 py-2.5 text-sm border rounded-xl transition
                           focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent
                           placeholder-warm-300 text-warm-900"
                    :class="form.errors.email ? 'border-rose-400 bg-rose-50' : 'border-warm-200 bg-white'"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password row — side by side on sm+ -->
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-xs font-semibold text-warm-700 mb-1.5">
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 text-sm border rounded-xl transition
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent
                               placeholder-warm-300 text-warm-900"
                        :class="form.errors.password ? 'border-rose-400 bg-rose-50' : 'border-warm-200 bg-white'"
                    />
                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-warm-700 mb-1.5">
                        Confirm password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 text-sm border rounded-xl transition
                               focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent
                               placeholder-warm-300 text-warm-900"
                        :class="form.errors.password_confirmation ? 'border-rose-400 bg-rose-50' : 'border-warm-200 bg-white'"
                    />
                    <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 bg-brand-600 text-white font-semibold rounded-xl text-sm
                       hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:ring-offset-2
                       transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 mt-2">
                <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                {{ form.processing ? 'Creating account…' : 'Create account' }}
            </button>

        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-warm-200"></div>
            <span class="text-xs text-warm-400">already registered?</span>
            <div class="flex-1 h-px bg-warm-200"></div>
        </div>

        <p class="text-center text-sm text-warm-500">
            <Link :href="route('login')" class="text-brand-600 hover:text-brand-800 font-medium transition-colors">
                Sign in to your account
            </Link>
        </p>

    </GuestLayout>
</template>
