<script setup lang="ts"></script>

<template>
    <footer
        id="footer-hero"
        class="footer-hero footer mb-5 flex w-full justify-center px-5"
    >
        <div
            class="footer-container mx-auto w-full rounded-xl bg-card p-8 shadow lg:container"
        >
            <div
                class="footer-content mb-8 grid grid-cols-1 gap-8 sm:grid-cols-3"
            >
                <div class="footer-section">
                    <h3>Company</h3>
                    <a href="#about">About Us</a>
                    <a href="#services">Services</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#portfolio">Portfolio</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
                <div class="footer-section">
                    <h3>Connect</h3>
                    <a href="#">hello@company.com</a>
                    <a href="#">+1 (555) 123-4567</a>
                    <a href="#">Follow Us</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>
                    &copy; {{ new Date().getFullYear() }} Max Andreev. All
                    rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.footer {
    background: transparent;
}

.footer-container {
    border: 1px solid var(--border);
}

.footer-section h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.footer-section p,
.footer-section a {
    text-decoration: none;
    margin-bottom: 8px;
    display: block;
    transition: color 0.3s ease;
    font-size: 0.9rem;
}

.footer-section a:hover {
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #333;
}

@media (prefers-reduced-motion: no-preference) {
    html {
        scroll-behavior: smooth;
    }
}
</style>
