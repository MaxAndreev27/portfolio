<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';

const menuList = ref<HTMLElement | null>(null);
let anim: number | null = null;
let currentActiveItem: HTMLElement | null = null;
let cleanupFns: Array<() => void> = [];

onMounted(() => {
    const nav = menuList.value;
    if (!nav) return;

    const items = Array.from(nav.querySelectorAll('li a')) as HTMLElement[];

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
            const el = item as HTMLAnchorElement;
            const dataTarget = el.dataset.target;

            if (dataTarget) {
                e.preventDefault();
                setActiveItem(item);
                const targetEl = document.getElementById(dataTarget);
                if (targetEl) {
                    const header = nav.closest('header') as HTMLElement | null;
                    const offset = (header?.offsetHeight ?? 0) + 8; // small gap
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
                    const offset = (header?.offsetHeight ?? 0) + 8; // small gap
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

    const navLeave = () => handleMouseLeave();
    nav.addEventListener('mouseleave', navLeave);
    cleanupFns.push(() => nav.removeEventListener('mouseleave', navLeave));

    if (items.length > 0) {
        window.setTimeout(() => {
            setActiveItem(items[0]);
        }, 100);
    }
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
    <header
        class="fixed top-0 z-50 container w-full text-2xl bg-white/50 px-2 shadow ring-1 ring-black/5 backdrop-blur-lg backdrop-contrast-105 backdrop-saturate-150 dark:bg-zinc-900/50 dark:ring-white/10"
    >
        <nav class="grid grid-cols-3 items-center gap-4">
            <ul ref="menuList" class="col-start-2 justify-self-center">
                <li><a data-target="main-hero">Home</a></li>
                <li><a data-target="main-about">About</a></li>
                <li><a data-target="section3">Services</a></li>
                <li><a data-target="section4">Contact</a></li>
            </ul>
            <AppearanceTabs class="isolate col-start-3 justify-self-end" />
        </nav>
    </header>
</template>

<style scoped>
nav {
    position: relative;
}

nav > ul {
    position: relative;
    list-style: none;
    display: flex;
    justify-content: center;
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
    color: #000000;
    transform: translateY(-2px);
}
nav > ul li a:focus {
    outline: none;
}
nav > ul li a.active {
    transform: translateY(-2px);
}
nav > ul.show-indicator::after {
    opacity: 1;
}
nav > ul li a:active {
    transform: translateY(-2px);
}
</style>
