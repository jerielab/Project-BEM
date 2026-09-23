<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Velorah — Reset password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="velorah-login min-h-screen overflow-x-hidden bg-[#032a42] text-white">
    <main class="relative isolate flex min-h-screen flex-col overflow-hidden">
        <video class="absolute inset-0 -z-10 h-full w-full object-cover" autoplay loop muted playsinline preload="metadata" aria-hidden="true"><source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4" type="video/mp4"></video>

        <section class="relative z-10 mx-auto flex w-full max-w-7xl flex-1 items-center justify-center px-6 py-16 sm:py-20" aria-labelledby="forgot-password-title">
            <div class="grid w-full max-w-5xl -translate-y-3 items-center gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(360px,420px)] lg:gap-16">
                <div class="text-center lg:text-left">
                    <p class="animate-fade-rise font-inter text-sm text-white/65">Project Flow</p>
                    <h1 id="forgot-password-title" class="animate-fade-rise mt-3 font-instrument text-5xl font-normal leading-[.95] tracking-[-2.46px] text-white sm:text-6xl" style="font-family: 'Instrument Serif', serif;">Find your way <em class="not-italic text-white/60">back in.</em></h1>
                    <p class="animate-fade-rise-delay mx-auto mt-5 max-w-sm font-inter text-base leading-relaxed text-white/65 lg:mx-0">Enter your email and we will send you a secure link to choose a new password.</p>
                </div>

                <div class="animate-fade-rise-delay-2 liquid-glass rounded-[1.5rem] p-5 text-left sm:p-7">
                    <x-auth-session-status class="mb-4 text-sm text-emerald-200" :status="session('status')" />
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block font-inter text-sm font-medium text-white">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" inputmode="email" class="velorah-input w-full rounded-xl px-4 py-3 text-base text-white outline-none" placeholder="you@example.com" aria-describedby="email-error">
                            @error('email') <p id="email-error" class="mt-2 text-sm text-rose-200" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="liquid-glass inline-flex min-h-14 w-full items-center justify-center rounded-full px-8 py-4 font-inter text-base font-medium text-white transition hover:scale-[1.03] focus:outline-none focus-visible:ring-4 focus-visible:ring-white/60">Email reset link</button>
                    </form>
                    <p class="mt-5 text-center font-inter text-sm text-white/65"><a href="{{ route('login') }}" class="text-white underline underline-offset-4 hover:text-white/70">Back to sign in</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
