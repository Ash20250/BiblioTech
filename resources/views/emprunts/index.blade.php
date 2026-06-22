<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📜 Registre des Mouvements") }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-6xl mx-auto">

            {{-- SECTION FILTRES (Encadrée et stylisée) --}}
            <div class="bg-[#FFFDF9] border border-[#4A3728] rounded-lg p-6 shadow-lg mb-8">
                <form action="{{ route('emprunts.index') }}" method="GET" class="flex flex-wrap items-end gap-8">
                    
                    {{-- Filtre Statut --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-bold text-[#4A3728] uppercase tracking-wider mb-2">Filtrer par statut</label>
                        <select name="statut" onchange="this.form.submit()" class="w-full bg-white border border-[#D2B48C] rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#8B4513] text-[#5D4037]">
                            <option value="">Tous les registres</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>Alertes retard</option>
                        </select>
                    </div>

                    {{-- Filtre Date --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-bold text-[#4A3728] uppercase tracking-wider mb-2">Date retour prévue</label>
                        <input type="date" name="date_prevue" value="{{ request('date_prevue') }}" onchange="this.form.submit()" class="w-full bg-white border border-[#D2B48C] rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#8B4513] text-[#5D4037]">
                    </div>

                    {{-- Bouton Reset --}}
                    <div>
                        <a href="{{ route('emprunts.index') }}" class="inline-block bg-[#4A3728] text-[#F4F1EA] px-6 py-2 rounded-md hover:bg-[#8B4513] transition font-bold shadow-sm">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            {{-- TABLEAU PROFESSIONNEL --}}
            <div class="shadow-2xl rounded-lg overflow-hidden border border-[#4A3728]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#4A3728] text-[#F4F1EA]">
                        <tr>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider">USAGER</th>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider">OUVRAGE</th>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider">DATE D'EMPRUNT</th>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider">RETOUR PRÉVU</th>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider">STATUT</th>
                            <th class="p-5 font-bold uppercase text-sm tracking-wider text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#FFFDF9] divide-y divide-[#D2B48C] text-[#5D4037]">
                        @forelse($emprunts as $emprunt)
                        <tr class="hover:bg-[#FDFBF7] transition">
                            <td class="p-5 font-medium">{{ $emprunt->usager->name ?? 'Inconnu' }}</td>
                            <td class="p-5 italic">{{ $emprunt->exemplaire->livre->titre ?? 'Inconnu' }}</td>
                            <td class="p-5">{{ \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') }}</td>
                            <td class="p-5">{{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y') }}</td>
                            <td class="p-5">
                                @if(!$emprunt->date_retour_effectif)
                                    <span class="{{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->isPast() ? 'text-red-600 font-bold' : 'text-blue-600' }}">
                                        {{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->isPast() ? '⚠️ En retard' : '⏳ En cours' }}
                                    </span>
                                @else
                                    <span class="text-green-600 font-bold">✅ Rendu</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                @if(!$emprunt->date_retour_effectif)
                                    <form action="{{ route('emprunts.retourner', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour ?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="bg-[#2D5A27] text-white px-4 py-2 rounded text-xs font-bold hover:bg-[#1E3D1A] transition shadow-md">
                                            VALIDER RETOUR
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center italic text-[#795548]">Aucun mouvement trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{-- PAGINATION --}}
                <div class="p-4 bg-[#F4F1EA] border-t border-[#4A3728]">
                    {{ $emprunts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>