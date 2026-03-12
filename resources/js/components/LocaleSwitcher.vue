<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const currentLocale = computed(() => page.props.locale);
const locales = computed(() => page.props.locales);

const switchLanguage = (code: string) => {
    router.get(
        `/language/${code}`,
        {},
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div
        class="mx-2 inline-flex w-fit shrink-0 items-center gap-0.5 rounded-full border border-white/5 bg-white/5 p-0.5"
    >
        <button
            v-for="(name, code) in locales"
            :key="code"
            @click="switchLanguage(code as string)"
            :class="[
                'flex h-6 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold uppercase transition-all',
                currentLocale === code
                    ? 'bg-white/10 text-primary shadow-sm'
                    : 'text-muted-foreground hover:text-pink-600',
            ]"
        >
            {{ code }}
        </button>
    </div>
</template>
