<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

interface Props extends PrimitiveProps {
    class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
    as: 'h2',
})

</script>

<template>
    <component :is="as" :class="cn('bemine_text', props.class)">
        <slot />
    </component>
</template>

<style scoped>
.bemine_text {
    color: rgba(225,225,225, .01);
    background-image: url('@/assets/images/bemineTextBg.webp');
    background-repeat: repeat;
    -webkit-background-clip:text;
    animation: animate 20s ease-in-out infinite;
}

@keyframes animate {
    0%, 100% {
        background-position: left top;
    }
    25%{
        background-position: right bottom;
    }
    50% {
        background-position: left bottom;
    }
    75% {
        background-position: right top;
    }
}
</style>
