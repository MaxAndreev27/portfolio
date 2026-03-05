<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { GradientText } from '@/components/ui/gradient-text';
import { HomeSettings } from '@/types';

defineProps<{
    homeSettings: HomeSettings;
}>();

const goToAbout = () => {
    const el = document.getElementById('main-about');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

const goToContact = () => {
    const el = document.getElementById('main-contact');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};
</script>

<template>
    <section id="main-hero" class="main-hero w-full px-5">
        <div
            class="container mx-auto flex min-h-screen max-w-4xl flex-col items-center justify-center"
        >
            <GradientText
                as="h1"
                class="mb-2 text-center text-5xl font-bold sm:text-6xl md:text-8xl"
                >{{ homeSettings.hero_title }}</GradientText
            >
            <h2
                class="mb-4 text-center text-2xl font-bold uppercase sm:text-3xl md:text-5xl"
            >
                {{ homeSettings.hero_description }}
            </h2>
            <Avatar
                class="mb-8 h-50 w-50 overflow-hidden rounded-full sm:h-75 sm:w-75"
            >
                <AvatarImage
                    v-if="homeSettings.hero_image"
                    :src="homeSettings.hero_image"
                    :alt="'avatar'"
                    loading="eager"
                    fetchpriority="high"
                />
                <AvatarFallback class="rounded-lg bg-[#0f3045] text-white">
                    avatar
                </AvatarFallback>
            </Avatar>
            <div class="grid grid-cols-2 gap-4 md:gap-6">
                <Button
                    class="cursor-pointer text-xl md:text-2xl"
                    variant="default"
                    size="xl2"
                    @click="goToAbout"
                    >{{ homeSettings.hero_button_about }}</Button
                >
                <Button
                    class="cursor-pointer text-xl md:text-2xl"
                    variant="secondary"
                    size="xl2"
                    @click="goToContact"
                    >{{ homeSettings.hero_button_contact }}</Button
                >
            </div>
        </div>
    </section>
</template>

<style scoped>
.main-hero {
    background-image: url('@/assets/images/heroBg768.webp');
    background-size: cover;
    animation: animate-bg 30s ease infinite;
    background-color: #e0edfe;
    font-family: 'Onest', sans-serif;
    position: relative;
}

@media (min-width: 769px) {
    .main-hero {
        background-image: url('@/assets/images/heroBg1920.webp');
    }
}

@media (min-width: 1921px) {
    .main-hero {
        background-image: url('@/assets/images/heroBg2560.webp');
    }
}

@keyframes animate-bg {
    0% {
        background-position: center 25%;
    }
    50% {
        background-position: center 50%;
    }
    100% {
        background-position: center 25%;
    }
}
</style>
