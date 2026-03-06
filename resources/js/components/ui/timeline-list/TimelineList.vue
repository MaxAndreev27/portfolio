<script setup lang="ts">
import { cn } from '@/lib/utils';
import { TimelineEvent } from '@/types';
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';

interface Props extends PrimitiveProps {
    class?: HTMLAttributes['class']
    aboutTimeline: TimelineEvent[];
}

const props = withDefaults(defineProps<Props>(), {
    as: 'ul',
})

</script>

<template>
    <component v-if="aboutTimeline" :is="as" :class="cn('timeline', props.class)">
        <li
            v-for="(event, index) in aboutTimeline"
            :key="index"
            class="event"
        >
            <h3>{{ event.period }}</h3>
            <div class="timeline-content" v-html="event.description"></div>
        </li>
    </component>
</template>

<style scoped>
.timeline {
    border-left: 4px solid #38bdf8;
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(255, 255, 255, 0.03);
    position: relative;
    line-height: 1.4em;
    padding: 50px;
    list-style: none;
    text-align: left;
    font-weight: 100;
}

.timeline h3 {
    font-weight: bold;
}

.timeline-content :deep(a) {
    font-weight: 700;
    color: var(--color-pink-700);
}

.dark .timeline-content :deep(a) {
    color: var(--color-pink-400);
}

.timeline .event {
    border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
    padding-bottom: 20px;
    margin-bottom: 20px;
    position: relative;
}
.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}
.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}
.timeline .event:before {
    left: -217.5px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 1em;
    min-width: 120px;
}
.timeline .event:after {
    box-shadow: 0 0 0 4px #38bdf8;
    left: -57.85px;
    background: #313533; /* lighten(#252827, 5%) */
    border-radius: 50%;
    height: 11px;
    width: 11px;
    content: "";
    top: 10px;
}

@media (max-width: 767px) {
    .timeline {
        padding-right: 25px;
    }
}
</style>
