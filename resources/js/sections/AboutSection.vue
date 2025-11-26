<script setup lang="ts">
import { BemineText } from '@/components/ui/bemine-text';
import { TimelineList } from '@/components/ui/timeline-list';
</script>

<template>
    <section id="main-about" class="main-about w-full">
        <div
            class="container mx-auto mt-20 mb-20 flex flex-col items-center justify-center"
        >
            <BemineText class="text-6xl font-bold">About me</BemineText>

            <TimelineList class="max-w-4xl text-2xl" />

            <ul
                class="flex w-full max-w-4xl list-inside list-disc list-image-[url(/public/images/checkmark.svg)] flex-col items-start text-left text-3xl"
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
@import url('https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap');
.main-about {
    font-family: 'Onest', sans-serif;
}
</style>
