# Portfolio - Full-Stack Laravel 13 & Vue 3 Application

This repository contains a modern, high-performance, and reactive full-stack portfolio built with **Laravel 13**, **Vue 3**, and **Inertia.js**.

The architecture features a powerful developer dashboard via Filament, real-time reactive capabilities using Laravel Reverb, and advanced smooth UI/UX animations driven by GSAP and Parallax.js.

## 🚀 Key Architectural Features

- **Core Framework** — Powered by **Laravel 13** and **Vue 3** working as a single-page application (SPA) without API endpoints, orchestrated seamlessly via **Inertia.js**.
- **Modern Styling & Engine** — Built utilizing the utility-first **Tailwind CSS v4** engine integrated natively inside **Vite 8**.
- **Real-Time Integration** — Implemented **Laravel Reverb** (a first-party WebSocket server) and **Laravel Echo** for live events, push notifications, or status changes.
- **Content Management & Admin Panel** — Built on **Filament v5** providing a beautiful, rapid-delivery administrative panel, alongside multi-language support through `spatie/laravel-translatable`.
- **State & Authentication** — Scalable application state using **VueUse**, global tracking with **Laravel Pulse**, clean logging with **Laravel Pail**, and secure authentication via **Laravel Fortify**.
- **Dynamic Motion & Components** — Animations handled dynamically with **GSAP** and **Parallax.js**, paired with unstyled accessible primitives from **Reka UI** and icons by **Lucide Vue**.

## 🛠️ Prerequisites

Before installing the project, verify that your local environment includes:

- **PHP ^8.5** with the `pdo_sqlite` and `gd` (with WebP support) extensions enabled
- **Node.js 24** (CI and Docker use 24; Vite 8 requires `^20.19.0 || >=22.12.0`) & **NPM**
- **Composer** 2

## 📖 Local Development Setup

### 1. Initial One-Step Initialization

The project includes a unified setup command that automatically installs PHP and Node dependencies, copies environment files, creates keys, triggers schema migrations, and pre-builds assets:

```bash
composer run setup
```

## 🖼️ Project Images

Upload a single original image per project in the admin panel. Responsive WebP variants (480, 768 and 1200 px wide) are generated automatically on create/update and removed together with the original. To generate variants for projects that already exist, run once:

```bash
php artisan projects:generate-image-variants
```

## ⚡ Server-Side Rendering

Production uses Inertia SSR: `npm run build:ssr` builds the client and the SSR bundle, and the Docker image runs the SSR server under Supervisor.
