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

- **PHP ^8.3** with the `pdo_sqlite` extension enabled
- **Node.js** (LTS version recommended) & **NPM**
- **Composer**

## 📖 Local Development Setup

### 1. Initial One-Step Initialization

The project includes a unified setup command that automatically installs PHP and Node dependencies, copies environment files, creates keys, triggers schema migrations, and pre-builds assets:

```bash
composer run setup
```
