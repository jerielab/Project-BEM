<nav class="border-b border-teal-800/15 bg-teal-50/75 backdrop-blur-xl dark:border-[#334155] dark:bg-[#111827]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="font-semibold text-lg text-teal-600 dark:text-teal-400">
                    Project Flow
                </a>
                @auth
                <div class="hidden sm:flex gap-6 text-sm">
                    <a href="{{ route('dashboard') }}" class="cursor-pointer hover:text-teal-600 dark:hover:text-teal-400 {{ request()->routeIs('dashboard') ? 'text-teal-600 dark:text-teal-400 font-medium' : 'text-slate-600 dark:text-slate-300' }}">Dashboard</a>
                    <a href="{{ route('projects.index') }}" class="cursor-pointer hover:text-teal-600 dark:hover:text-teal-400 {{ request()->routeIs('projects.*') ? 'text-teal-600 dark:text-teal-400 font-medium' : 'text-slate-600 dark:text-slate-300' }}">Projects</a>
                </div>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="button"
                    x-on:click="$store.theme.toggle()"
                    class="relative cursor-pointer rounded-md p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500"
                    aria-label="Toggle dark mode"
                >
                    <span class="relative block h-5 w-5">
                        
                        <svg
                            class="absolute inset-0 h-5 w-5 transition-all duration-500 ease-[cubic-bezier(.16,1,.3,1)]"
                            :class="!$store.theme.dark ? 'opacity-100 scale-100 rotate-0' : 'opacity-0 scale-50 rotate-90'"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                        ><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m8.25-9H21M3 12h1.5m14.16-6.16-1.06 1.06M6.34 17.66l-1.06 1.06m0-13.44 1.06 1.06M17.66 17.66l1.06 1.06M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/></svg>

                        
                        <svg
                            class="absolute inset-0 h-5 w-5 transition-all duration-500 ease-[cubic-bezier(.16,1,.3,1)]"
                            :class="$store.theme.dark ? 'opacity-100 scale-100 rotate-0' : 'opacity-0 scale-50 -rotate-90'"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                        ><circle cx="12" cy="12" r="8.25"/><path stroke-linecap="round" d="M3.75 12h16.5M12 3.75c2.4 2.2 2.4 14.3 0 16.5M12 3.75c-2.4 2.2-2.4 14.3 0 16.5"/><path stroke-linecap="round" d="M5.2 7.5c2.6 1.4 11 1.4 13.6 0M5.2 16.5c2.6-1.4 11-1.4 13.6 0"/></svg>
                    </span>
                </button>

                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cursor-pointer text-sm text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400">Sign out</button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</nav>
