<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📜 Registre des Mouvements d'Ouvrages") }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
            
            {{-- Notifications de succès --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6 font-serif">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-[#FFFDF9] shadow-2xl border-2 border-[#D2B48C] rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase text-xs tracking-widest">
                            <th class="p-5 border-b border-[#D2B48C]">Emprunteur</th>
                            <th class="p-5 border-b border-[#D2B48C]">Ouvrage</th>
                            <th class="p-5 border-b border-[#D2B48C]">Date de Sortie</th>
                            <th class="p-5 border-b border-[#D2B48C]">Statut / Alerte</th>
                            <th class="p-5 border-b border-[#D2B48C] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F4F1EA] text-[#5D4037] font-serif">
                        @forelse($emprunts as $emprunt)
                            @php
                                // Calcul sécurisé des jours depuis la sortie
                                $joursDepuisSortie = $emprunt->created_at ? $emprunt->created_at->diffInDays(now()) : 0;
                            @endphp
                            <tr class="hover:bg-[#FDFBF7] transition-colors">
                                {{-- Nom de l'emprunteur (avec sécurité si supprimé) --}}
                                <td class="p-5 font-bold">
                                    {{ $emprunt->salarie->nom ?? $emprunt->salarie->name ?? 'Usager inconnu' }}
                                </td>
                                
                                {{-- Titre du livre --}}
                                <td class="p-5 italic text-[#4A3728]">
                                    "{{ $emprunt->livre->titre ?? $emprunt->exemplaire->livre->titre ?? 'Ouvrage inconnu' }}"
                                </td>
                                
                                {{-- Date d'emprunt --}}
                                <td class="p-5 text-[#4A3728]">
                                    {{ $emprunt->created_at ? $emprunt->created_at->format('d/m/Y') : 'N/A' }}
                                </td>
                                
                                {{-- Colonne Statut / Alerte --}}
                                <td class="p-5">
                                    @if(!$emprunt->rendu_le)
                                        @if($joursDepuisSortie >= 14)
                                            <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-md text-xs font-bold animate-pulse shadow-sm">
                                                ⚠️ RETARD ({{ $joursDepuisSortie }} jours)
                                            </span>
                                        @else
                                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-xs font-medium border border-blue-200 shadow-sm">
                                                ⏳ En cours ({{ $joursDepuisSortie }}j)
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-md text-xs font-medium border border-green-200 shadow-sm">
                                            {{-- Sécurité Carbon pour le formatage de la date de retour --}}
                                            ✅ Rendu le {{ \Carbon\Carbon::parse($emprunt->rendu_le)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Colonne Action (Bouton relooké et sécurisé) --}}
                                <td class="p-5 text-right">
                                    @if(!$emprunt->rendu_le)
                                        <form action="{{ route('emprunts.retour', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de cet ouvrage à la bibliothèque ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-3 py-1 rounded text-xs hover:bg-[#5D4037] transition shadow-sm font-bold uppercase border-none tracking-wider">
                                                Retourner
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs italic pr-2">Clos</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-[#795548] italic bg-[#FFFDF9]">
                                    📜 Aucun mouvement d'ouvrage n'est consigné dans le registre pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>