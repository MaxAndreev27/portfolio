<script setup lang="ts"></script>

<template>
    <footer
        id="footer-hero"
        class="bg-transparent footer-hero footer mb-5 flex w-full justify-center px-5"
    >
        <div
            class="footer-container p-4 sm:p-8 mx-auto w-full rounded-xl border border-[var(--border)] bg-card shadow lg:container"
        >
            <div
                class="footer-content mb-4 flex items-center justify-center gap-5"
            >
                <a
                    href="https://github.com/MaxAndreev27"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="GitHub"
                    class="social-icon transform text-current transition-colors hover:-translate-y-1"
                >
                    <img
                        width="48px"
                        height="48px"
                        src="@/assets/images/github.svg"
                        alt="LinkedIn"
                    />
                </a>
                <a
                    href="https://www.linkedin.com/in/your-profile"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="LinkedIn"
                    class="social-icon transform text-current transition-colors hover:-translate-y-1"
                >
                    <img
                        width="48px"
                        height="48px"
                        src="@/assets/images/linkedin.svg"
                        alt="LinkedIn"
                    />
                </a>
            </div>
            <div
                class="footer-bottom border-top[var(--navbar-border)] border-t pt-5 text-center"
            >
                <p>
                    &copy; {{ new Date().getFullYear() }} Max Andreev. All
                    rights reserved.
                </p>
                <p>Powered by Laravel and Vue</p>
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
