<script setup lang="ts">
import ProjectCard from '@/components/ProjectCard.vue';
import { Project } from '@/types';

defineProps<{
    projects: Project[];
}>();
</script>

<template>
    <section
        id="main-projects"
        class="main-projects bg-gray-50 py-20 dark:bg-gray-900"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h2
                    class="text-4xl font-extrabold text-gray-900 sm:text-4xl dark:text-white"
                >
                    My projects
                </h2>
                <div class="mt-2 h-1.5 w-20 rounded-full bg-indigo-600"></div>
            </div>

            <div
                v-if="projects.length > 0"
                class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <ProjectCard
                    v-for="project in projects"
                    :key="project.id"
                    :project="project"
                />
            </div>

            <div
                v-else
                class="rounded-xl border-2 border-dashed border-gray-300 py-20 text-center"
            >
                <p class="text-3xl text-gray-500 dark:text-gray-400">
                    No projects have been added yet.
                </p>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* .main-projects {
    font-family: 'Onest', sans-serif;
} */
</style>
