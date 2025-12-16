<script setup lang="ts">
import ElasticMainMenu from '@/components/ElasticMainMenu.vue';
import AboutSection from '@/sections/AboutSection.vue';
import ContactSection from '@/sections/ContactSection.vue';
import FooterSection from '@/sections/FooterSection.vue';
import HeroSection from '@/sections/HeroSection.vue';
import TechnologySection from '@/sections/TechnologySection.vue';
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Home">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col items-center justify-center">
        <ElasticMainMenu />

        <HeroSection />

        <AboutSection />

        <TechnologySection />

        <ContactSection />

        <FooterSection />

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
