<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const menuList = ref<HTMLElement | null>(null);
const mobileOpen = ref(false);
const toggleMobile = () => (mobileOpen.value = !mobileOpen.value);
let anim: number | null = null;
let currentActiveItem: HTMLElement | null = null;
let cleanupFns: Array<() => void> = [];

onMounted(() => {
    const nav = menuList.value;
    if (!nav) return;

    const items = Array.from(nav.querySelectorAll('li a')) as HTMLElement[];

    const linkById = new Map<string, HTMLElement>();
    const sections: Array<{ id: string; el: HTMLElement }> = [];

    const animate = (from: number, to: number) => {
        if (anim) window.clearInterval(anim);

        const start = Date.now();
        anim = window.setInterval(() => {
            const p = Math.min((Date.now() - start) / 500, 1);
            const e = 1 - Math.pow(1 - p, 3);

            const x = from + (to - from) * e;
            const y = -40 * (4 * e * (1 - e));
            const r = 200 * Math.sin(p * Math.PI);

            nav.style.setProperty('--translate-x', `${x}px`);
            nav.style.setProperty('--translate-y', `${y}px`);
            nav.style.setProperty('--rotate-x', `${r}deg`);

            if (p >= 1) {
                window.clearInterval(anim!);
                anim = null;
                nav.style.setProperty('--translate-y', '0px');
                nav.style.setProperty('--rotate-x', '0deg');
            }
        }, 16);
    };

    const getCurrentPosition = () =>
        parseFloat(nav.style.getPropertyValue('--translate-x')) || 0;

    const getItemCenter = (item: HTMLElement) => {
        return (
            item.getBoundingClientRect().left +
            item.offsetWidth / 2 -
            nav.getBoundingClientRect().left -
            5
        );
    };

    const moveToItem = (item: HTMLElement) => {
        const current = getCurrentPosition();
        const center = getItemCenter(item);
        animate(current, center);
        nav.classList.add('show-indicator');
    };

    const setActiveItem = (item: HTMLElement) => {
        if (currentActiveItem) {
            currentActiveItem.classList.remove('active');
        }

        currentActiveItem = item;
        item.classList.add('active');
        moveToItem(item);
    };

    const handleMouseLeave = () => {
        if (currentActiveItem) {
            moveToItem(currentActiveItem);
        } else {
            nav.classList.remove('show-indicator');
            if (anim) window.clearInterval(anim);
        }
    };

    items.forEach((item) => {
        const enter = () => moveToItem(item);
        const leave = () => handleMouseLeave();
        const click = (e: Event) => {
            mobileOpen.value = false;
            const el = item as HTMLAnchorElement;
            const dataTarget = el.dataset.target;

            if (dataTarget) {
                e.preventDefault();
                setActiveItem(item);
                const targetEl = document.getElementById(dataTarget);
                if (targetEl) {
                    const header = nav.closest('header') as HTMLElement | null;
                    const offset = (header?.offsetHeight ?? 0) + 8;
                    const y =
                        targetEl.getBoundingClientRect().top +
                        window.scrollY -
                        offset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
                return;
            }

            const href = el.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                setActiveItem(item);
                const targetId = href.slice(1);
                const targetEl = document.getElementById(targetId);
                if (targetEl) {
                    const header = nav.closest('header') as HTMLElement | null;
                    const offset = (header?.offsetHeight ?? 0) + 8;
                    const y =
                        targetEl.getBoundingClientRect().top +
                        window.scrollY -
                        offset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            } else {
                setActiveItem(item);
            }
        };
        item.addEventListener('mouseenter', enter);
        item.addEventListener('mouseleave', leave);
        item.addEventListener('click', click);
        cleanupFns.push(() => {
            item.removeEventListener('mouseenter', enter);
            item.removeEventListener('mouseleave', leave);
            item.removeEventListener('click', click);
        });
    });

    const initSections = () => {
        linkById.clear();
        sections.length = 0;
        items.forEach((item) => {
            const el = item as HTMLAnchorElement;
            const id = el.dataset.target;
            if (!id) return;
            const target = document.getElementById(id);
            if (!target) return;
            linkById.set(id, item);
            sections.push({ id, el: target });
        });
    };
    initSections();

    const navLeave = () => handleMouseLeave();
    nav.addEventListener('mouseleave', navLeave);
    cleanupFns.push(() => nav.removeEventListener('mouseleave', navLeave));

    const updateActiveOnScroll = () => {
        const header = nav.closest('header') as HTMLElement | null;
        const offset = (header?.offsetHeight ?? 0) + 8;
        const pivot = window.scrollY + offset + 1;
        let activeId: string | null = null;
        let bestTop = -Infinity;
        for (const { id, el } of sections) {
            const top = el.getBoundingClientRect().top + window.scrollY;
            if (top <= pivot && top > bestTop) {
                bestTop = top;
                activeId = id;
            }
        }
        if (!activeId && sections.length) activeId = sections[0].id;
        if (activeId) {
            const link = linkById.get(activeId);
            if (link) setActiveItem(link);
        }
    };

    let ticking = false;
    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            ticking = false;
            updateActiveOnScroll();
        });
    };

    window.addEventListener('scroll', onScroll, {
        passive: true,
    } as AddEventListenerOptions);
    window.addEventListener('resize', () => {
        initSections();
        onScroll();
        if (window.innerWidth >= 768) mobileOpen.value = false;
    });
    cleanupFns.push(() => window.removeEventListener('scroll', onScroll));

    const wrapper = nav.closest('nav') as HTMLElement | null;
    const onDocumentClick = (ev: MouseEvent) => {
        if (!mobileOpen.value) return;
        const target = ev.target as Node | null;
        if (!wrapper) return;
        if (target && !wrapper.contains(target)) {
            mobileOpen.value = false;
        }
    };
    document.addEventListener('click', onDocumentClick);
    cleanupFns.push(() =>
        document.removeEventListener('click', onDocumentClick),
    );

    window.setTimeout(() => {
        updateActiveOnScroll();
    }, 100);
});

onBeforeUnmount(() => {
    if (anim) {
        window.clearInterval(anim);
        anim = null;
    }
    cleanupFns.forEach((fn) => fn());
    cleanupFns = [];
});
</script>

<template>
    <header class="fixed top-[15px] z-50 container w-full">
        <nav
            class="grid grid-cols-3 items-center gap-4 rounded-full bg-[var(--navbar)] px-5 md:text-xl lg:text-2xl shadow ring-1 ring-black/5 backdrop-blur-lg backdrop-contrast-105 backdrop-saturate-150 dark:ring-white/10"
        >
            <a href="/" class="logo">LOGO</a>
            <button
                @click="toggleMobile"
                :aria-expanded="mobileOpen"
                aria-controls="main-menu"
                class="flex h-[55px] cursor-pointer items-center justify-self-center rounded-md p-0 md:hidden"
                title="Toggle menu"
            >
                <!-- use a span wrapper (valid inside button) instead of div -->
                <span
                    :class="['hamburger', { open: mobileOpen }]"
                    aria-hidden="true"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <span class="sr-only">Toggle menu</span>
            </button>
            <ul
                id="main-menu"
                ref="menuList"
                :class="
                    mobileOpen
                        ? 'absolute top-full right-4 left-4 z-50 mt-2 flex flex-col rounded-xl bg-[var(--navbar)] p-2'
                        : 'hidden justify-center justify-self-center md:flex'
                "
            >
                <li><a data-target="main-hero">Home</a></li>
                <li><a data-target="main-about">About</a></li>
                <li><a data-target="main-technology">Technology</a></li>
                <li><a data-target="main-contact">Contact</a></li>
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
nav > ul::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 6px;
    width: 12px;
    height: 12px;
    background: black;
    border-radius: 50%;
    transform: translateX(var(--translate-x, 0))
        translateY(var(--translate-y, 0)) rotate(var(--rotate-x, 0deg));
    transition: none;
    opacity: 0;
    z-index: -1;
    box-shadow:
        0 4px 16px rgba(255, 255, 255, 0.4),
        0 2px 8px rgba(255, 255, 255, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.8),
        inset 0 -1px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.6);
}
.dark nav > ul::after {
    background: white;
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
nav > ul li a:hover {
    color: var(--color-pink-600);
    transform: translateY(-2px);
}
nav > ul li a:focus {
    color: var(--color-pink-600);
    outline: none;
}
nav > ul li a.active {
    color: var(--color-pink-600);
    transform: translateY(-2px);
}
nav > ul.show-indicator::after {
    opacity: 1;
}
nav > ul li a:active {
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

@media (max-width: 1023px) {
    nav > ul li a {
        padding-inline: 10px;
    }
}

@media (max-width: 767px) {
    nav > ul {
        height: auto;
        padding: 8px;
        position: absolute;
    }
    nav > ul::after {
        display: none;
    }
    nav > ul li {
        padding: 6px 0;
    }
    button .hamburger {
        height: 70%;
    }
}
</style>
