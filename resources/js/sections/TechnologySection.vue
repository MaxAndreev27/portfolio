<script setup lang="ts">
import gsap from 'gsap';
import { MotionPathPlugin } from 'gsap/MotionPathPlugin';
import { onMounted, onUnmounted } from 'vue';

gsap.registerPlugin(MotionPathPlugin);

let mainElement: Element | null = null;
let handleMouseMove: ((e: MouseEvent) => void) | null = null;
let handleClick: ((e: Event) => void) | null = null;
let baseStageX = 0;
let baseStageY = 0;

onMounted(() => {
    const centerStage = () => {
        const stage = document.querySelector<SVGGElement>('.m1_stage');
        if (!stage) return;
        const bbox = stage.getBBox();
        const targetX = -(bbox.x + bbox.width / 2);
        const targetY = 50 - (bbox.y + bbox.height / 2);
        baseStageX = targetX;
        baseStageY = targetY;
        gsap.set(stage, { x: baseStageX, y: baseStageY, opacity: 1 });
    };

    const fitStageToContainer = () => {
        const stage = document.querySelector<SVGGElement>('.m1_stage');
        const container = document.querySelector<HTMLElement>(
            '#main-technology .container',
        );
        if (!stage || !container) return;
        const bbox = stage.getBBox();
        const containerW = container.clientWidth;
        if (!containerW || !bbox.width) return;
        const target = Math.max(containerW, 0.0001);
        let scale = Math.min(1, target / bbox.width);

        const isMobile =
            typeof window !== 'undefined' && window.matchMedia
                ? window.matchMedia('(max-width: 640px)').matches
                : false;

        if (isMobile) {
            scale = 1;
        } else {
            scale = Math.max(scale, 0.6);
        }

        gsap.set(stage, { scale: scale, transformOrigin: '50% 50%' });
    };

    window.onresize = window.onload = function () {
        centerStage();
        fitStageToContainer();
    };
    centerStage();
    fitStageToContainer();

    gsap.timeline({ defaults: { duration: 45 } })
        .from('.main1', { duration: 1, autoAlpha: 0, ease: 'power1.inOut' }, 0)
        .fromTo(
            '.m1_cGroup',
            { opacity: 0 },
            { duration: 0.3, opacity: 1, stagger: -0.1 },
            0,
        )
        .from(
            '.m1_cGroup',
            {
                duration: 2.5,
                scale: -0.3,
                transformOrigin: '50% 50%',
                stagger: -0.05,
                ease: 'elastic',
            },
            0,
        )
        .add('orbs', 1.2)
        .call(
            () => {
                mainElement = document.querySelector('.main1');
                if (!mainElement) return;

                handleMouseMove = (e: MouseEvent) => {
                    const rect = (
                        mainElement as Element
                    ).getBoundingClientRect();
                    const dxNorm =
                        (e.clientX - (rect.left + rect.width / 2)) / rect.width;
                    const dyNorm =
                        (e.clientY - (rect.top + rect.height / 2)) /
                        rect.height;
                    gsap.to('.m1_cGroup', {
                        duration: 1,
                        x: function (i: number) {
                            return dxNorm * (150 / (i + 1));
                        },
                        y: function (i: number) {
                            return i * -20 * dyNorm;
                        },
                        rotation: Math.random() * 0.1,
                        overwrite: 'auto',
                    });

                    const n =
                        document.querySelectorAll('.m1_cGroup').length || 1;
                    let sumFactors = 0;
                    for (let i = 0; i < n; i++) sumFactors += 150 / (i + 1);
                    const xMean = dxNorm * (sumFactors / n);
                    const yMean = dyNorm * (-20 * ((n - 1) / 2));

                    gsap.to('.m1_stage', {
                        duration: 1,
                        x: baseStageX - xMean,
                        y: baseStageY - yMean,
                        overwrite: 'auto',
                    });

                    gsap.to('.c1_solid, .c1_line', {
                        duration: 1,
                        attr: {
                            opacity:
                                1.1 - 0.75 * (e.clientY / window.innerHeight),
                        },
                    });
                };

                handleClick = (e: Event) => {
                    e.preventDefault();
                    if (gsap.getProperty('.m1_cGroup', 'scale') != 1) return;

                    const m1cGroups = document.querySelectorAll('.m1_cGroup');
                    m1cGroups.forEach((group, i) => {
                        gsap.fromTo(
                            group,
                            { transformOrigin: '50% 50%', scale: 1 },
                            {
                                duration: 0.7 - i / 25,
                                scale: 0.9,
                                ease: 'back.in(10)',
                                yoyo: true,
                                repeat: 1,
                            },
                        );
                    });
                };

                mainElement.addEventListener(
                    'mousemove',
                    handleMouseMove as EventListener,
                );
                mainElement.addEventListener(
                    'click',
                    handleClick as EventListener,
                );
            },
            [],
            'orbs+=0.5',
        )

        .fromTo(
            '.orb1',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line1',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.0,
                    end: 0.2,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb1b',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line1',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.3,
                    end: 0.5,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb1c',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line1',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.6,
                    end: 0.8,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb1d',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line1',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.9,
                    end: 1.05,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb2',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line2',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.1,
                    end: 0.25,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb2b',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line2',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.45,
                    end: 0.6,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb2c',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line2',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.8,
                    end: 0.95,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb3',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line3',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.05,
                    end: 0.2,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb3b',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line3',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.25,
                    end: 0.4,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb3c',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line3',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.5,
                    end: 0.65,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb3d',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line3',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.7,
                    end: 0.85,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb3e',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line3',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.9,
                    end: 1.05,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb4b',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.25,
                    end: 0.4,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb4c',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.5,
                    end: 0.65,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb4d',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.75,
                    end: 0.9,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )
        .fromTo(
            '.orb4e',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 1.0,
                    end: 1.15,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb4d',
            { xPercent: -40, yPercent: -20 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.9,
                    end: 1.0,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb4e',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.3,
                    end: 0.45,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        )

        .fromTo(
            '.orb4f',
            { xPercent: -40, yPercent: -15 },
            {
                xPercent: 0,
                yPercent: 0,
                motionPath: {
                    path: MotionPathPlugin.convertToPath(
                        '.c1_line4',
                        false,
                    )[0] as SVGPathElement,
                    start: 0.0,
                    end: 0.15,
                },
                ease: 'none',
                yoyo: true,
                repeat: -1,
            },
            'orbs',
        );
});

onUnmounted(() => {
    if (mainElement && handleMouseMove) {
        mainElement.removeEventListener(
            'mousemove',
            handleMouseMove as EventListener,
        );
    }
    if (mainElement && handleClick) {
        mainElement.removeEventListener('click', handleClick as EventListener);
    }
});
</script>

<template>
    <section
        id="main-technology"
        class="main-technology relative w-full overflow-hidden bg-[#000]"
        style="
            background-image: linear-gradient(
                180deg,
                rgba(0, 255, 200, 0.1) 25%,
                rgba(200, 255, 0, 0.2) 99%
            );
        "
    >
        <div class="container mx-auto max-w-4xl overflow-visible px-0">
            <svg
                class="main1 block h-auto w-[100%] overflow-visible sm:w-full"
                viewBox="-700 -600 1400 1400"
                preserveAspectRatio="xMidYMid meet"
            >
                <defs>
                    <linearGradient
                        id="grad1"
                        x1="50%"
                        y1="0%"
                        x2="50%"
                        y2="100%"
                    >
                        <stop
                            offset="10%"
                            style="
                                stop-color: rgb(255, 255, 0);
                                stop-opacity: 0.9;
                            "
                        />
                        <stop
                            offset="99%"
                            style="
                                stop-color: rgb(0, 255, 0);
                                stop-opacity: 0.1;
                            "
                        />
                    </linearGradient>
                    <linearGradient
                        id="grad2"
                        x1="50%"
                        y1="0%"
                        x2="50%"
                        y2="100%"
                    >
                        <stop
                            offset="25%"
                            style="
                                stop-color: rgb(0, 255, 200);
                                stop-opacity: 0.1;
                            "
                        />
                        <stop
                            offset="99%"
                            style="
                                stop-color: rgb(200, 255, 0);
                                stop-opacity: 0.2;
                            "
                        />
                    </linearGradient>
                </defs>

                <g
                    class="m1_stage cursor-pointer"
                    opacity="1"
                    transform="translate(0, 0)"
                    width="1400"
                    height="1400"
                    viewBox="-700 -600 1400 1400"
                >
                    <g class="m1_cGroup">
                        <!-- Background elements first -->
                        <circle
                            class="c1_line c1_line4"
                            cx="0"
                            cy="50"
                            r="550"
                            fill="none"
                            stroke-width="2"
                            stroke="url(#grad1)"
                            opacity="0.4"
                        />
                        <g class="m1Orb orb4b">
                            <image
                                href="@/assets/images/logo/logoVue.webp"
                                width="80"
                                height="80"
                            />
                        </g>

                        <g class="m1Orb orb4c">
                            <image
                                href="@/assets/images/logo/logoRedis.svg"
                                width="80"
                                height="80"
                            />
                        </g>

                        <g class="m1Orb orb4d">
                            <image
                                href="@/assets/images/logo/logoMongo.webp"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb4e">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoTailwind.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb4f">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoNestjs.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                    </g>
                    <g class="m1_cGroup">
                        <!-- Background elements first -->
                        <circle
                            class="c1_line c1_line3"
                            cx="0"
                            cy="50"
                            r="450"
                            fill="none"
                            stroke-width="2"
                            stroke="url(#grad1)"
                            opacity="0.4"
                        />

                        <g class="m1Orb orb3c">
                            <image
                                href="@/assets/images/logo/logoPostgre.svg"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>

                        <g class="m1Orb orb3b">
                            <image
                                href="@/assets/images/logo/logoReact.webp"
                                width="80"
                                height="80"
                            />
                        </g>
                        <g class="m1Orb orb3d">
                            <image
                                href="@/assets/images/logo/logoK8s.webp"
                                width="88"
                                height="88"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb3">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoGithub.svg"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb3e">
                            <circle
                                cx="40"
                                cy="40"
                                r="39"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoNextjs.svg"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                    </g>
                    <g class="m1_cGroup">
                        <!-- Background elements first -->
                        <circle
                            class="c1_line c1_line2"
                            cx="0"
                            cy="50"
                            r="360"
                            fill="none"
                            stroke-width="2"
                            stroke="url(#grad1)"
                            opacity="0.5"
                        />

                        <g class="m1Orb orb2">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoDocker.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>

                        <g class="m1Orb orb2b">
                            <image
                                href="@/assets/images/logo/logoTypescript.svg"
                                x="0"
                                y="0"
                                width="88"
                                height="88"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>

                        <g class="m1Orb orb2c">
                            <circle
                                cx="40"
                                cy="40"
                                r="50"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoLaravel.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                    </g>
                    <g class="m1_cGroup">
                        <!-- Background elements first -->
                        <circle
                            class="c1_solid"
                            cx="0"
                            cy="50"
                            r="280"
                            fill="url(#grad1)"
                            opacity="0.2"
                        />
                        <circle
                            class="c1_line c1_line1"
                            cx="0"
                            cy="50"
                            r="279"
                            fill="none"
                            stroke-width="3"
                            stroke="url(#grad1)"
                            opacity="0.5"
                        />
                        <!-- Logos after background elements -->
                        <g class="m1Orb orb1">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <g class="m1Icon m1IconPHP">
                                <image
                                    href="@/assets/images/logo/logoPhp.svg"
                                    x="0"
                                    y="0"
                                    width="80"
                                    height="80"
                                    preserveAspectRatio="xMidYMid meet"
                                />
                            </g>
                        </g>
                        <g class="m1Orb orb1b">
                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoMysql.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb1c">
                            <circle
                                cx="40"
                                cy="40"
                                r="39"
                                stroke="#f0f6fc"
                                fill="#f0f6fc"
                            />
                            <image
                                href="@/assets/images/logo/logoWordpress.svg"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                        <g class="m1Orb orb1d">
                            <image
                                href="@/assets/images/logo/logoNodejs.webp"
                                x="0"
                                y="0"
                                width="80"
                                height="80"
                                preserveAspectRatio="xMidYMid meet"
                            />
                        </g>
                    </g>

                    <g class="m1_cGroup">
                        <circle
                            class="c1_solid"
                            cx="0"
                            cy="50"
                            r="220"
                            fill="url(#grad1)"
                            opacity="0.4"
                        />
                    </g>
                    <g class="m1_cGroup">
                        <circle
                            class="c1_solid"
                            cx="0"
                            cy="50"
                            r="150"
                            fill="url(#grad1)"
                            opacity="0.5"
                        />
                    </g>
                    <g class="m1_cGroup">
                        <circle
                            class="c1_solid"
                            cx="0"
                            cy="50"
                            r="80"
                            fill="#9e0"
                            opacity="0.6"
                        />
                        <g class="m1Icon m1IconWeb" transform="translate(0,50)">
                            <circle
                                cx="0"
                                cy="0"
                                r="58"
                                fill="rgba(10, 25, 20, 0.6)"
                                stroke="rgba(255,255,255,0.25)"
                                stroke-width="1"
                            />
                            <text
                                x="0"
                                y="0"
                                text-anchor="middle"
                                dominant-baseline="middle"
                                fill="rgba(255,255,255,0.9)"
                                font-size="38"
                                font-weight="700"
                                letter-spacing="0.5"
                                opacity="0.7"
                            >
                                Click
                            </text>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
    </section>
</template>
