<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>Project Flow — Sign in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

        .project-flow-video {
            transition: transform 900ms cubic-bezier(.65, 0, .35, 1);
            transform-origin: 56% 62%;
            will-change: transform;
            backface-visibility: hidden;
            transform: translateZ(0) scale(1);
        }
        .project-flow-video.is-zooming { transform: translateZ(0) scale(2.2); }

        .project-flow-scene-content {
            transition: opacity 450ms ease, transform 450ms ease;
            will-change: opacity, transform;
        }
        .project-flow-scene-content.is-fading { opacity: 0; transform: scale(0.94); }

        .project-flow-flash {
            position: fixed; inset: 0; background: #fff; opacity: 0;
            pointer-events: none; transition: opacity 220ms ease; z-index: 60;
        }
        .project-flow-flash.is-visible { opacity: 1; }

        @media (prefers-reduced-motion: reduce) {
            .project-flow-video, .project-flow-scene-content, .project-flow-flash { transition: none !important; }
        }
    </style>
</head>
<body class="project-flow-login min-h-screen overflow-x-hidden bg-[#032a42] text-white">
    <main x-data="loginForm()" class="relative isolate flex min-h-screen flex-col overflow-hidden">
        <video
            x-bind:class="{ 'is-zooming': animating }"
            class="project-flow-video absolute inset-0 -z-10 h-full w-full object-cover"
            autoplay loop muted playsinline preload="metadata" aria-hidden="true"
        ><source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4" type="video/mp4"></video>

        <div class="project-flow-flash" x-bind:class="{ 'is-visible': flashing }"></div>

        <section class="relative z-10 mx-auto flex w-full max-w-7xl flex-1 items-center justify-center px-6 pb-20 pt-10 sm:pb-28" aria-labelledby="login-title">
            <div
                x-bind:class="{ 'is-fading': animating }"
                class="project-flow-scene-content grid w-full max-w-5xl -translate-y-3 items-center gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(360px,420px)] lg:gap-16"
            >
                <div class="text-center lg:text-left">
                    <p class="animate-fade-rise font-inter text-sm text-white/65">Project Flow</p>
                    <h1 id="login-title" class="animate-fade-rise mt-3 font-instrument text-5xl font-normal leading-[.95] tracking-[-2.46px] text-white sm:text-6xl" style="font-family: 'Instrument Serif', serif;">Return to <em class="not-italic text-white/60">what matters.</em></h1>
                    <p class="animate-fade-rise-delay mx-auto mt-5 max-w-sm font-inter text-base leading-relaxed text-white/65 lg:mx-0">Sign in to continue tracking your projects with calm, clarity, and momentum</p>
                </div>

                <div class="animate-fade-rise-delay-2 liquid-glass rounded-[1.5rem] p-5 text-left sm:p-7">
                    <x-auth-session-status class="mb-4 text-sm text-emerald-200" :status="session('status')" />
                    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-on:submit.prevent="submit($event)">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block font-inter text-sm font-medium text-white">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" inputmode="email" class="project-flow-input w-full rounded-xl px-4 py-3 text-base text-white outline-none" placeholder="you@example.com" aria-describedby="email-error">
                            @error('email') <p id="email-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                            <p x-show="errors.email" x-cloak x-text="errors.email" id="email-error-js" class="mt-2 text-sm text-rose-200" role="alert"></p>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3"><label for="password" class="font-inter text-sm font-medium text-white">Password</label>@if (Route::has('password.request'))<a href="{{ route('password.request') }}" class="text-sm text-white/70 underline-offset-4 hover:text-white hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60">Forgot password?</a>@endif</div>
                            <div class="relative"><input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="project-flow-input w-full rounded-xl px-4 py-3 pr-12 text-base text-white outline-none" placeholder="Enter your password" aria-describedby="password-error"><button type="button" x-on:click="showPassword = !showPassword" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center rounded-r-xl text-white/65 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-white/70" x-bind:aria-label="showPassword ? 'Hide password' : 'Show password'"><svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg><svg x-show="showPassword" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A11 11 0 0 1 12 5c6 0 9.5 7 9.5 7a16.6 16.6 0 0 1-3.2 3.9M6.1 6.1C3.8 7.8 2.5 12 2.5 12S6 19 12 19c1.1 0 2.1-.2 3-.5" stroke-linecap="round"/></svg></button></div>
                            @error('password') <p id="password-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                            <p x-show="errors.password" x-cloak x-text="errors.password" id="password-error-js" class="mt-2 text-sm text-rose-200" role="alert"></p>
                        </div>
                        <label for="remember" class="flex cursor-pointer items-center gap-2 text-sm text-white/75"><input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-white/30 bg-white/10 text-white focus:ring-white/70">Remember me on this device</label>
                        <button
                            type="submit"
                            x-bind:disabled="submitting"
                            class="liquid-glass inline-flex min-h-14 w-full items-center justify-center rounded-full px-14 py-4 font-inter text-base font-medium text-white transition hover:scale-[1.03] focus:outline-none focus-visible:ring-4 focus-visible:ring-white/60 disabled:opacity-70"
                        >
                            <span x-show="!submitting">Sign in</span>
                            <span x-show="submitting" x-cloak>Signing in…</span>
                        </button>
                    </form>
                    <p class="mt-5 text-center font-inter text-sm text-white/65">New to Project Flow? <a href="{{ route('register') }}" class="text-white underline underline-offset-4 hover:text-white/70">Create an account</a></p>
                </div>
            </div>
        </section>
    </main>


</body>
</html>
