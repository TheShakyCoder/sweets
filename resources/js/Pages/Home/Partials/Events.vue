<script setup>
const events = [
    {
        date: 'SEASONAL',
        title: 'Halloween Trick or Treat',
        time: 'October Half Term',
        location: 'In-Store',
        tag: 'Seasonal',
        tagColor: 'bg-orange-100 text-orange-700',
        accent: 'bg-orange-500',
    },
    {
        date: 'SEASONAL',
        title: 'Santa\'s Grotto Experience',
        time: 'December',
        location: 'In-Store',
        tag: 'Christmas',
        tagColor: 'bg-red-100 text-red-700',
        accent: 'bg-red-500',
    },
    {
        date: 'YEAR ROUND',
        title: 'Character Visits',
        time: 'Check our socials',
        location: 'In-Store',
        tag: 'Kids',
        tagColor: 'bg-purple-100 text-purple-700',
        accent: 'bg-candy-purple',
    },
    {
        date: 'AVAILABLE NOW',
        title: 'Pick \'n\' Mix Trailer Hire',
        time: 'Weddings & Events',
        location: 'Your Venue',
        tag: 'Hire',
        tagColor: 'bg-brand-100 text-brand-700',
        accent: 'bg-brand-500',
    },
];
</script>

<template>
    <section id="events" class="py-20 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-14">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-3 block">Events &amp; Hire</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-warm-900">
                        What's <span class="text-gradient-candy">on</span>
                    </h2>
                </div>
                <a href="#contact"
                    class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-brand-500 hover:text-brand-700 transition-colors">
                    Get in touch to book
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div v-for="event in events" :key="event.title"
                    class="bg-white rounded-2xl overflow-hidden border border-warm-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="h-2 w-full" :class="event.accent"></div>
                    <div class="p-5">
                        <p class="text-xs font-bold tracking-wider text-warm-400 mb-3">{{ event.date }}</p>
                        <h3 class="font-semibold text-warm-900 mb-2 leading-snug">{{ event.title }}</h3>
                        <div class="flex items-center gap-1.5 text-xs text-warm-500 mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ event.time }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-warm-500 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ event.location }}
                        </div>
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold"
                            :class="event.tagColor">
                            {{ event.tag }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
