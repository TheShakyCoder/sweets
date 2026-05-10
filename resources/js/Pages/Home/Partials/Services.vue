<script setup>
const services = [
    {
        icon: '&#127852;',
        title: 'Pick \'n\' Mix',
        desc: 'The biggest self-serve pick \'n\' mix in the North West! Over 90 varieties of fizzy, sour, sweet, and jelly sweets to fill your bag.',
        color: 'bg-brand-50 border-brand-200',
        iconBg: 'bg-brand-100',
    },
    {
        icon: '&#127471;&#127480;',
        title: 'American Candy',
        desc: 'A huge selection of American imports — from Reese\'s and Nerds to Warheads, Jolly Ranchers, and the latest viral snacks.',
        color: 'bg-sky-50 border-sky-200',
        iconBg: 'bg-sky-100',
    },
    {
        icon: '&#127854;',
        title: 'Retro Sweets',
        desc: 'Take a trip down memory lane with our retro selection. Drumsticks, Black Jacks, Fruit Salads, and all the classics you grew up with.',
        color: 'bg-purple-50 border-purple-200',
        iconBg: 'bg-purple-100',
    },
    {
        icon: '&#127873;',
        title: 'Gift Ideas',
        desc: 'Sweet cones, party pouches, birthday gifts, and hampers — perfect for any occasion. We can customise to your taste.',
        color: 'bg-accent-50 border-amber-200',
        iconBg: 'bg-amber-100',
    },
    {
        icon: '&#127880;',
        title: 'Events & Parties',
        desc: 'From wedding graze tables to our pick \'n\' mix trailer — we bring the sweetness to your special occasion. Character visits and seasonal events in-store too!',
        color: 'bg-rose-50 border-rose-200',
        iconBg: 'bg-rose-100',
    },
    {
        icon: '&#127851;',
        title: 'Olde World Sweets',
        desc: 'Sarsaparilla tablets, liquorice root, pear drops, and more — our olde world selection brings back the sweets your grandparents loved.',
        color: 'bg-emerald-50 border-emerald-200',
        iconBg: 'bg-emerald-100',
    },
];
</script>

<template>
    <section id="sweets" class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-500 mb-3 block">Our Sweets</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-warm-900">
                    Something sweet for <span class="text-gradient-candy">everyone</span>
                </h2>
                <p class="text-warm-500 mt-4 max-w-xl mx-auto">
                    From fizzy pick 'n' mix to American imports and nostalgic retro treats — our shelves are packed.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="service in services" :key="service.title"
                    class="group p-6 rounded-2xl border-2 transition-all hover:shadow-lg hover:-translate-y-1 cursor-default"
                    :class="service.color">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4"
                        :class="service.iconBg" v-html="service.icon">
                    </div>
                    <h3 class="font-semibold text-warm-900 text-base mb-2">{{ service.title }}</h3>
                    <p class="text-sm text-warm-600 leading-relaxed">{{ service.desc }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
