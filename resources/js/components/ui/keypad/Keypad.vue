<script setup lang="ts">
import { defineEmits, onMounted, ref } from 'vue';

const emit = defineEmits(['keyPress']);

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

    // Initialize key styles
    updateKeyStyles();

    // Initialize keypad
    initKeypad();

    // Show keypad
    const keypad = document.querySelector('.keypad') as HTMLElement;
    if (keypad) {
        keypad.style.opacity = '1';
    }
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

// Initialize keypad
const initKeypad = () => {
    // Set initial theme based on system preference
    const prefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)',
    ).matches;
    config.value.theme = prefersDark ? 'dark' : 'light';
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
        // Emit the key press event to parent with the key ID
        emit('keyPress', { key: key.key, keyId });
        setTimeout(() => {
            key.pressed = false;
        }, 200);
    }
};
</script>

<template>
    <div class="keypad" :style="{ opacity: 1 }">
        <div class="keypad__base">
            <img
                src="@/assets/images/keypad-base.webp?format=auto&quality=86"
                alt="keypad-base"
            />
        </div>

        <!-- Key 1 -->
        <button
            id="one"
            class="key keypad__single keypad__single--left"
            :class="{ 'key--pressed': config.one.pressed }"
            @click="(e) => handleKeyPress(e, 'one')"
        >
                            <span class="key__mask">
                                <span class="key__content">
                                    <span class="key__text">{{
                                            config.one.text
                                        }}</span>
                                    <img
                                        src="@/assets/images/keypad-single.webp?format=auto&quality=86"
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
            @click="(e) => handleKeyPress(e, 'two')"
        >
                            <span class="key__mask">
                                <span class="key__content">
                                    <span class="key__text">{{
                                            config.two.text
                                        }}</span>
                                    <img
                                        src="@/assets/images/keypad-single.webp?format=auto&quality=86"
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
            @click="(e) => handleKeyPress(e, 'three')"
        >
                            <span class="key__mask">
                                <span class="key__content">
                                    <span class="key__text">{{
                                            config.three.text
                                        }}</span>
                                    <img
                                        src="@/assets/images/keypad-double.webp?format=auto&quality=86"
                                        alt="keypad-double"
                                    />
                                </span>
                            </span>
        </button>
    </div>
</template>

<style scoped>
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
        mask: url(@/assets/images/keypad-single.webp?format=auto&quality=86) 50%
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
        mask: url(@/assets/images/keypad-double.webp?format=auto&quality=86) 50%
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
