<script setup lang="ts">
import MainMenu from '@/components/MainMenu.vue';
import AboutSection from '@/sections/AboutSection.vue';
import HeroSection from '@/sections/HeroSection.vue';
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
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col items-center lg:justify-center">
        <MainMenu />

        <HeroSection />

        <AboutSection />

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
