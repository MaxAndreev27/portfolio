<script setup lang="ts">
import { FooterSettings } from '@/types';

defineProps<{
    footerSettings: FooterSettings;
}>();
</script>

<template>
    <footer
        id="footer-hero"
        class="footer-hero footer mx-auto mt-15 mb-5 flex w-full max-w-360 justify-center bg-transparent px-5"
    >
        <div
            class="footer-container mx-auto w-full rounded-xl border border-border bg-card p-4 shadow sm:p-8"
        >
            <div
                v-if="
                    footerSettings.footer_social_links &&
                    footerSettings.footer_social_links.length > 0
                "
                class="footer-content mb-4 flex items-center justify-center gap-5"
            >
                <a
                    v-for="(link, index) in footerSettings.footer_social_links"
                    :key="index"
                    :href="link.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    :aria-label="link.label"
                    class="social-icon transform text-current transition-colors hover:-translate-y-1"
                >
                    <img
                        v-if="link.icon"
                        width="48"
                        height="48"
                        :src="link.icon"
                        :alt="link.label"
                /></a>
            </div>
            <div
                class="footer-bottom border-top[var(--navbar-border)] border-t pt-5 text-center"
            >
                <p v-if="footerSettings.footer_copyright">
                    &copy; {{ new Date().getFullYear() }}
                    <span>{{ footerSettings.footer_copyright }}</span>
                </p>
                <p v-if="footerSettings.footer_powered">
                    {{ footerSettings.footer_powered }}
                </p>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.footer-section p,
.footer-section a {
    text-decoration: none;
    margin-bottom: 8px;
    display: block;
    transition: color 0.3s ease;
}

@media (prefers-reduced-motion: no-preference) {
    html {
        scroll-behavior: smooth;
    }
}
</style>
