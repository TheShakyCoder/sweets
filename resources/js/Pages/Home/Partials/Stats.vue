<script setup>
const stats = [
    { value: '90+', label: 'Pick \'n\' mix varieties', color: 'from-brand-400 to-brand-600', shadow: 'shadow-brand-400/20' },
    { value: '100s', label: 'Of American imports', color: 'from-candy-purple to-candy-magenta', shadow: 'shadow-candy-purple/20' },
    { value: '1000s', label: 'Happy customers', color: 'from-candy-orange to-candy-red', shadow: 'shadow-candy-orange/20' },
    { value: '5⭐', label: 'Rated on Google', color: 'from-accent-400 to-accent-600', shadow: 'shadow-accent-400/20' },
];
</script>

<template>
    <section class="bg-white py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <div v-for="stat in stats" :key="stat.label"
                    class="relative text-center p-7 rounded-2xl bg-white border border-warm-100 shadow-lg overflow-hidden group hover:-translate-y-1 transition-all duration-200"
                    :class="stat.shadow">
                    <!-- Accent stripe top -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r" :class="stat.color"></div>
                    <p class="font-display text-4xl font-extrabold bg-gradient-to-r bg-clip-text text-transparent"
                       :class="stat.color"
                       v-html="stat.value"></p>
                    <p class="text-sm text-warm-500 mt-2 font-medium">{{ stat.label }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
