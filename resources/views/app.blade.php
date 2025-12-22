<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.svg" type="image/svg+xml" sizes="any">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" />
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" media="print" onload="this.media='all'">

        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap" media="print" onload="this.media='all'">

        <noscript>
            <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" />
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Onest:wght@300;900&display=swap" />
        </noscript>

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
