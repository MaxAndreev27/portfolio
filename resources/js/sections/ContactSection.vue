<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import type { Pane as PaneType, InputBindingApi } from 'tweakpane';
import { Pane } from 'tweakpane';

// Extend the Pane type to include addBinding
declare module 'tweakpane' {
  interface Pane {
    addBinding: <T, K extends keyof T>(
      target: T,
      key: K,
      options?: any
    ) => InputBindingApi<T[K]>;
  }
}

interface KeyConfig {
    travel: number;
    text: string;
    key: string;
    hue: number;
    saturation: number;
    brightness: number;
    pressed: boolean;
}

interface AppConfig {
    theme: string;
    muted: boolean;
    exploded: boolean;
    one: KeyConfig;
    two: KeyConfig;
    three: KeyConfig;
    [key: string]: string | boolean | KeyConfig;
}

// Configuration state
const config = ref<AppConfig>({
    theme: 'system',
    muted: false,
    exploded: false,
    one: {
        travel: 26,
        text: 'ok',
        key: 'o',
        hue: 114,
        saturation: 1.4,
        brightness: 1.2,
        pressed: false,
    },
    two: {
        travel: 26,
        text: 'go',
        key: 'g',
        hue: 0,
        saturation: 0,
        brightness: 1.4,
        pressed: false,
    },
    three: {
        travel: 18,
        text: 'Enter',
        key: 'Enter',
        hue: 0,
        saturation: 0,
        brightness: 0.4,
        pressed: false,
    },
});

const clickAudio = ref<HTMLAudioElement | null>(null);
// let recorder: ((event: KeyboardEvent) => void) | null = null;
const ids = ['one', 'two', 'three'] as const;

// Initialize audio
onMounted(() => {
    clickAudio.value = new Audio(
        'https://cdn.freesound.org/previews/378/378085_6260145-lq.mp3',
    );
    clickAudio.value.muted = config.value.muted;

    // Initialize key styles
    updateKeyStyles();

    // Initialize Tweakpane
    initTweakpane();

    // Show keypad
    const keypad = document.querySelector('.keypad') as HTMLElement;
    if (keypad) {
        keypad.style.opacity = '1';
    }

    // Prevent form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (e) => e.preventDefault());
    }
});

// Cleanup
onUnmounted(() => {
    if (pane) {
        pane.dispose();
    }
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('keyup', handleKeyUp);
});

// Update key styles based on config
const updateKeyStyles = () => {
    ids.forEach((id) => {
        const key = document.getElementById(id);
        if (key) {
            const cfg = config.value[id];
            key.style.setProperty('--travel', cfg.travel.toString());
            key.style.setProperty('--saturate', cfg.saturation.toString());
            key.style.setProperty('--hue', cfg.hue.toString());
            key.style.setProperty('--brightness', cfg.brightness.toString());

            // Update key text
            const textElement = key.querySelector('.key__text');
            if (textElement) {
                textElement.textContent = cfg.text;
            }
        }
    });
};

// Handle key down
const handleKeyDown = (event: KeyboardEvent) => {
    ids.forEach((id) => {
        if (event.key === config.value[id].key) {
            config.value[id].pressed = true;
            playClickSound();
        }
    });
};

// Handle key up
const handleKeyUp = (event: KeyboardEvent) => {
    ids.forEach((id) => {
        if (event.key === config.value[id].key) {
            config.value[id].pressed = false;
        }
    });
};

// Play click sound
const playClickSound = () => {
    if (clickAudio.value && !config.value.muted) {
        clickAudio.value.currentTime = 0;
        clickAudio.value
            .play()
            .catch((e) => console.error('Audio play failed:', e));
    }
};

// Update theme
const updateTheme = () => {
    document.documentElement.dataset.theme = config.value.theme;
};

// Toggle exploded state
const toggleExploded = () => {
    document.documentElement.dataset.exploded =
        config.value.exploded.toString();
};

// Initialize Tweakpane
let pane: PaneType;
const initTweakpane = () => {
    pane = new Pane({
        title: 'Keypad Config',
        expanded: false,
    });

    // Theme control
    (pane as any)
        .addBinding(config.value, 'theme', {
            label: 'theme',
            options: {
                system: 'system',
                light: 'light',
                dark: 'dark',
            },
        })
        .on('change', updateTheme);

    // Mute control
    pane.addBinding(config.value, 'muted', {
        label: 'mute',
    }).on('change', () => {
        if (clickAudio.value) {
            clickAudio.value.muted = config.value.muted;
        }
    });

    // Exploded view
    pane.addBinding(config.value, 'exploded', {
        label: 'explode',
    }).on('change', toggleExploded);

    // Key-specific controls
    // ids.forEach((id) => {
    //     const keyFolder = pane!.addFolder({
    //         title: `Key ${id}`,
    //         expanded: false,
    //     });
    //
    //     // Text
    //     keyFolder
    //         .addBinding(config.value[id], 'text', {
    //             label: 'text',
    //         })
    //         .on('change', updateKeyStyles);
    //
    //     // Travel
    //     keyFolder
    //         .addBinding(config.value[id], 'travel', {
    //             min: 1,
    //             max: 50,
    //             step: 1,
    //         })
    //         .on('change', updateKeyStyles);
    //
    //     // Hue
    //     keyFolder
    //         .addBinding(config.value[id], 'hue', {
    //             min: 0,
    //             max: 360,
    //             step: 1,
    //         })
    //         .on('change', updateKeyStyles);
    //
    //     // Saturation
    //     keyFolder
    //         .addBinding(config.value[id], 'saturation', {
    //             min: 0,
    //             max: 2,
    //             step: 0.1,
    //         })
    //         .on('change', updateKeyStyles);
    //
    //     // Brightness
    //     keyFolder
    //         .addBinding(config.value[id], 'brightness', {
    //             min: 0,
    //             max: 2,
    //             step: 0.1,
    //         })
    //         .on('change', updateKeyStyles);
    //
    //     // Key binding
    //     keyFolder.addBinding(config.value[id], 'key', {
    //         disabled: true,
    //     });
    //
    //     // Record key
    //     keyFolder
    //         .addButton({
    //             title: 'Record Key',
    //         })
    //         .on('click', () => {
    //             if (recorder) {
    //                 window.removeEventListener('keypress', recorder);
    //             }
    //
    //             const recordKey = (event: KeyboardEvent) => {
    //                 config.value[id].key = event.key;
    //                 (pane as unknown as { refresh: () => void }).refresh();
    //                 window.removeEventListener('keypress', recordKey);
    //                 recorder = null;
    //             };
    //
    //             recorder = recordKey;
    //             window.addEventListener('keypress', recordKey, { once: true });
    //         });
    // });

    // Initial theme setup
    updateTheme();

    // Add event listeners
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('keyup', handleKeyUp);
};

// Handle key press for visual feedback
const handleKeyPress = (event: PointerEvent, keyId: string) => {
    const keyConfig = config.value[keyId as keyof typeof config.value];
    if (keyConfig && typeof keyConfig === 'object' && 'pressed' in keyConfig) {
        (keyConfig as KeyConfig).pressed = event.type === 'pointerdown';
        if (event.type === 'pointerdown') {
            playClickSound();
        }
    }
};
</script>

<template>
    <section id="main-contact" class="main-contact w-full">
        <div class="container mx-auto mt-20 mb-20 grid max-w-4xl grid-cols-2">
            <div>
                <div>
                    <h1>Jus' make things.<br />Put them on the internet.</h1>
                    <p>
                        Sign up for a newsletter all about building in public,
                        sharing your work, and ignoring the haters.
                    </p>
                    <form @submit.prevent>
                        <input
                            type="email"
                            required
                            placeholder="creator@hotmail.com"
                        />
                        <button type="submit">sign up</button>
                    </form>
                </div>
            </div>
            <div>
                <div class="keypad" :style="{ opacity: 1 }">
                    <div class="keypad__base">
                        <img
                            src="https://assets.codepen.io/605876/keypad-base.png?format=auto&quality=86"
                            alt=""
                        />
                    </div>

                    <!-- Key 1 -->
                    <button
                        id="one"
                        class="key keypad__single keypad__single--left"
                        :class="{ 'key--pressed': config.one.pressed }"
                        @pointerdown="(e) => handleKeyPress(e, 'one')"
                        @pointerup="(e) => handleKeyPress(e, 'one')"
                        @pointerleave="(e) => handleKeyPress(e, 'one')"
                    >
                        <span class="key__mask">
                            <span class="key__content">
                                <span class="key__text">{{
                                    config.one.text
                                }}</span>
                                <img
                                    src="https://assets.codepen.io/605876/keypad-single.png?format=auto&quality=86"
                                    alt=""
                                />
                            </span>
                        </span>
                    </button>

                    <!-- Key 2 -->
                    <button
                        id="two"
                        class="key keypad__single"
                        :class="{ 'key--pressed': config.two.pressed }"
                        @pointerdown="(e) => handleKeyPress(e, 'two')"
                        @pointerup="(e) => handleKeyPress(e, 'two')"
                        @pointerleave="(e) => handleKeyPress(e, 'two')"
                    >
                        <span class="key__mask">
                            <span class="key__content">
                                <span class="key__text">{{
                                    config.two.text
                                }}</span>
                                <img
                                    src="https://assets.codepen.io/605876/keypad-single.png?format=auto&quality=86"
                                    alt=""
                                />
                            </span>
                        </span>
                    </button>

                    <!-- Key 3 -->
                    <button
                        id="three"
                        class="key keypad__double"
                        :class="{ 'key--pressed': config.three.pressed }"
                        @pointerdown="(e) => handleKeyPress(e, 'three')"
                        @pointerup="(e) => handleKeyPress(e, 'three')"
                        @pointerleave="(e) => handleKeyPress(e, 'three')"
                    >
                        <span class="key__mask">
                            <span class="key__content">
                                <span class="key__text">{{
                                    config.three.text
                                }}</span>
                                <img
                                    src="https://assets.codepen.io/605876/keypad-double.png?format=auto&quality=86"
                                    alt=""
                                />
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@import url('https://unpkg.com/normalize.css') layer(normalize);

@layer normalize, base, demo, debug, exploding;

@layer exploding {
    [data-exploded='true'] {
        section {
            transition-delay: 0s;
        }
        .keypad {
            translate: calc(-50% - 1rem) 0;
            transition-delay: 0s, 0.26s;
            @media (max-width: 768px) {
                translate: 0 calc(50% + 1rem);
            }
        }
        .keypad__base {
            --depth: 2.5;
        }
        .keypad__single {
            --depth: -1;
        }
        .keypad__single--left {
            --depth: -2;
        }
        .keypad__double {
            --depth: 0;
        }

        .keypad__base,
        .key {
            translate: 0 calc(var(--depth) * 10vh);
            transition-delay: 0.52s;
        }
    }

    .keypad {
        transition-delay: 0.26s, 0.52s;
    }

    .key,
    .keypad__base {
        transition-property: translate;
        transition-duration: 0.26s;
        transition-timing-function: ease-out;
    }

    [data-exploded='true'] .key::after {
        opacity: 1;
        transition-delay: 0.52s;
    }

    .key::after {
        z-index: -1;
        content: '';
        position: absolute;
        opacity: 0;
        inset: 0;
        transition-property: opacity;
        transition-duration: 0.26s;
        transition-timing-function: ease-out;
        background: repeating-linear-gradient(
            -45deg,
            #0000 0 5px,
            hsl(220 100% 70%) 5px 6px
        );
    }
    /* timings */
}

@layer debug {
    main {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        align-items: center;
        justify-content: center;

        h1 {
            letter-spacing: -0.05rem;
            line-height: 1;
        }

        p {
            opacity: 0.7;
            font-weight: 300;
        }

        form {
            display: flex;
            gap: 0.5rem;

            input {
                flex: 1;
                padding: 0.5rem 0.75rem;
                background: canvas;
                border: 1px solid color-mix(in oklch, canvasText, #0000 75%);
                border-radius: 6px;
                outline-color: red;
            }

            button {
                padding-inline: 1.5rem;
                border-radius: 6px;
                background: canvas;
                border: 1px solid color-mix(in oklch, canvasText, #0000 75%);
                cursor: pointer;
                color: canvasText;
                font-size: 0.875rem;
            }
        }

        section {
            transition-property: opacity, filter;
            transition-duration: 0.26s;
            transition-delay: 0.26s;
            transition-timing-function: ease-out;
        }
    }

    [data-exploded='true'] section {
        opacity: 0;
        filter: blur(4px);
    }

    @media (max-width: 768px) {
        .keypad {
            order: 1;
        }
        section {
            order: 2;
        }
    }
}

@layer demo {
    :root {
        --travel: 20;
        --hue: 0; /* Default hue value */
        --saturate: 1; /* Default saturation value */
        --brightness: 1; /* For completeness, since it's also used */
    }
    .keypad {
        position: relative;
        /* outline: 4px dashed red;
        outline-offset: 2px; */
        aspect-ratio: 400 / 310;
        display: flex;
        place-items: center;
        width: clamp(280px, 35vw, 400px);
        -webkit-tap-highlight-color: #0000;
        transition-property: translate, transform;
        transition-duration: 0.26s;
        transition-timing-function: ease-out;
        transform-style: preserve-3d;

        .key {
            transform-style: preserve-3d;
            border: 0;
            background: #0000;
            padding: 0;
            cursor: pointer;
            outline: none;

            &[data-pressed='true'],
            &:active {
                .key__content {
                    translate: 0 calc(var(--travel) * 1%);
                }
            }
        }

        .key__content {
            width: 100%;
            display: inline-block;
            height: 100%;
            transition: translate 0.12s ease-out;
            container-type: inline-size;
        }

        .keypad__single .key__text {
            width: 52%;
            height: 62%;
            translate: 45% -16%;
        }

        .key__text {
            height: 46%;
            width: 86%;
            position: absolute;
            font-size: 12cqi;
            z-index: 21;
            top: 5%;
            left: 0;
            mix-blend-mode: normal;
            /* mix-blend-mode: overlay soft-light; */
            color: hsl(0 0% 94%);
            translate: 8% 10%;
            transform: rotateX(36deg) rotateY(45deg) rotateX(-90deg)
                rotate(0deg);
            text-align: left;
            padding: 1ch;
        }

        .keypad__single {
            position: absolute;
            width: 40.5%;
            left: 54%;
            bottom: 36%;
            height: 46%;
            clip-path: polygon(
                0 0,
                54% 0,
                89% 24%,
                100% 70%,
                54% 100%,
                46% 100%,
                0 69%,
                12% 23%,
                47% 0%
            );
            mask: url(https://assets.codepen.io/605876/keypad-single.png?format=auto&quality=86)
                50% 50% / 100% 100%;

            &.keypad__single--left {
                left: 29.3%;
                bottom: 54.2%;
            }

            .key__text {
                font-size: 18cqi;
            }

            img {
                top: 0;
                opacity: 1;
                width: 96%;
                position: absolute;
                left: 50%;
                translate: -50% 1%;
            }
        }

        .key__mask {
            width: 100%;
            height: 100%;
            display: inline-block;
        }

        .keypad__double {
            position: absolute;
            background: hsl(10 100% 50% / 0);
            width: 64%;
            height: 65%;
            left: 6%;
            bottom: 17.85%;
            clip-path: polygon(
                34% 0,
                93% 44%,
                101% 78%,
                71% 100%,
                66% 100%,
                0 52%,
                0 44%,
                7% 17%,
                30% 0
            );
            mask: url(https://assets.codepen.io/605876/keypad-double.png?format=auto&quality=86)
                50% 50% / 100% 100%;
            img {
                top: 0;
                opacity: 1;
                width: 99%;
                position: absolute;
                left: 50%;
                translate: -50% 1%;
            }
        }

        .key img {
            filter: hue-rotate(calc(var(--hue, 0) * 1deg))
                saturate(var(--saturate, 1)) brightness(var(--brightness, 1));
        }

        .keypad__base {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        img {
            transition: translate 0.12s ease-out;
            width: 100%;
        }
    }
    /*.reference {
        pointer-events: none;
        opacity: 0;
        z-index: 2;
    }*/
}

@layer base {
    :root {
        --font-level: 0;
        --font-size-min: 16;
        --font-size-max: 20;
        --font-ratio-min: 1.2;
        --font-ratio-max: 1.33;
        --font-width-min: 375;
        --font-width-max: 1500;
    }

    html {
        color-scheme: light dark;
    }

    [data-theme='light'] {
        color-scheme: light only;
    }

    [data-theme='dark'] {
        color-scheme: dark only;
    }
/*
    :where(.fluid) {
        --fluid-min: calc(
            var(--font-size-min) *
                pow(var(--font-ratio-min), var(--font-level, 0))
        );
        --fluid-max: calc(
            var(--font-size-max) *
                pow(var(--font-ratio-max), var(--font-level, 0))
        );
        --variable-unit: 100vi;
        --fluid-preferred: calc(
            (var(--fluid-max) - var(--fluid-min)) /
                (var(--font-width-max) - var(--font-width-min))
        );
        --fluid-type: clamp(
            (var(--fluid-min) / 16) * 1rem,
            ((var(--fluid-min) / 16) * 1rem) -
                (
                    ((var(--fluid-preferred) * var(--font-width-min)) / 16) *
                        1rem
                ) +
                (var(--fluid-preferred) * var(--variable-unit)),
            (var(--fluid-max) / 16) * 1rem
        );
        font-size: var(--fluid-type);
    }
*/
    *,
    *:after,
    *:before {
        box-sizing: border-box;
    }

    body {
        background: light-dark(#fff, #000);
        display: grid;
        place-items: center;
        min-height: 100vh;
        overflow: hidden;
        font-family:
            'SF Pro Text', 'SF Pro Icons', 'AOS Icons', 'Helvetica Neue',
            Helvetica, Arial, sans-serif, system-ui;
    }

    body::before {
        --size: 45px;
        --line: color-mix(in hsl, canvasText, transparent 80%);
        content: '';
        height: 100vh;
        width: 100vw;
        position: fixed;
        background:
            linear-gradient(90deg, var(--line) 1px, transparent 1px var(--size))
                calc(var(--size) * 0.36) 50% / var(--size) var(--size),
            linear-gradient(var(--line) 1px, transparent 1px var(--size)) 0%
                calc(var(--size) * 0.32) / var(--size) var(--size);
        mask: linear-gradient(-20deg, transparent 50%, white);
        top: 0;
        transform-style: flat;
        pointer-events: none;
        z-index: -1;
    }
/*
    .bear-link {
        color: canvasText;
        position: fixed;
        top: 1rem;
        left: 1rem;
        width: 48px;
        aspect-ratio: 1;
        display: grid;
        place-items: center;
        opacity: 0.8;
    }
*/
    /*
    :where(.x-link, .bear-link):is(:hover, :focus-visible) {
        opacity: 1;
    }
         */

    .bear-link svg {
        width: 75%;
    }

    /* Utilities */
    /*
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    */
}

/*div.tp-dfwv {
    width: 280px;
}
*/


.keypad {
    opacity: 0;
}
</style>
