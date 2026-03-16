<x-app-layout>
    <div class="min-h-[80vh] flex flex-col items-center justify-center">
        <div class="text-center mb-10">
            <span class="text-7xl">🏛️</span>
            <h1 class="mt-4 text-5xl font-serif font-bold text-[#4A3728] tracking-tight">BiblioTech</h1>
            <p class="mt-2 text-[#795548] italic font-serif">Gestion du fonds documentaire</p>
        </div>

        <div class="bg-[#FFFDF9] p-10 rounded-lg shadow-2xl border-2 border-[#D2B48C] w-full max-w-md text-center">
            <h2 class="text-2xl font-serif font-bold text-[#4A3728] mb-6">Accès au Registre</h2>
            
            <p class="text-[#5D4037] mb-8 text-sm leading-relaxed">
                Veuillez vous identifier pour consulter le catalogue ou enregistrer un nouvel emprunt.
            </p>

            <div class="space-y-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/catalogue') }}" 
                           class="block w-full py-4 bg-[#8B4513] hover:bg-[#5D4037] text-[#F4F1EA] font-bold rounded shadow-lg transition-all uppercase tracking-widest">
                            Entrer dans la Bibliothèque
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full py-4 bg-[#8B4513] hover:bg-[#5D4037] text-[#F4F1EA] font-bold rounded shadow-lg transition-all uppercase tracking-widest">
                            Se Connecter
                        </a>

                        @if (Route::has('register'))
                            <div class="pt-4">
                                <p class="text-xs text-[#795548] mb-2 italic">Nouveau bibliothécaire ?</p>
                                <a href="{{ route('register') }}" 
                                   class="text-[#8B4513] font-bold hover:underline decoration-[#D2B48C] underline-offset-4">
                                    Créer un compte registre
                                </a>
                            </div>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        <footer class="mt-12 text-[#8B4513] text-xs font-serif opacity-70 italic">
            — Projet BTS Blanc · Gestion de Bibliothèque © 2026 —
        </footer>
    </div>
</x-app-layout>