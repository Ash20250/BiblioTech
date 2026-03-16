<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728]">
            {{ __("📚 Catalogue de la Bibliothèque") }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 py-6">
        
        {{-- Affichage des messages de succès ou d'erreur --}}
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

                    {{-- BOUTON AJOUTER : SEULEMENT POUR L'ADMIN --}}
                    @auth
                        @if(Auth::user()->email == 'ashdh@gmail.com')
                            <a href="{{ route('livres.create') }}" class="bg-green-700 text-white px-6 py-3 rounded-md font-bold hover:bg-green-800 transition shadow-md text-center">
                                ➕ Ajouter
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="disponible" value="1" id="dispo" {{ request('disponible') ? 'checked' : '' }} class="rounded border-[#D2B48C] text-[#8B4513] focus:ring-[#8B4513]">
                    <label for="dispo" class="text-sm text-[#795548] italic font-serif">Afficher uniquement les livres disponibles immédiatement</label>
                </div>
            </form>
            
            <div class="mt-4 text-right">
                <span class="text-[#795548] italic font-serif text-sm">
                    Affichage de {{ $livres->firstItem() ?? 0 }} à {{ $livres->lastItem() ?? 0 }} sur <strong class="text-[#4A3728]">{{ $livres->total() }}</strong> ouvrages.
                </span>
            </div>
        </div>

        <div class="bg-[#FFFDF9] rounded-lg shadow-xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase tracking-widest text-sm">
                        <th class="p-5 border-b border-[#8B4513]">Titre</th>
                        <th class="p-5 border-b border-[#8B4513]">Auteur</th>
                        <th class="p-5 border-b border-[#8B4513]">Catégorie</th>
                        <th class="p-5 border-b border-[#8B4513] text-center">Disponibilité</th>
                        
                        {{-- GESTION : SEULEMENT POUR L'ADMIN --}}
                        @auth
                            @if(Auth::user()->email == 'ashdh@gmail.com')
                                <th class="p-5 border-b border-[#8B4513] text-right">Gestion</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="text-[#5D4037] font-serif">
                    @forelse($livres as $livre)
                        @php
                            $dispo = $livre->exemplaires->filter(function($ex) {
                                return $ex->emprunts->where('date_retour_effectif', null)->isEmpty();
                            })->count();
                        @endphp
                        <tr class="border-b border-[#F4F1EA] hover:bg-[#FDFBF7] transition-colors">
                            <td class="p-5 font-bold text-[#4A3728]">{{ $livre->titre }}</td>
                            <td class="p-5 italic text-[#795548]">{{ $livre->auteur->nom }}</td>
                            <td class="p-5 text-sm">{{ $livre->categorie->nom }}</td>
                            <td class="p-5 text-center">
                                @if($dispo > 0)
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold border border-green-200">
                                        {{ $dispo }} en rayon
                                    </span>

                                    {{-- ACTION EMPRUNTER : SEULEMENT POUR L'USAGER CONNECTÉ --}}
                                    @auth
                                        @if(Auth::user()->email !== 'ashdh@gmail.com')
                                            <form action="{{ route('emprunter.livre', $livre->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit" class="text-xs bg-[#4A3728] text-white px-3 py-1 rounded hover:bg-[#8B4513] transition shadow-sm font-sans uppercase tracking-tighter">
                                                    Prendre ce livre
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold border border-red-200">
                                        🚫 Épuisé
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS ADMIN --}}
                            @auth
                                @if(Auth::user()->email == 'ashdh@gmail.com')
                                    <td class="p-5 text-right">
                                        <div class="flex justify-end gap-4 uppercase text-xs font-bold">
                                            <a href="{{ route('livres.edit', $livre->id) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>
                                            <form action="{{ route('livres.destroy', $livre->id) }}" method="POST" onsubmit="return confirm('Supprimer cet ouvrage ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center italic text-[#795548]">Aucun ouvrage trouvé dans le grimoire.</td>
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