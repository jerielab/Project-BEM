<!DOCTYPE html>
<html lang="en" x-data x-bind:class="{ 'dark': $store.theme.dark }" x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>{{ config('app.name', 'Project Flow') }} — @yield('title', 'Dashboard')</title>
    <style>
        /* Must load before the Vite stylesheet: stops Alpine overlays flashing at page paint. */
        [x-cloak] { display: none !important; }
        html { background: #0f172a; color-scheme: dark; }
    </style>
    <script>
        // Set the stored colour preference synchronously, before the body is painted.
        (() => {
            try {
                const savedTheme = localStorage.getItem('theme');
                const dark = savedTheme ? savedTheme === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
            } catch (_) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600&family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ request()->routeIs('dashboard', 'projects.*') ? 'dashboard-page' : '' }} font-sans antialiased bg-teal-50 text-slate-900 dark:bg-[#0F172A] dark:text-[#F8FAFC] min-h-screen">
    <a href="#main-content" class="sr-only fixed left-4 top-4 z-50 rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white focus:not-sr-only focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-300">Skip navigation</a>
    @include('layouts.navigation')

        <main id="main-content" tabindex="-1" class="{{ request()->routeIs('dashboard', 'projects.*') ? 'w-full p-0' : 'max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8' }} focus:outline-none">        @if (session('status') && !request()->routeIs('projects.*'))
            <div class="mb-6 rounded-lg border border-teal-600/30 bg-teal-600/10 px-4 py-3 text-sm text-teal-700 dark:text-teal-300">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
