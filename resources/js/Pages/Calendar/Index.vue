<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();
const navLinks = page.props.site.nav_links;

const props = defineProps({
    occurrences: { type: Array,  required: true },
    activities:  { type: Array,  required: true },
    month:       { type: Number, required: true },
    year:        { type: Number, required: true },
});

const monthName = computed(() => {
    return new Date(props.year, props.month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
});

function prevMonth() {
    let m = props.month - 1;
    let y = props.year;
    if (m < 1) { m = 12; y--; }
    router.get('/calendar', { month: m, year: y }, { preserveState: true });
}

function nextMonth() {
    let m = props.month + 1;
    let y = props.year;
    if (m > 12) { m = 1; y++; }
    router.get('/calendar', { month: m, year: y }, { preserveState: true });
}

const groupedByDate = computed(() => {
    const groups = {};
    for (const occ of props.occurrences) {
        const dateKey = new Date(occ.starts_at).toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' });
        if (!groups[dateKey]) groups[dateKey] = [];
        groups[dateKey].push(occ);
    }
    return groups;
});

const calendarDays = computed(() => {
    const firstOfMonth = new Date(props.year, props.month - 1, 1);
    const lastOfMonth = new Date(props.year, props.month, 0);
    const startDay = firstOfMonth.getDay() === 0 ? 6 : firstOfMonth.getDay() - 1;
    const totalDays = lastOfMonth.getDate();

    const days = [];

    for (let i = 0; i < startDay; i++) {
        days.push({ day: null, events: [] });
    }

    for (let d = 1; d <= totalDays; d++) {
        const dateStr = `${props.year}-${String(props.month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const dayEvents = props.occurrences.filter(occ => occ.starts_at.slice(0, 10) === dateStr);
        days.push({ day: d, events: dayEvents, isToday: isToday(d) });
    }

    return days;
});

function isToday(day) {
    const now = new Date();
    return now.getFullYear() === props.year && now.getMonth() + 1 === props.month && now.getDate() === day;
}

function formatTime(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
}
</script>

<template>
    <Head :title="`Calendar — ${monthName}`" />

    <div class="font-sans antialiased text-warm-800 bg-white">
        <Header :navLinks="navLinks" />

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Month navigation -->
            <div class="flex items-center justify-between mb-6">
                <button @click="prevMonth" class="p-2 rounded-lg hover:bg-warm-100 transition-colors text-warm-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h1 class="text-2xl font-bold text-warm-900 font-display">{{ monthName }}</h1>
                <button @click="nextMonth" class="p-2 rounded-lg hover:bg-warm-100 transition-colors text-warm-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Calendar grid -->
            <div class="bg-white border border-warm-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-7 bg-warm-50 border-b border-warm-200">
                    <div v-for="day in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" :key="day"
                         class="px-2 py-2 text-xs font-semibold text-warm-500 text-center">
                        {{ day }}
                    </div>
                </div>

                <div class="grid grid-cols-7">
                    <div v-for="(cell, i) in calendarDays" :key="i"
                         class="min-h-[5rem] border-b border-r border-warm-100 p-1.5 last:border-r-0"
                         :class="{ 'bg-warm-50/50': !cell.day }">

                        <div v-if="cell.day" class="h-full">
                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-medium rounded-full mb-1"
                                  :class="cell.isToday ? 'bg-brand-600 text-white' : 'text-warm-600'">
                                {{ cell.day }}
                            </span>
                            <div class="space-y-0.5">
                                <div v-for="event in cell.events.slice(0, 3)" :key="event.id + event.starts_at"
                                     class="text-xs px-1.5 py-0.5 rounded bg-brand-50 text-brand-700 truncate cursor-default"
                                     :title="`${formatTime(event.starts_at)} ${event.title}${event.location ? ' · ' + event.location : ''}`">
                                    <span class="font-medium">{{ formatTime(event.starts_at) }}</span>
                                    {{ event.title }}
                                </div>
                                <div v-if="cell.events.length > 3"
                                     class="text-xs text-warm-400 px-1.5">
                                    +{{ cell.events.length - 3 }} more
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List below the grid -->
            <div v-if="Object.keys(groupedByDate).length" class="mt-8 space-y-6">
                <h2 class="text-lg font-semibold text-warm-900 font-display">All Events This Month</h2>
                <div v-for="(events, dateLabel) in groupedByDate" :key="dateLabel">
                    <h3 class="text-sm font-semibold text-warm-500 uppercase tracking-wide mb-3">{{ dateLabel }}</h3>
                    <div class="space-y-3">
                        <div v-for="event in events" :key="event.id + event.starts_at"
                             class="bg-white border border-warm-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-warm-900">{{ event.title }}</h4>
                                    <p class="text-sm text-warm-500 mt-1">
                                        {{ formatTime(event.starts_at) }}
                                        <span v-if="event.ends_at"> – {{ formatTime(event.ends_at) }}</span>
                                        <span v-if="event.location"> · {{ event.location }}</span>
                                    </p>
                                    <p v-if="event.activity" class="text-xs text-brand-600 mt-1">{{ event.activity.title }}</p>
                                </div>
                                <span v-if="event.recurrence"
                                      class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium shrink-0">
                                    {{ event.recurrence }}
                                </span>
                            </div>
                            <p v-if="event.description" class="text-sm text-warm-600 mt-2">{{ event.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="mt-8 text-center py-12 text-warm-400">
                <p class="text-lg">No events scheduled this month.</p>
            </div>

        </div>

        <Footer />
    </div>
</template>
