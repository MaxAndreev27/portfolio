<script setup lang="ts">
</script>

<template>
    <section id="footer-hero" class="footer-hero footer w-full">
        <div class="container mx-auto footer-container bg-card rounded-xl shadow">
            <div class="footer-content">
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
                <p>&copy; {{ new Date().getFullYear() }} Max Andreev. All rights reserved.</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
.footer {
    background: transparent;
    display: flex;
    justify-content: center;
    padding: 0 20px;
}

.footer-container {
    border: 1px solid var(--border);
    padding: 40px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
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

/* Responsive Design */
@media (max-width: 768px) {
    .footer-container {
        margin: 0 10px;
        padding: 30px 20px;
    }

    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 20px;
    }
}

@media (max-width: 480px) {

}

/* Smooth scroll enhancement */
@media (prefers-reduced-motion: no-preference) {
    html {
        scroll-behavior: smooth;
    }
}
</style>
