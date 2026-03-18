<script setup lang="ts">
import ChatWidget from '@/components/ChatWidget.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const page = usePage();
const isAuth = computed(() => !!page.props.auth.user);
console.log('isAuth: ' + isAuth.value);

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <ChatWidget v-if="isAuth" />
    </AppLayout>
</template>
