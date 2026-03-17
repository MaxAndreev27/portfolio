<script setup lang="ts">
import ChatWidget from '@/components/ChatWidget.vue';
import FloatingMainMenu from '@/components/FloatingMainMenu.vue';
import HeroSection from '@/sections/HeroSection.vue';
import {
    AboutSettings,
    ContactSettings,
    FooterSettings,
    HeroSettings,
    MenuSettings,
    Project,
    ProjectsSettings,
    SeoSettings,
    TechnologySettings,
} from '@/types';
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
        menuSettings: MenuSettings;
        projects: Project[];
        heroSettings: HeroSettings;
        aboutSettings: AboutSettings;
        projectsSettings: ProjectsSettings;
        technologySettings: TechnologySettings;
        contactSettings: ContactSettings;
        footerSettings: FooterSettings;
        seoSettings: SeoSettings;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Home">
        <title>{{ seoSettings.seo_title || heroSettings.hero_title }}</title>
        <meta
            name="description"
            :content="
                seoSettings.seo_description || heroSettings.hero_description
            "
        />
        <meta
            property="og:title"
            :content="seoSettings.seo_title || heroSettings.hero_title"
        />
        <meta
            property="og:description"
            :content="
                seoSettings.seo_description || heroSettings.hero_description
            "
        />
    </Head>

    <div class="flex min-h-screen flex-col items-center justify-center">
        <FloatingMainMenu :menuSettings="menuSettings" />

        <main class="flex w-full flex-col">
            <HeroSection
                v-if="heroSettings.hero_is_featured"
                :heroSettings="heroSettings"
            />
            <AboutSection
                v-if="aboutSettings.about_is_featured"
                :aboutSettings="aboutSettings"
            />
            <ProjectsSection
                v-if="projectsSettings.projects_is_featured"
                :projects="projects"
                :projectsSettings="projectsSettings"
            />
            <TechnologySection
                v-if="technologySettings.technology_is_featured"
            />
            <ContactSection
                v-if="contactSettings.contact_is_featured"
                :contactSettings="contactSettings"
            />
        </main>

        <FooterSection :footerSettings="footerSettings" />

        <ChatWidget />

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
