<script setup lang="ts">
import ElasticMainMenu from '@/components/ElasticMainMenu.vue';
import HeroSection from '@/sections/HeroSection.vue';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';

const AboutSection = defineAsyncComponent(
    () => import('@/sections/AboutSection.vue'),
);
const TechnologySection = defineAsyncComponent(
    () => import('@/sections/TechnologySection.vue'),
);
const ContactSection = defineAsyncComponent(
    () => import('@/sections/ContactSection.vue'),
);
const FooterSection = defineAsyncComponent(
    () => import('@/sections/FooterSection.vue'),
);

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
        <meta
            name="description"
            content="Site portfolio by Max Andreev Full-Stack Developer."
        />
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col items-center justify-center">
        <ElasticMainMenu />

        <main class="flex w-full flex-col">
            <HeroSection />
            <AboutSection />
            <TechnologySection />
            <ContactSection />
        </main>

        <FooterSection />

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
