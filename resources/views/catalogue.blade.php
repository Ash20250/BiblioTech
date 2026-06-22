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

        {{-- Barre de recherche & Ajout --}}
        <div class="bg-[#FFFDF9] p-6 rounded-lg shadow-md border border-[#D2B48C]">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                
                <form action="{{ route('catalogue') }}" method="GET" class="flex-1 flex gap-4 w-full">
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
                </form>

                @if(Auth::check() && Auth::user()->role === 'bibliothecaire')
                    <a href="{{ route('livres.create') }}" class="bg-[#2D5A27] text-white px-6 py-3 rounded-md font-bold hover:bg-[#1E3D1A] transition shadow-md whitespace-nowrap">
                        + Ajouter un livre
                    </a>
                @endif
            </div>
        </div>

        {{-- Tableau du Catalogue --}}
        <div class="bg-[#FFFDF9] rounded-lg shadow-xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase tracking-widest text-sm">
                        <th class="p-5 border-b border-[#8B4513]">Favori</th>
                        <th class="p-5 border-b border-[#8B4513]">Titre</th>
                        <th class="p-5 border-b border-[#8B4513]">Auteur</th>
                        <th class="p-5 border-b border-[#8B4513]">Catégorie</th>
                        <th class="p-5 border-b border-[#8B4513] text-center">Actions</th>
                        @if(Auth::check() && Auth::user()->role === 'bibliothecaire')
                            <th class="p-5 border-b border-[#8B4513] text-right">Gestion</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-[#5D4037] font-serif">
                    @forelse($livres as $livre)
                        @php
                            $userId = Auth::id();
                            $nbDispo = $livre->exemplaires->where('statut_id', 1)->count();
                            $monExemplaireReserve = $userId ? $livre->exemplaires->where('reserved_by_user_id', $userId)->first() : null;
                            // On vérifie si le livre est déjà dans les favoris de l'utilisateur
                            $isFavori = $userId ? $livre->favoris->contains('user_id', $userId) : false;
                        @endphp
                        
                        <tr class="border-b border-[#F4F1EA] hover:bg-[#FDFBF7] transition-colors">
                            {{-- Colonne Cœur --}}
                            <td class="p-5 text-center">
                                @auth
                                    <form action="{{ route('livres.toggle-favorite', $livre->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="transition-transform hover:scale-125">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $isFavori ? 'fill-red-500 text-red-500' : 'fill-white text-gray-300' }} stroke-current" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endauth
                            </td>

                            <td class="p-5 font-bold text-[#4A3728]">{{ $livre->titre }}</td>
                            <td class="p-5 italic text-[#795548]">{{ $livre->auteur->nom ?? 'Auteur inconnu' }}</td>
                            <td class="p-5 text-sm">{{ $livre->categorie->nom ?? 'Général' }}</td>
                            
                            <td class="p-5 text-center">
                                @auth
                                    @if(Auth::user()->role !== 'bibliothecaire')
                                        <div class="flex flex-col gap-2">
                                            <div class="text-[10px] uppercase font-bold mb-1">
                                                @if($nbDispo > 0)
                                                    <span class="text-green-600">● {{ $nbDispo }} disponible(s)</span>
                                                @else
                                                    <span class="text-red-500">● Épuisé</span>
                                                @endif
                                            </div>

                                            @if($nbDispo > 0)
                                                <form action="{{ route('emprunter.livre', $livre->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-[#4A3728] text-white px-3 py-1 rounded hover:bg-[#8B4513] transition font-bold shadow text-xs">Emprunter</button>
                                                </form>
                                            @elseif($nbDispo == 0)
                                                <form action="{{ route('reserver.exemplaire', $livre->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 transition font-bold shadow text-xs">Réserver</button>
                                                </form>
                                            @endif
                                            
                                            @if($monExemplaireReserve)
                                                <span class="text-green-700 text-[10px] font-bold">✓ Réservé pour vous</span>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            </td>

                            @if(Auth::check() && Auth::user()->role === 'bibliothecaire')
                                <td class="p-5 text-right uppercase text-xs font-bold">
                                    <a href="{{ route('livres.edit', $livre->id) }}" class="text-blue-600 mr-4 hover:underline">Modifier</a>
                                    <form action="{{ route('livres.destroy', $livre->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce livre ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center italic text-gray-500">Aucun ouvrage trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $livres->links() }}
        </div>
    </div>
</x-app-layout>