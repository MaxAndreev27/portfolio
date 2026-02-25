<script setup lang="ts">
import { Project } from '@/types';

const props = defineProps<{
    project: Project;
}>();

const year = props.project.completed_at
    ? new Date(props.project.completed_at).getFullYear()
    : 'In Progress';
</script>

<template>
    <div
        class="project-card group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-background shadow-sm dark:border-gray-700 dark:bg-gray-800"
    >
        <div class="relative aspect-video overflow-hidden">
            <img
                v-if="project.image"
                :src="project.image"
                :alt="project.title"
                class="h-full w-full transform object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center bg-gray-200 dark:bg-gray-700"
            >
                <span class="text-md text-gray-400">No Image</span>
            </div>

            <div
                class="absolute top-3 right-3 rounded-lg bg-white/90 px-2 py-1 text-sm font-bold text-gray-700 shadow-sm backdrop-blur dark:bg-gray-900/90 dark:text-gray-300"
            >
                {{ year }}
            </div>
        </div>

        <div class="flex grow flex-col p-4">
            <h3
                class="mb-2 line-clamp-2 text-xl font-bold text-gray-900 dark:text-white"
            >
                {{ project.title }}
            </h3>

            <p
                class="text-md mb-3 line-clamp-3 text-gray-600 dark:text-gray-400"
            >
                {{ project.excerpt }}
            </p>

            <div class="mt-auto mb-6 flex flex-wrap gap-2">
                <span
                    v-for="tag in project.tags"
                    :key="tag"
                    class="text-sm inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 font-medium text-indigo-700 ring-1 ring-indigo-700/10 ring-inset dark:bg-indigo-900/30 dark:text-indigo-300 dark:ring-indigo-500/20"
                >
                    {{ tag }}
                </span>
            </div>

            <div
                class="mt-auto flex items-center justify-between border-t border-gray-300 pt-4 dark:border-gray-700"
            >
                <a
                    v-if="project.github_url"
                    :href="project.github_url"
                    target="_blank"
                    class="text-md flex items-center font-medium text-gray-500 transition-colors hover:text-gray-900 dark:hover:text-white"
                    title="GitHub"
                >
                    <svg
                        class="mr-1 h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.627-5.373-12-12-12z"
                        />
                    </svg>
                    GitHub
                </a>

                <a
                    v-if="project.url"
                    :href="project.url"
                    target="_blank"
                    class="text-md flex items-center font-medium text-indigo-600 transition-colors hover:text-indigo-500"
                    title="Перейти на сайт"
                >
                    <svg
                        class="mr-1 h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                        />
                    </svg>
                    To site
                </a>

                <a
                    :href="`/projects/${project.slug}`"
                    class="text-md inline-flex items-center font-semibold text-indigo-600 hover:text-indigo-500"
                >
                    Details
                    <svg
                        class="ml-1 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* .project-card {
    font-family: 'Onest', sans-serif;
} */
</style>
