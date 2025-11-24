<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { GradientText } from '@/components/ui/gradient-text';

const goToAbout = () => {
    const el = document.getElementById('main-about');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};
</script>

<template>
    <section id="main-hero" class="main-hero w-full">
        <div
            class="container mx-auto flex h-screen flex-col items-center justify-center"
        >
            <GradientText as="h1" class="mb-2 text-8xl font-bold"
                >Max Andreev</GradientText
            >
            <h2 class="mb-4 text-5xl font-bold uppercase">
                Full-Stack Developer
            </h2>
            <Avatar class="mb-8 h-50 w-50 overflow-hidden rounded-full">
                <AvatarImage :src="'/images/avatar200.jpg'" :alt="'avatar'" />
                <AvatarFallback class="rounded-lg bg-[#0f3045] text-white">
                    avatar
                </AvatarFallback>
            </Avatar>
            <div class="grid grid-cols-2 gap-6">
                <Button class="text-2xl cursor-pointer" variant="default" size="xl2" @click="goToAbout"
                    >About me</Button
                >
                <Button class="text-2xl cursor-pointer" variant="secondary" size="xl2"
                    >Contact me</Button
                >
            </div>
        </div>
    </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap');
.main-hero {
    background-image: url('https://images.unsplash.com/photo-1530569673472-307dc017a82d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2788&q=80');
    background-size: cover;
    animation: animate-bg 30s ease infinite;
    background-color: #e0edfe;
    font-family: 'Onest', sans-serif;
    position: relative;
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
