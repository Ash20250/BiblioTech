<x-app-layout>
    <div class="py-12 bg-[#F4F1EA] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#4A3728] border-b-2 border-[#A67B5B] inline-block pb-2">
                    Registre de la BiblioTech
                </h2>
                <p class="text-[#765C48] mt-4 italic text-lg">Bienvenue, Maître des Livres {{ Auth::user()->name }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <a href="/catalogue" class="relative bg-[#FFFDF9] p-8 rounded-lg shadow-md border-2 border-[#D2B48C] hover:shadow-2xl hover:border-[#8B4513] transition-all group overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-[#D2B48C] opacity-10 group-hover:opacity-20 rounded-bl-full transition-opacity"></div>
                    <div class="text-4xl mb-4">📖</div>
                    <h4 class="text-2xl font-bold text-[#5D4037] mb-3">Le Catalogue</h4>
                    <p class="text-[#795548] leading-relaxed italic">Feuilleter l'inventaire complet des ouvrages et la liste des membres.</p>
                    <div class="mt-8 flex items-center text-[#8B4513] font-bold uppercase text-xs tracking-widest">
                        Ouvrir le registre <span class="ml-2 group-hover:translate-x-2 transition-transform">↣</span>
                    </div>
                </a>

                <a href="/emprunt" class="relative bg-[#FFFDF9] p-8 rounded-lg shadow-md border-2 border-[#D2B48C] hover:shadow-2xl hover:border-[#2E7D32] transition-all group">
                    <div class="text-4xl mb-4">✒️</div>
                    <h4 class="text-2xl font-bold text-[#5D4037] mb-3">Nouvel Emprunt</h4>
                    <p class="text-[#795548] leading-relaxed italic">Inscrire un nouveau prêt dans le grand livre de la bibliothèque.</p>
                    <div class="mt-8 flex items-center text-[#2E7D32] font-bold uppercase text-xs tracking-widest">
                        Rédiger une fiche <span class="ml-2 group-hover:translate-x-2 transition-transform">↣</span>
                    </div>
                </a>

                <a href="#" class="relative bg-[#FFFDF9] p-8 rounded-lg shadow-md border-2 border-[#D2B48C] hover:shadow-2xl hover:border-[#546E7A] transition-all group">
                    <div class="text-4xl mb-4">📜</div>
                    <h4 class="text-2xl font-bold text-[#5D4037] mb-3">Administration</h4>
                    <p class="text-[#795548] leading-relaxed italic">Gérer vos informations personnelles et vos accès de bibliothécaire.</p>
                    <div class="mt-8 flex items-center text-[#546E7A] font-bold uppercase text-xs tracking-widest">
                        Modifier le profil <span class="ml-2 group-hover:translate-x-2 transition-transform">↣</span>
                    </div>
                </a>

            </div>

            <div class="mt-16 text-center">
                <div class="inline-block p-4 border-t border-b border-[#D2B48C]">
                    <p class="text-[#8B4513] text-sm uppercase tracking-[0.3em]">Ex Libris - BiblioTech 2026</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>