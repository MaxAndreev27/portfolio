<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { onMounted, onUnmounted, ref } from 'vue';

const mobileOpen = ref(false);
const activeSection = ref('');

const toggleMobile = () => (mobileOpen.value = !mobileOpen.value);

const closeMobile = () => {
    mobileOpen.value = false;
};

// Функція для плавного скролу без # в URL
const scrollToSection = (e: Event, id: string) => {
    e.preventDefault(); // Запобігаємо переходу по посиланню та зміні URL

    const element = document.getElementById(id);
    if (element) {
        // Вираховуємо відступ (наприклад, висота навбару + запас)
        const offset = 80;
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth',
        });
    }

    closeMobile(); // Закриваємо меню після кліку
};

const updateActiveNav = () => {
    const sectionIds = [
        'main-hero',
        'main-about',
        'main-technology',
        'main-contact',
    ];
    let current = '';

    for (const id of sectionIds) {
        const section = document.getElementById(id);
        if (section) {
            const sectionTop = section.offsetTop;
            if (window.scrollY >= sectionTop - 150) {
                current = id;
            }
        }
    }
    activeSection.value = current;
};

const handleClickOutside = (event: MouseEvent) => {
    const nav = document.querySelector('nav');
    if (mobileOpen.value && nav && !nav.contains(event.target as Node)) {
        closeMobile();
    }
};

const handleResize = () => {
    if (window.innerWidth > 767 && mobileOpen.value) {
        closeMobile();
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    window.addEventListener('scroll', updateActiveNav);
    document.addEventListener('click', handleClickOutside);
    updateActiveNav();
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    window.removeEventListener('scroll', updateActiveNav);
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <header class="fixed top-5 z-50 container w-full px-5">
        <nav
            class="grid grid-cols-3 items-center gap-4 rounded-full bg-[var(--navbar)] px-5 shadow ring-1 ring-black/5 backdrop-blur-lg backdrop-contrast-105 backdrop-saturate-150 dark:ring-white/10"
        >
            <a href="/" class="logo flex justify-self-start">
                <img
                    src="@/assets/images/cicd.svg"
                    width="80"
                    height="40"
                    alt="logo"
                />
            </a>

            <button
                @click.stop="toggleMobile"
                class="flex h-[55px] items-center justify-self-center md:hidden"
                :aria-expanded="mobileOpen"
                aria-controls="main-menu"
                aria-label="Відкрити головне меню"
            >
                <span
                    :class="['hamburger', { open: mobileOpen }]"
                    aria-hidden="true"
                >
                    <span></span><span></span><span></span>
                </span>
                <span class="sr-only">
                    {{ mobileOpen ? 'Закрити меню' : 'Відкрити меню' }}
                </span>
            </button>

            <ul
                id="main-menu"
                class="text-2xl shadow md:text-xl md:shadow-none lg:text-2xl"
                :class="
                    mobileOpen
                        ? 'absolute top-full right-4 left-4 z-50 mt-2 flex flex-col rounded-xl bg-[var(--navbar)] p-2'
                        : 'hidden justify-center justify-self-center md:flex'
                "
            >
                <li>
                    <a
                        href="#main-hero"
                        :class="{ active: activeSection === 'main-hero' }"
                        @click.prevent="scrollToSection($event, 'main-hero')"
                        >Home</a
                    >
                </li>
                <li>
                    <a
                        href="#main-about"
                        :class="{ active: activeSection === 'main-about' }"
                        @click.prevent="scrollToSection($event, 'main-about')"
                        >About</a
                    >
                </li>
                <li>
                    <a
                        href="#main-technology"
                        :class="{ active: activeSection === 'main-technology' }"
                        @click.prevent="
                            scrollToSection($event, 'main-technology')
                        "
                        >Technology</a
                    >
                </li>
                <li>
                    <a
                        href="#main-contact"
                        :class="{ active: activeSection === 'main-contact' }"
                        @click.prevent="scrollToSection($event, 'main-contact')"
                        >Contact</a
                    >
                </li>
            </ul>

            <AppearanceTabs class="isolate justify-self-end" />
        </nav>
    </header>
</template>

<style scoped>
nav {
    position: relative;
    border: 1px solid var(--navbar-border);
    min-height: 55px;
    box-sizing: border-box;
}

nav > button {
    height: 100%;
    align-items: center;
    justify-content: center;
    padding: 0 10px;
    line-height: 0;
    background: transparent;
    box-sizing: border-box;
}

nav > ul {
    position: relative;
    list-style: none;
    height: 55px;
    isolation: isolate;
    padding: 0 15px;
    --translate-x: 0;
    --translate-y: 0;
    --rotate-x: 0deg;
}

nav > ul li,
nav > ul li a {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
}
nav > ul li a {
    padding-inline: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

nav > ul li a.active {
    color: var(--color-pink-600);
    transform: translateY(-2px);
}

button .hamburger {
    --bar-gap: 6px;
    --bar-height: 3px;
    --bar-width: 70%;
    height: 70%;
    aspect-ratio: 1 / 1;
    min-height: 20px;
    max-height: 40px;
    display: inline-flex;
    position: relative;
    align-items: center;
    justify-content: center;
}

button .hamburger span {
    position: absolute;
    left: 50%;
    top: 50%;
    height: var(--bar-height);
    width: var(--bar-width);
    background: currentColor;
    border-radius: 2px;
    transition:
        transform 200ms cubic-bezier(0.2, 0.8, 0.2, 1),
        opacity 150ms linear;
    transform-origin: center center;
    display: block;
}

button .hamburger span:nth-child(1) {
    transform: translate(-50%, calc(-50% - var(--bar-gap)));
}
button .hamburger span:nth-child(2) {
    transform: translate(-50%, -50%);
}
button .hamburger span:nth-child(3) {
    transform: translate(-50%, calc(-50% + var(--bar-gap)));
}

button .hamburger.open span:nth-child(1) {
    transform: translate(-50%, -50%) rotate(45deg);
}
button .hamburger.open span:nth-child(2) {
    opacity: 0;
    transform: translate(-50%, -50%) scaleX(0);
}
button .hamburger.open span:nth-child(3) {
    transform: translate(-50%, -50%) rotate(-45deg);
}

@media (max-width: 767px) {
    nav > ul {
        height: auto;
        padding: 8px;
        position: absolute;
        border: 1px solid var(--navbar-border);
    }
    nav > ul li {
        padding: 6px 0;
    }
    button .hamburger {
        height: 70%;
    }
}

@media (max-width: 1023px) {
    nav > ul li a {
        padding-inline: 10px;
    }
}
</style>
