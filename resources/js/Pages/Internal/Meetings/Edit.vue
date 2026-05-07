<script setup>
import { useForm, Link, Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    meeting:    { type: Object, required: true },
    activities: { type: Array,  required: true },
});

function toLocalInput(iso) {
    if (!iso) return '';
    return new Date(iso).toISOString().slice(0, 16);
}

const form = useForm({
    title:              props.meeting.title,
    activity_id:        props.meeting.activity_id ?? '',
    starts_at:          toLocalInput(props.meeting.starts_at),
    ends_at:            toLocalInput(props.meeting.ends_at),
    location:           props.meeting.location ?? '',
    description:        props.meeting.description ?? '',
    recurrence:         props.meeting.recurrence ?? '',
    recurrence_ends_at: props.meeting.recurrence_ends_at ?? '',
});

function submit() {
    form.transform(data => ({
        ...data,
        activity_id:        data.activity_id || null,
        recurrence:         data.recurrence || null,
        recurrence_ends_at: data.recurrence_ends_at || null,
        ends_at:            data.ends_at || null,
    })).put(route('internal.meetings.update', props.meeting.id));
}

function destroy() {
    if (confirm('Delete this meeting?')) {
        router.delete(route('internal.meetings.destroy', props.meeting.id));
    }
}
</script>

<template>
    <Head :title="`Edit: ${meeting.title}`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('internal.meetings.index')"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Edit Meeting</h1>
                    <p class="text-sm text-warm-500 mt-0.5">{{ meeting.title }}</p>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="max-w-2xl">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Title</label>
                    <input v-model="form.title" type="text" required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                           :class="form.errors.title ? 'border-rose-300' : 'border-warm-200'" />
                    <p v-if="form.errors.title" class="mt-1.5 text-xs text-rose-600">{{ form.errors.title }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Activity (optional)</label>
                    <select v-model="form.activity_id"
                            class="w-full px-3 py-2.5 text-sm border border-warm-200 rounded-xl bg-white text-warm-900">
                        <option value="">None</option>
                        <option v-for="a in activities" :key="a.id" :value="a.id">{{ a.title }}</option>
                    </select>
                </div>

                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">All times are in UTC, not British Summer Time (BST). During BST, subtract one hour from local time.</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Starts at (UTC)</label>
                        <input v-model="form.starts_at" type="datetime-local" required
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                               :class="form.errors.starts_at ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.starts_at" class="mt-1.5 text-xs text-rose-600">{{ form.errors.starts_at }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Ends at (UTC, optional)</label>
                        <input v-model="form.ends_at" type="datetime-local"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                               :class="form.errors.ends_at ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.ends_at" class="mt-1.5 text-xs text-rose-600">{{ form.errors.ends_at }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Location (optional)</label>
                    <input v-model="form.location" type="text" placeholder="e.g. Main Hall"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                  focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                           :class="form.errors.location ? 'border-rose-300' : 'border-warm-200'" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-warm-700 mb-1.5">Description (optional)</label>
                    <textarea v-model="form.description" rows="3"
                              class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900 resize-none
                                     focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition"
                              :class="form.errors.description ? 'border-rose-300' : 'border-warm-200'"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Recurrence (optional)</label>
                        <select v-model="form.recurrence"
                                class="w-full px-3 py-2.5 text-sm border border-warm-200 rounded-xl bg-white text-warm-900">
                            <option value="">None</option>
                            <option value="weekly">Weekly</option>
                            <option value="fortnightly">Fortnightly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div v-if="form.recurrence">
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Recurrence ends</label>
                        <input v-model="form.recurrence_ends_at" type="date"
                               class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl
                                   hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                        {{ form.processing ? 'Saving…' : 'Save Changes' }}
                    </button>
                    <Link :href="route('internal.meetings.index')"
                          class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                        Cancel
                    </Link>
                    <button type="button" @click="destroy"
                            class="px-6 py-2.5 text-sm font-medium text-rose-600 border border-rose-200 rounded-xl hover:bg-rose-50 transition-colors ml-auto">
                        Delete
                    </button>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
