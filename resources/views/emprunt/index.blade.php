<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📜 Registre des Mouvements d'Ouvrages") }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-6xl mx-auto">
            
            <div class="bg-[#FFFDF9] shadow-2xl border-2 border-[#D2B48C] rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase text-sm tracking-widest">
                            <th class="p-4 border-b border-[#D2B48C]">Emprunteur</th>
                            <th class="p-4 border-b border-[#D2B48C]">Ouvrage</th>
                            <th class="p-4 border-b border-[#D2B48C]">Date de Sortie</th>
                            <th class="p-4 border-b border-[#D2B48C]">Statut / Alerte</th>
                            <th class="p-4 border-b border-[#D2B48C]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#D2B48C]">
                        @forelse($emprunts as $emprunt)
                            <tr class="hover:bg-[#F4F1EA] transition-colors">
                                <td class="p-4 font-bold text-[#5D4037]">{{ $emprunt->salarie->nom }}</td>
                                <td class="p-4 italic text-[#8B4513]">"{{ $emprunt->livre->titre }}"</td>
                                <td class="p-4 text-[#4A3728]">{{ $emprunt->created_at->format('d/m/Y') }}</td>
                                
                                <td class="p-4">
                                    @php
                                        // LOGIQUE DU RETARD 14 JOURS
                                        $joursDepuisSortie = $emprunt->created_at->diffInDays(now());
                                    @endphp

                                    @if(!$emprunt->rendu_le)
                                        @if($joursDepuisSortie >= 14)
                                            <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-md text-xs font-bold animate-pulse">
                                                ⚠️ RETARD ({{ $joursDepuisSortie }} jours)
                                            </span>
                                        @else
                                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-xs font-medium border border-blue-200">
                                                En cours ({{ $joursDepuisSortie }}j)
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-md text-xs font-medium border border-green-200">
                                            ✅ Rendu le {{ $emprunt->rendu_le->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    @if(!$emprunt->rendu_le)
                                        <form action="{{ route('emprunts.retour', $emprunt->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-[#8B4513] hover:text-red-700 font-bold text-xs uppercase underline">
                                                Enregistrer Retour
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Clos</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-[#795548] italic">
                                    Aucun emprunt n'est consigné dans le registre pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>