<script setup>
import { ref, onMounted } from 'vue';

const STORAGE_KEY = 'cookie_consent';
const visible = ref(false);
const showDetails = ref(false);

onMounted(() => {
    try {
        if (!localStorage.getItem(STORAGE_KEY)) {
            visible.value = true;
        }
    } catch (e) {
        visible.value = true;
    }
});

function save(choice) {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({
            choice,
            at: new Date().toISOString(),
        }));
    } catch (e) {}
    visible.value = false;
    window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: { choice } }));
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div
            v-if="visible"
            role="dialog"
            aria-live="polite"
            aria-label="Cookie consent"
            class="fixed inset-x-0 bottom-0 z-50 px-4 pb-4 sm:px-6 sm:pb-6"
        >
            <div class="mx-auto max-w-4xl rounded-2xl bg-warm-900 text-warm-50 shadow-2xl ring-1 ring-warm-50/10">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-accent-400 mb-1">We value your privacy</p>
                            <p class="text-sm leading-relaxed text-warm-50/80">
                                We use cookies to keep the site working and to understand how visitors use it.
                                Essential cookies are always on. You can accept all cookies, reject non-essential ones,
                                or read more below.
                                <a href="/privacy" class="underline hover:text-accent-400 transition-colors">Privacy Policy</a>.
                            </p>

                            <div v-if="showDetails" class="mt-4 space-y-3 text-xs text-warm-50/70">
                                <div>
                                    <p class="font-semibold text-warm-50">Essential</p>
                                    <p>Required for the site to function (session, security, CSRF). Always on.</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-warm-50">Analytics</p>
                                    <p>Help us understand how the site is used so we can improve it. Optional.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 sm:w-56 sm:flex-shrink-0">
                            <button
                                type="button"
                                @click="save('all')"
                                class="rounded-lg bg-accent-400 px-4 py-2 text-sm font-semibold text-warm-900 hover:bg-accent-400/90 transition-colors"
                            >
                                Accept all
                            </button>
                            <button
                                type="button"
                                @click="save('essential')"
                                class="rounded-lg border border-warm-50/20 px-4 py-2 text-sm font-medium text-warm-50 hover:bg-warm-50/5 transition-colors"
                            >
                                Reject non-essential
                            </button>
                            <button
                                type="button"
                                @click="showDetails = !showDetails"
                                class="text-xs text-warm-50/60 underline hover:text-warm-50 transition-colors"
                            >
                                {{ showDetails ? 'Hide details' : 'Manage preferences' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
