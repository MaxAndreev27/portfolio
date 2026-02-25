<script setup lang="ts">
import ProjectCard from '@/components/ProjectCard.vue';
import { Project } from '@/types';
import { computed, ref } from 'vue';

const props = defineProps<{
    projects: Project[];
}>();

const activeCategory = ref('All');
const sortBy = ref('newest'); // 'newest', 'oldest', 'alphabetical'

const categories = computed(() => {
    const tags = props.projects.flatMap((p) => p.tags || []);
    return ['All', ...new Set(tags)];
});

const filteredProjects = computed(() => {
    let result = [...props.projects];

    if (activeCategory.value !== 'All') {
        result = result.filter((p) => p.tags?.includes(activeCategory.value));
    }

    result.sort((a, b) => {
        if (sortBy.value === 'newest') {
            return (
                new Date(b.completed_at || 0).getTime() -
                new Date(a.completed_at || 0).getTime()
            );
        }
        if (sortBy.value === 'oldest') {
            return (
                new Date(a.completed_at || 0).getTime() -
                new Date(b.completed_at || 0).getTime()
            );
        }
        if (sortBy.value === 'alphabetical') {
            return a.title.localeCompare(b.title);
        }
        return 0;
    });

    return result;
});
</script>

<template>
    <section id="main-projects" class="main-projects py-20 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4">
            <div class="mb-12">
                <h2
                    class="text-4xl font-extrabold text-gray-900 sm:text-4xl dark:text-white"
                >
                    My projects
                </h2>
                <div class="mt-2 h-1.5 w-20 rounded-full bg-indigo-600"></div>
            </div>
            <div
                class="mb-10 flex flex-wrap items-center justify-between gap-6"
            >
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        @click="activeCategory = cat"
                        :class="[
                            'cursor-pointer rounded-full border-2 px-5 py-2.5 text-sm font-semibold transition-all duration-300',
                            activeCategory === cat
                                ? 'scale-105 transform border-indigo-600 bg-indigo-600 text-white shadow-md'
                                : 'border-transparent bg-white text-gray-600 hover:border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600',
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>

                <div class="relative min-w-45">
                    <select
                        v-model="sortBy"
                        class="w-full cursor-pointer appearance-none rounded-xl border-gray-200 bg-white px-4 py-2.5 pr-10 text-sm font-medium shadow-sm transition-all focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                    >
                        <option value="newest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="alphabetical">By Name (A-Z)</option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative">
                <TransitionGroup
                    name="project-list"
                    tag="div"
                    class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <ProjectCard
                        v-for="project in filteredProjects"
                        :key="project.id"
                        :project="project"
                    />
                </TransitionGroup>

                <div
                    v-if="filteredProjects.length === 0"
                    class="py-20 text-center text-gray-500"
                >
                    No projects have been added yet.
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.project-list-move,
.project-list-enter-active,
.project-list-leave-active {
    transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

.project-list-move {
    transition: transform 0.6s cubic-bezier(0.55, 0, 0.1, 1);
    backface-visibility: hidden;
}

.project-list-enter-from,
.project-list-leave-to {
    opacity: 0;
    transform: scale(0.9) translateY(30px);
}

.project-list-leave-active {
    position: absolute;
}
</style>
