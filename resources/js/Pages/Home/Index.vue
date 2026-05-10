<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Hero from '@/Pages/Home/Partials/Hero.vue';
import Stats from '@/Pages/Home/Partials/Stats.vue';
import About from '@/Pages/Home/Partials/About.vue';
import Services from '@/Pages/Home/Partials/Services.vue';
import Events from '@/Pages/Home/Partials/Events.vue';
import News from '@/Pages/Home/Partials/News.vue';
import Volunteer from '@/Pages/Home/Partials/Volunteer.vue';
import Contact from '@/Pages/Home/Partials/Contact.vue';
import InstagramFeed from '@/Components/InstagramFeed.vue';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    site: { type: Object },
});

const navLinks = page.props.site.nav_links;
</script>

<template>

    <Head :title="site.fullname" />

    <div class="font-sans antialiased text-warm-800 bg-white">

        <Header :navLinks="navLinks" :canLogin="canLogin" :canRegister="canRegister" />

        <!-- HERO -->
        <Hero :site="site" />

        <!-- STATS -->
        <Stats />

        <!-- ABOUT -->
        <About />

        <!-- OUR SWEETS -->
        <Services />

        <!-- EVENTS & HIRE -->
        <Events />

        <!-- NEWS -->
        <News />

        <!-- INSTAGRAM FEED -->
        <InstagramFeed />

        <!-- WEDDINGS & EVENTS CTA -->
        <Volunteer />

        <!-- CONTACT -->
        <Contact :site="site" />

        <!-- FOOTER -->
        <Footer :navLinks="navLinks" />

    </div>
</template>
