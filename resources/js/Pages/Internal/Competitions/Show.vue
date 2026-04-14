<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const page = usePage();

const props = defineProps({
    competition: { type: Object, required: true },
    submissions: { type: Array,  required: true },
});

const statusLabel = {
    open:    { text: 'Open',    cls: 'bg-emerald-100 text-emerald-700' },
    closed:  { text: 'Closed',  cls: 'bg-warm-100 text-warm-500' },
    results: { text: 'Results', cls: 'bg-brand-100 text-brand-700' },
};

function declareWinner(submissionId, name) {
    if (confirm(`Declare "${name}" as the winner? This will set the competition to Results.`)) {
        router.post(`/internal/competitions/${props.competition.id}/submissions/${submissionId}/winner`);
    }
}

function deleteSubmission(submissionId, name) {
    if (confirm(`Delete entry "${name}"? This cannot be undone.`)) {
        router.delete(`/internal/competitions/${props.competition.id}/submissions/${submissionId}`);
    }
}
</script>

<template>
    <Head :title="`Entries — ${competition.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/competitions"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold text-warm-900 font-display truncate">{{ competition.title }}</h1>
                        <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-semibold"
                              :class="statusLabel[competition.status].cls">
                            {{ statusLabel[competition.status].text }}
                        </span>
                    </div>
                    <p class="text-sm text-warm-500 mt-0.5">{{ competition.submissions_count }} entr{{ competition.submissions_count === 1 ? 'y' : 'ies' }}</p>
                </div>
                <Link :href="`/internal/competitions/${competition.id}/edit`"
                      class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit competition
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
        <div v-if="!submissions.length"
             class="text-center py-20 bg-warm-50 rounded-2xl border border-warm-200">
            <span class="text-4xl block mb-3">📭</span>
            <p class="font-semibold text-warm-800 mb-1">No entries yet</p>
            <p class="text-sm text-warm-500">Entries will appear here once members submit.</p>
        </div>

        <!-- Submissions grid -->
        <div v-else class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="submission in submissions" :key="submission.id"
                 class="bg-white border rounded-2xl overflow-hidden shadow-sm flex flex-col"
                 :class="submission.is_winner ? 'border-amber-300 ring-2 ring-amber-200' : 'border-warm-200'">

                <!-- Winner badge -->
                <div v-if="submission.is_winner"
                     class="flex items-center gap-1.5 px-4 py-2 bg-amber-50 border-b border-amber-200 text-xs font-semibold text-amber-700">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Winner
                </div>

                <!-- Image -->
                <img v-if="submission.image_url" :src="submission.image_url" :alt="submission.name"
                     class="w-full h-auto block" />
                <div v-else class="aspect-video bg-warm-100 flex items-center justify-center text-3xl text-warm-300">
                    🖼
                </div>

                <!-- Info -->
                <div class="p-4 flex-1 flex flex-col gap-3">
                    <div>
                        <p class="text-sm font-semibold text-warm-900">{{ submission.name }}</p>
                        <p v-if="submission.description" class="text-xs text-warm-500 mt-1">{{ submission.description }}</p>
                    </div>
                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-warm-100">
                        <span class="text-xs text-warm-400">{{ submission.user.name }} · {{ submission.created_at }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="!submission.is_winner"
                                @click="declareWinner(submission.id, submission.name)"
                                class="flex-1 px-3 py-1.5 text-xs font-semibold text-amber-700 border border-amber-300 rounded-lg hover:bg-amber-50 transition-colors">
                            Declare winner
                        </button>
                        <button @click="deleteSubmission(submission.id, submission.name)"
                                class="px-3 py-1.5 text-xs font-medium text-rose-600 border border-rose-200 rounded-lg hover:bg-rose-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
