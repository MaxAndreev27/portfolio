<script setup lang="ts">
import { BemineText } from '@/components/ui/bemine-text';
import { TimelineList } from '@/components/ui/timeline-list';
</script>

<template>
    <section id="main-about" class="main-about w-full px-5">
        <div
            class="container mx-auto my-15 md:my-20 flex max-w-4xl flex-col items-center justify-center"
        >
            <BemineText class="text-5xl font-bold md:text-6xl"
                >About me</BemineText
            >

            <TimelineList class="text-lg my-10 sm:text-xl md:text-2xl" />

            <ul
                class="flex w-full list-inside list-disc list-image-[url(/public/images/checkmark.svg)] flex-col items-start text-left text-xl sm:text-2xl md:text-3xl"
            >
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Programming Languages:</span>
                    PHP, JavaScript, TypeScript;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold"
                        >Frameworks, CMS & UI Libraries:</span
                    >
                    WordPress, Laravel (Blade, Livewire, Inertia), Next.js,
                    Nestjs, React (Redux), Vue (Vuex, Pinia), Tailwind CSS,
                    Bootstrap;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Testing:</span> PHPUnit, Jest,
                    Pest, Cypress E2E, Storybook workshop;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Commercial platform:</span> WP
                    WooCommerce, Shopify;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Version control:</span> Git,
                    Github, Gitlab, Bitbucket;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold"
                        >Database Management System:</span
                    >
                    MySQL, PostgreSQL, SQLite, Redis, MongoDB;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Deployment CI/CD:</span> Docker,
                    Kubernetes, Terraform (little), Ansible (little), Github
                    Actions, DigitalOcean, Railway, Render, Vercel, Fly.io;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Tools & Platforms:</span>
                    Postman, Figma, Worksection, Asana, Trello.
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Soft skills:</span> team
                    collaboration, communication, problem-solving;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Methodologies:</span> Scrum,
                    Kanban, Agile, SOLID principles, Feature-Sliced Design;
                </li>
                <li class="mb-4 text-justify">
                    <span class="font-semibold">Languages (spoken):</span>
                    Ukrainian (native), English (B1+), French (B1).
                </li>
            </ul>
        </div>
    </section>
</template>

<style scoped>
.main-about {
    font-family: 'Onest', sans-serif;
}
</style>
