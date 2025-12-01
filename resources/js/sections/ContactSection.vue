<script setup lang="ts">
import { GlitchText } from '@/components/ui/glitch-text';
import { onMounted, onUnmounted, ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { store } from '@/routes/login';
import { Form } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';

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
        text: 'send',
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

// Audio context and initialization state
let audioContext: AudioContext | null = null;
let isAudioInitialized = false;

// Initialize audio context on first interaction
const initAudioOnInteraction = () => {
    if (isAudioInitialized || typeof window === 'undefined') return;

    try {
        audioContext = new (window.AudioContext ||
            (window as any).webkitAudioContext)();

        // Play a silent sound to unlock audio
        const buffer = audioContext.createBuffer(1, 1, 22050);
        const source = audioContext.createBufferSource();
        source.buffer = buffer;
        source.connect(audioContext.destination);
        source.start(0);

        isAudioInitialized = true;
        // console.log('Audio initialized');
    } catch (e) {
        console.warn('Audio initialization failed:', e);
    }
};

// Initialize audio
onMounted(() => {
    clickAudio.value = new Audio(
        'https://cdn.freesound.org/previews/378/378085_6260145-lq.mp3',
    );
    clickAudio.value.muted = config.value.muted;

    // Initialize audio on first interaction
    const initOnInteraction = () => {
        initAudioOnInteraction();
        document.removeEventListener('click', initOnInteraction);
        document.removeEventListener('keydown', initOnInteraction);
    };

    document.addEventListener('click', initOnInteraction, { once: true });
    document.addEventListener('keydown', initOnInteraction, { once: true });

    // Initialize key styles
    updateKeyStyles();

    // Initialize keypad
    initKeypad();

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
    if (!isAudioInitialized) {
        initAudioOnInteraction();
        return;
    }

    if (clickAudio.value && !config.value.muted) {
        clickAudio.value.currentTime = 0;
        clickAudio.value
            .play()
            .catch((e) => console.warn('Audio play failed:', e));
    }
};

// Update theme
const updateTheme = () => {
    // Use system preference if theme is set to 'system'
    if (config.value.theme === 'system') {
        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)',
        ).matches;
        document.documentElement.dataset.theme = prefersDark ? 'dark' : 'light';
    } else {
        document.documentElement.dataset.theme = config.value.theme;
    }
};

// Initialize keypad
const initKeypad = () => {
    // Set initial theme based on system preference
    const prefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)',
    ).matches;
    config.value.theme = prefersDark ? 'dark' : 'light';
    updateTheme();

    // Listen for system theme changes
    window
        .matchMedia('(prefers-color-scheme: dark)')
        .addEventListener('change', (e) => {
            if (config.value.theme === 'system') {
                config.value.theme = e.matches ? 'dark' : 'light';
                updateTheme();
            }
        });

    // Add mute toggle button handler if needed
    const muteButton = document.querySelector('.mute-button');
    if (muteButton) {
        muteButton.addEventListener('click', () => {
            config.value.muted = !config.value.muted;
            if (clickAudio.value) {
                clickAudio.value.muted = config.value.muted;
            }
        });
    }

    // Initial theme setup
    updateTheme();

    // Add event listeners
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('keyup', handleKeyUp);
};

// Handle key press for visual feedback
const handleKeyPress = (event: PointerEvent, keyId: string) => {
    if (!isAudioInitialized) {
        initAudioOnInteraction();
    }

    playClickSound();
    const key = config.value[keyId as keyof AppConfig] as KeyConfig;
    if (key) {
        key.pressed = true;
        setTimeout(() => {
            key.pressed = false;
        }, 200);
    }
};
</script>

<template>
    <section id="main-contact" class="main-contact w-full">
        <div class="container mx-auto mt-20 mb-20 max-w-4xl">
            <div class="grid grid-cols-2">
                <div>
                    <Card class="rounded-xl">
                        <CardHeader class="px-10 pt-8 pb-0 text-center">
                            <CardTitle class="text-xl">
                                <GlitchText
                                    class="content-center text-6xl font-bold"
                                    data-text="Contact me"
                                    >Contact me</GlitchText
                                >
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="px-10 py-8">
                            <Form
                                v-bind="store.form()"
                                :reset-on-success="['password']"
                                class="flex flex-col gap-6"
                            >
                                <div class="grid gap-6">
                                    <div class="grid gap-2">
                                        <Label for="email">Email address</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            name="email"
                                            required
                                            autofocus
                                            :tabindex="1"
                                            autocomplete="email"
                                            placeholder="email@example.com"
                                        />
                                        <InputError :message="'aaa'" />
                                    </div>
                                    <Button
                                        type="submit"
                                        class="mt-4 w-full"
                                        :tabindex="4"
                                        :disabled="'processing'"
                                        data-test="login-button"
                                    >
                                        <Spinner v-if="true" />
                                        Send
                                    </Button>
                                </div>
                            </Form>
                        </CardContent>
                    </Card>
                </div>
                <div>
                    <div class="keypad" :style="{ opacity: 1 }">
                        <div class="keypad__base">
                            <img
                                src="/images/keypad/keypad-base.webp?format=auto&quality=86"
                                alt="keypad-base"
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
                                        src="/images/keypad/keypad-single.webp?format=auto&quality=86"
                                        alt="keypad-single"
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
                                        src="/images/keypad/keypad-single.webp?format=auto&quality=86"
                                        alt="keypad-single"
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
                                        src="/images/keypad/keypad-double.png?format=auto&quality=86"
                                        alt="keypad-double"
                                    />
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
:root {
    --travel: 20;
    --hue: 0; /* Default hue value */
    --saturate: 1; /* Default saturation value */
    --brightness: 1; /* For completeness, since it's also used */
}
.main-contact {
    position: relative;
    display: block;
    overflow: hidden;
    z-index: 1;
}

.main-contact:before {
    --size: 45px;
    --line: color-mix(in hsl, canvasText, transparent 80%);
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        linear-gradient(90deg, var(--line) 1px, transparent 1px var(--size))
            calc(var(--size) * 0.36) 50% / var(--size) var(--size),
        linear-gradient(var(--line) 1px, transparent 1px var(--size)) 0%
            calc(var(--size) * 0.32) / var(--size) var(--size);
    mask: linear-gradient(-20deg, transparent 50%, white);
    opacity: 0.5;
    z-index: -1;
}

.keypad {
    position: relative;
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
        transform: rotateX(36deg) rotateY(45deg) rotateX(-90deg) rotate(0deg);
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
        mask: url(/images/keypad/keypad-single.webp?format=auto&quality=86) 50%
            50% / 100% 100%;

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
        mask: url(/images/keypad/keypad-double.png?format=auto&quality=86) 50%
            50% / 100% 100%;
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
</style>
