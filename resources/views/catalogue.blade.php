<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728]">
            {{ __("📚 Catalogue de la Bibliothèque") }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 py-6">
        
        {{-- Messages de notification --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-4">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-4">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Barre de recherche --}}
        <div class="bg-[#FFFDF9] p-6 rounded-lg shadow-md border border-[#D2B48C]">
            <form action="{{ route('catalogue') }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre ou auteur..." 
                        class="flex-1 p-3 border-2 border-[#D2B48C] rounded-md focus:border-[#8B4513] focus:ring-0 outline-none">
                    
                    <select name="categorie_id" class="border-2 border-[#D2B48C] rounded-md text-[#5D4037]">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-6 py-3 rounded-md font-bold hover:bg-[#5D4037] transition shadow-md">
                        🔍 Rechercher
                    </button>

                    @auth
                        @if(Auth::user()->role === 'bibliothecaire')
                            <a href="{{ route('livres.create') }}" class="bg-green-700 text-white px-6 py-3 rounded-md font-bold hover:bg-green-800 transition shadow-md text-center">
                                ➕ Ajouter
                            </a>
                        @endif
                    @endauth
                </div>
            </form>
        </div>

        {{-- Tableau du Catalogue --}}
        <div class="bg-[#FFFDF9] rounded-lg shadow-xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase tracking-widest text-sm">
                        <th class="p-5 border-b border-[#8B4513]">Titre</th>
                        <th class="p-5 border-b border-[#8B4513]">Auteur</th>
                        <th class="p-5 border-b border-[#8B4513]">Catégorie</th>
                        <th class="p-5 border-b border-[#8B4513] text-center">Disponibilité</th>
                        @auth
                            @if(Auth::user()->role === 'bibliothecaire')
                                <th class="p-5 border-b border-[#8B4513] text-right">Gestion</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="text-[#5D4037] font-serif">
                    @forelse($livres as $livre)
                        @php
                            $userId = Auth::id();

                            // 1. Exemplaires physiquement présents en rayon (ni réservés, ni empruntés)
                            $exemplairesEnRayon = $livre->exemplaires->filter(function($ex) {
                                return is_null($ex->reserved_by_user_id) && $ex->emprunts->where('date_retour', null)->isEmpty();
                            });

                            // 2. Exemplaires actuellement empruntés mais sans réservation (donc réservables)
                            $exemplairesReservables = $livre->exemplaires->filter(function($ex) {
                                return is_null($ex->reserved_by_user_id) && $ex->emprunts->where('date_retour', null)->isNotEmpty();
                            });

                            // 3. Vérifier si l'utilisateur connecté a réservé un exemplaire
                            $monExemplaireReserve = $userId ? $livre->exemplaires->where('reserved_by_user_id', $userId)->first() : null;
                            
                            $nbEnRayon = $exemplairesEnRayon->count();
                        @endphp
                        
                        <tr class="border-b border-[#F4F1EA] hover:bg-[#FDFBF7] transition-colors">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    @auth
                                        <form action="{{ route('favoris.toggle', $livre->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-2xl transition transform hover:scale-125 focus:outline-none">
                                                @if(Auth::user()->favoris->contains($livre->id))
                                                    <span class="text-red-500">❤️</span>
                                                @else
                                                    <span class="text-gray-300 hover:text-red-300">🤍</span>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth
                                    <span class="font-bold text-[#4A3728]">{{ $livre->titre }}</span>
                                </div>
                            </td>
                            
                            <td class="p-5 italic text-[#795548]">{{ $livre->auteur->nom ?? 'Auteur inconnu' }}</td>
                            <td class="p-5 text-sm">{{ $livre->categorie->nom ?? 'Général' }}</td>
                            
                            <td class="p-5 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    
                                    {{-- 🔐 ZONE AUTHENTIFIÉE : Adhérents & Bibliothécaires connectés --}}
                                    @auth
                                        @if($monExemplaireReserve)
                                            {{-- CAS : J'ai déjà une réservation --}}
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">
                                                ✅ Réservé pour vous
                                            </span>
                                            <form action="{{ route('emprunter.livre', $livre->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-32 text-xs bg-[#4A3728] text-white px-3 py-1 rounded hover:bg-[#8B4513] transition uppercase font-bold shadow">
                                                    Emprunter
                                                </button>
                                            </form>
                                        @else
                                            {{-- Statut Visuel selon les stocks --}}
                                            @if($nbEnRayon > 0)
                                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">
                                                    {{ $nbEnRayon }} en rayon
                                                </span>
                                            @elseif($exemplairesReservables->isNotEmpty())
                                                <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold border border-orange-200">
                                                    ⏳ Indisponible
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-200">
                                                    🚫 Épuisé
                                                </span>
                                            @endif

                                            {{-- Boutons d'action visibles uniquement pour les clients/adhérents --}}
                                            @if(Auth::user()->role !== 'bibliothecaire')
                                                <div class="flex flex-col gap-2 mt-1">
                                                    @if($nbEnRayon > 0)
                                                        <form action="{{ route('emprunter.livre', $livre->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="w-32 text-xs bg-[#4A3728] text-white px-3 py-1 rounded hover:bg-[#8B4513] transition uppercase font-bold shadow">
                                                                Emprunter
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($exemplairesReservables->isNotEmpty())
                                                        <form action="{{ route('reserver.exemplaire', $exemplairesReservables->first()->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="w-32 text-xs bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition uppercase font-bold shadow">
                                                                Réserver
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endauth

                                    {{-- 🌍 ZONE INVITÉ : Visiteurs non connectés --}}
                                    @guest
                                        @if($nbEnRayon > 0)
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">
                                                ✔ Disponible ({{ $nbEnRayon }})
                                            </span>
                                        @elseif($exemplairesReservables->isNotEmpty())
                                            <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-800 text-xs font-bold border border-orange-200">
                                                ⏳ Emprunté (Réservable)
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-200">
                                                🚫 Non disponible
                                            </span>
                                        @endif
                                        <span class="text-[10px] text-gray-400 italic">Connectez-vous pour interagir</span>
                                    @endguest

                                </div>
                            </td>

                            {{-- Actions Bibliothécaire --}}
                            @auth
                                @if(Auth::user()->role === 'bibliothecaire')
                                    <td class="p-5 text-right uppercase text-xs font-bold">
                                        <a href="{{ route('livres.edit', $livre->id) }}" class="text-blue-600 mr-4 hover:underline">Modifier</a>
                                        <form action="{{ route('livres.destroy', $livre->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce livre ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                        </form>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center italic text-gray-500">Aucun ouvrage trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $livres->links() }}
        </div>
    </div>
</x-app-layout>