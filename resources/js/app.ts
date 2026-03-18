import { createInertiaApp } from '@inertiajs/vue3';
import Echo from 'laravel-echo';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Pusher from 'pusher-js';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

window.Pusher = Pusher;

// console.log('appName: ' + import.meta.env.VITE_APP_NAME);
// console.log('key: ' + import.meta.env.VITE_REVERB_APP_KEY);
// console.log('wsHost: ' + import.meta.env.VITE_REVERB_HOST);
// console.log('wsPort: ' + import.meta.env.VITE_REVERB_PORT);
// console.log('wssPort: ' + import.meta.env.VITE_REVERB_PORT);
// console.log('forceTLS: ' + import.meta.env.VITE_REVERB_SCHEME);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    autoConnect: false,
});

import.meta.glob([
  './assets/images/**',
]);

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
