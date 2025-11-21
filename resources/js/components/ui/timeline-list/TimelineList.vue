<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

interface Props extends PrimitiveProps {
    class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
    as: 'ul',
})
</script>

<template>
    <component :is="as" :class="cn('timeline', props.class)">
        <li class="event">
            <h2>2005-2010</h2>
            <h3>Graduated from University of Informatics and Artificial Intelligence, and obtained the qualification of a software engineer with a specialty in "Software for Automated Systems"</h3>
        </li>
        <li class="event">
            <h2>2010-2011</h2>
            <h3>Programmer at UniCredit bank</h3>
        </li>
        <li class="event">
            <h2>2011-2014</h2>
            <h3>Programmer at the Regional Directorate of the Bank "Kyivska Rus"</h3>
        </li>
        <li class="event">
            <h2>2015-2023</h2>
            <h3>Worked in the
                <a
                    class="font-bold text-pink-600"
                    target="_blank"
                    rel="noopener noreferrer"
                    href="https://specialcase.net"
                >SpecialCase</a>
                team</h3>
        </li>
        <li class="event">
            <h2>2024-present</h2>
            <h3>Freelance</h3>
        </li>
    </component>
</template>

<style scoped>
.timeline {
    border-left: 4px solid #38bdf8;
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(255, 255, 255, 0.03);
    margin: 50px auto;
    position: relative;
    line-height: 1.4em;
    padding: 50px;
    list-style: none;
    text-align: left;
    font-weight: 100;
}

.timeline h2 {
    font-weight: bold;
}

.timeline h3 {

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
</style>
