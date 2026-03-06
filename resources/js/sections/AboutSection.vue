<script setup lang="ts">
import { BemineText } from '@/components/ui/bemine-text';
import { TimelineList } from '@/components/ui/timeline-list';
import { AboutSettings } from '@/types';

defineProps<{
    aboutSettings: AboutSettings;
}>();
</script>

<template>
    <section id="main-about" class="main-about w-full px-5">
        <div
            class="container mx-auto my-15 flex max-w-5xl flex-col items-center justify-center md:my-20"
        >
            <BemineText
                v-if="aboutSettings.about_title"
                class="text-5xl font-bold md:text-6xl"
                >{{ aboutSettings.about_title }}</BemineText
            >

            <TimelineList
                v-if="
                    aboutSettings.about_timeline &&
                    aboutSettings.about_timeline.length > 0
                "
                :aboutTimeline="aboutSettings.about_timeline"
                class="my-10 text-lg sm:text-xl md:text-2xl"
            />

            <ul
                v-if="
                    aboutSettings.about_skills &&
                    aboutSettings.about_skills.length > 0
                "
                class="flex w-full list-inside list-disc list-image-[url(@/assets/images/checkmark.svg)] flex-col items-start text-left text-xl sm:text-2xl md:text-3xl"
            >
                <li
                    v-for="(skill, index) in aboutSettings.about_skills"
                    :key="index"
                    class="mb-4 text-justify"
                >
                    <span class="font-semibold">{{ skill.category }}:</span>
                    {{ skill.content }}
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
