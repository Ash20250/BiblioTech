<nav x-data="{ open: false }" class="bg-[#4A3728] border-b border-[#3D2B1E] shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <span class="text-2xl">📜</span>
                        <span class="text-[#F4F1EA] font-serif text-2xl font-bold tracking-tighter group-hover:text-[#D2B48C] transition-colors">
                            BiblioTech
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-[#F4F1EA] hover:text-[#D2B48C] border-none uppercase text-[10px] tracking-widest font-bold">
                        {{ __('Accueil') }}
                    </x-nav-link>

                    <x-nav-link :href="route('catalogue')" :active="request()->routeIs('catalogue')" class="text-[#F4F1EA] hover:text-[#D2B48C] border-none uppercase text-[10px] tracking-widest font-bold">
                        {{ __('Catalogue') }}
                    </x-nav-link>

                    <x-nav-link :href="route('salaries.index')" :active="request()->routeIs('salaries.index')" class="text-[#F4F1EA] hover:text-[#D2B48C] border-none uppercase text-[10px] tracking-widest font-bold">
                        {{ __('Membres') }}
                    </x-nav-link>

                    <a href="{{ route('emprunts.index') }}" class="bg-[#8B4513] hover:bg-[#A0522D] text-[#F4F1EA] px-4 py-2 rounded shadow-md uppercase text-[10px] tracking-widest font-bold transition-all transform active:scale-95 no-underline border-none">
                        {{ __('Gestion des Emprunts') }}
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('profile.edit') }}" class="text-[#D2B48C] text-[10px] font-bold uppercase tracking-widest border-r border-[#3D2B1E] pr-4 hover:text-[#F4F1EA] transition-colors">
                    {{ Auth::user()->name }}
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="text-[#F4F1EA] hover:text-red-400 uppercase text-[10px] tracking-widest font-bold transition-colors cursor-pointer border-none bg-transparent font-serif">
                        {{ __('Quitter') }}
                    </button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 text-[#F4F1EA] hover:bg-[#3D2B1E] rounded-md transition duration-150">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#3D2B1E]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-[#F4F1EA]">
                {{ __('Accueil') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('catalogue')" :active="request()->routeIs('catalogue')" class="text-[#F4F1EA]">
                {{ __('Catalogue') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('emprunts.index')" :active="request()->routeIs('emprunts.index')" class="text-[#F4F1EA]">
                {{ __('Gestion des Emprunts') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 font-bold">
                    {{ __('Quitter la session') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>