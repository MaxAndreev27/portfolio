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
    background-image: url("https://media.istockphoto.com/id/958836498/tr/foto%C4%9Fraf/soyut-tasar%C4%B1m-arka-plan.jpg?s=612x612&w=0&k=20&c=qyrPkJ0I820YC8iWwg0vWwl1IZzADW4y9GY9XwBHTOc=");
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
