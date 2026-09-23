<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project Flow — Create account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="project-flow-login min-h-screen overflow-x-hidden bg-[#032a42] text-white">
    <main x-data="{ showPassword: false, showConfirmation: false }" class="relative isolate flex min-h-screen flex-col overflow-hidden">
        <video class="absolute inset-0 -z-10 h-full w-full object-cover" autoplay loop muted playsinline preload="metadata" aria-hidden="true"><source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4" type="video/mp4"></video>

        <section class="relative z-10 mx-auto flex w-full max-w-7xl flex-1 items-center justify-center px-6 py-16 sm:py-20" aria-labelledby="register-title">
            <div class="grid w-full max-w-5xl -translate-y-3 items-center gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(360px,420px)] lg:gap-16">
                <div class="text-center lg:text-left">
                    <p class="animate-fade-rise font-inter text-sm text-white/65">Project Flow</p>
                    <h1 id="register-title" class="animate-fade-rise mt-3 font-instrument text-5xl font-normal leading-[.95] tracking-[-2.46px] text-white sm:text-6xl" style="font-family: 'Instrument Serif', serif;">Make room for <em class="not-italic text-white/60">what matters.</em></h1>
                    <p class="animate-fade-rise-delay mx-auto mt-5 max-w-sm font-inter text-base leading-relaxed text-white/65 lg:mx-0">Create your account and bring every project, task, and next step into focus.</p>
                </div>

                <div class="animate-fade-rise-delay-2 liquid-glass rounded-[1.5rem] p-5 text-left sm:p-7">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="mb-2 block font-inter text-sm font-medium text-white">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="project-flow-input w-full rounded-xl px-4 py-3 text-base text-white outline-none" placeholder="Your name" aria-describedby="name-error">
                            @error('name') <p id="name-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="mb-2 block font-inter text-sm font-medium text-white">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" inputmode="email" class="project-flow-input w-full rounded-xl px-4 py-3 text-base text-white outline-none" placeholder="you@example.com" aria-describedby="email-error">
                            @error('email') <p id="email-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="mb-2 block font-inter text-sm font-medium text-white">Password</label>
                            <div class="relative">
                                <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="new-password" class="project-flow-input w-full rounded-xl px-4 py-3 pr-12 text-base text-white outline-none" placeholder="Create a password" aria-describedby="password-error">
                                <button type="button" x-on:click="showPassword = !showPassword" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center rounded-r-xl text-white/65 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-white/70" x-bind:aria-label="showPassword ? 'Hide password' : 'Show password'"><svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg><svg x-show="showPassword" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A11 11 0 0 1 12 5c6 0 9.5 7 9.5 7a16.6 16.6 0 0 1-3.2 3.9M6.1 6.1C3.8 7.8 2.5 12 2.5 12S6 19 12 19c1.1 0 2.1-.2 3-.5" stroke-linecap="round"/></svg></button>
                            </div>
                            @error('password') <p id="password-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block font-inter text-sm font-medium text-white">Confirm password</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" x-bind:type="showConfirmation ? 'text' : 'password'" required autocomplete="new-password" class="project-flow-input w-full rounded-xl px-4 py-3 pr-12 text-base text-white outline-none" placeholder="Repeat your password" aria-describedby="password-confirmation-error">
                                <button type="button" x-on:click="showConfirmation = !showConfirmation" class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center rounded-r-xl text-white/65 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-white/70" x-bind:aria-label="showConfirmation ? 'Hide password confirmation' : 'Show password confirmation'"><svg x-show="!showConfirmation" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg><svg x-show="showConfirmation" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A11 11 0 0 1 12 5c6 0 9.5 7 9.5 7a16.6 16.6 0 0 1-3.2 3.9M6.1 6.1C3.8 7.8 2.5 12 2.5 12S6 19 12 19c1.1 0 2.1-.2 3-.5" stroke-linecap="round"/></svg></button>
                            </div>
                            @error('password_confirmation') <p id="password-confirmation-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="liquid-glass inline-flex min-h-14 w-full items-center justify-center rounded-full px-8 py-4 font-inter text-base font-medium text-white transition hover:scale-[1.03] focus:outline-none focus-visible:ring-4 focus-visible:ring-white/60">Create account</button>
                    </form>
                    <p class="mt-5 text-center font-inter text-sm text-white/65">Already have an account? <a href="{{ route('login') }}" class="text-white underline underline-offset-4 hover:text-white/70">Sign in</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
