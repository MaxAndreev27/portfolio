<script setup lang="ts">
import FloatingMainMenu from '@/components/FloatingMainMenu.vue';
import HeroSection from '@/sections/HeroSection.vue';
import { Project } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';

const AboutSection = defineAsyncComponent(
    () => import('@/sections/AboutSection.vue'),
);
const ProjectsSection = defineAsyncComponent(
    () => import('@/sections/ProjectsSection.vue'),
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
        projects: Project[];
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
    </Head>

    <div class="flex min-h-screen flex-col items-center justify-center">
        <FloatingMainMenu />

        <main class="flex w-full flex-col">
            <HeroSection />
            <AboutSection />
            <ProjectsSection :projects="projects"/>
            <TechnologySection />
            <ContactSection />
        </main>

        <FooterSection />

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
