<x-app-layout>
    <div class="py-12 bg-[#f4ece1] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header --}}
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold text-amber-900 italic">📜 Mon Espace Adhérent</h2>
                @if($nbLivresEnCours > 0)
                    <div class="bg-amber-800 text-white px-6 py-2 rounded-full shadow-lg border border-amber-600 font-bold">
                        {{ $nbLivresEnCours }} livre(s) en votre possession
                    </div>
                @endif
            </div>

            {{-- BLOC SUPÉRIEUR : Profil, Favoris et Réservations --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Carte Profil --}}
                <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-amber-800">
                    <p class="text-xs uppercase text-gray-400 font-bold tracking-widest">Adhérent</p>
                    <p class="text-xl text-amber-900 font-bold mb-4">{{ $user->name ?? 'Invité' }}</p>
                    <p class="text-xs uppercase text-gray-400 font-bold tracking-widest">Email</p>
                    <p class="text-lg text-amber-900">{{ $user->email ?? 'N/A' }}</p>
                </div>

                {{-- Section Favoris --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-amber-100">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">❤️ Coups de cœur</h3>
                    <ul class="space-y-3">
                        @forelse($favoris as $f)
                            <li class="flex justify-between items-center text-amber-800 italic">
                                <span>📖 {{ $f->livre->titre ?? 'Inconnu' }}</span>
                                <form action="{{ route('favoris.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Retirer des favoris ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs px-2">✕</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">Aucun favori.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Section Réservations --}}
                <div class="bg-white p-6 rounded-xl shadow-md border border-amber-100">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">📅 Réservations</h3>
                    <ul class="space-y-3">
                        @forelse($reservations as $r)
                            <li class="flex justify-between items-center text-amber-800">
                                <span>🔖 {{ $r->livre->titre ?? 'Inconnu' }}</span>
                                <form action="{{ route('reservations.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Annuler la réservation ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1 rounded-full text-[10px] font-bold">Annuler</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">Aucune réservation.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Historique des lectures --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-amber-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-amber-900">📚 Historique des lectures</h3>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-amber-50 text-amber-900">
                        <tr>
                            <th class="p-4 font-bold uppercase text-xs">Ouvrage</th>
                            <th class="p-4 font-bold uppercase text-xs">Emprunté le</th>
                            <th class="p-4 font-bold uppercase text-xs text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($emprunts as $emprunt)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="p-4 font-semibold text-amber-800">{{ $emprunt->exemplaire->livre->titre ?? 'Livre inconnu' }}</td>
                                <td class="p-4 text-gray-600">{{ $emprunt->date_emprunt ? \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') : 'N/A' }}</td>
                                <td class="p-4 text-center">
                                    {{-- CORRECTION ICI : utilisation de date_retour_effectif --}}
                                    @if($emprunt->date_retour_effectif)
                                        <span class="text-green-600 font-bold text-sm">✅ RENDU</span>
                                    @else
                                        <form action="{{ route('emprunts.retourner', $emprunt->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="bg-amber-800 text-white px-4 py-1 rounded-full text-xs hover:bg-amber-950 transition">Rendre</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-gray-400 italic">Aucun historique disponible.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>