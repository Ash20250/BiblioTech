<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-2xl text-[#4A3728]">
                {{ __("📅 Registre des Prêts et Emprunts") }}
            </h2>
            {{-- ✅ CORRECTION DE LA ROUTE AU PLURIEL --}}
            <a href="{{ route('emprunts.create') }}" class="bg-[#8B4513] hover:bg-[#A0522D] text-[#F4F1EA] px-4 py-2 rounded shadow-md uppercase text-xs tracking-widest font-bold transition-all transform active:scale-95 no-underline border-none">
                + Inscrire un nouveau prêt
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-4 font-serif">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ BARRE DE FILTRAGE COMBINÉE --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 p-4 bg-white rounded-lg shadow-sm border border-[#D2B48C] font-serif">
            
            {{-- Partie 1 : Boutons de statut stylisés --}}
            <div class="flex gap-2">
                <a href="{{ route('emprunts.index') }}" 
                   class="px-4 py-2 rounded text-sm transition-all no-underline {{ !request('statut') && !request('date_prevue') ? 'bg-[#4A3728] text-white' : 'bg-white text-[#4A3728] border border-[#D2B48C] hover:bg-[#FDFBF7]' }}">
                    📜 Tous les registres
                </a>
                
                <a href="{{ route('emprunts.index', ['statut' => 'en_cours']) }}" 
                   class="px-4 py-2 rounded text-sm transition-all no-underline {{ request('statut') === 'en_cours' ? 'bg-[#8B4513] text-white' : 'bg-white text-[#8B4513] border border-[#D2B48C] hover:bg-[#FDFBF7]' }}">
                    ⏳ Emprunts en cours
                </a>

                <a href="{{ route('emprunts.index', ['statut' => 'en_retard']) }}" 
                   class="px-4 py-2 rounded text-sm transition-all no-underline {{ request('statut') === 'en_retard' ? 'bg-red-700 text-white' : 'bg-white text-red-700 border border-red-200 hover:bg-red-50' }}">
                    🚨 Alertes retard
                </a>
            </div>

            {{-- Partie 2 : Filtrage par date --}}
            <form action="{{ route('emprunts.index') }}" method="GET" class="flex items-center gap-3 border-l border-[#D2B48C] pl-4 mb-0">
                @if(request('statut'))
                    <input type="hidden" name="statut" value="{{ request('statut') }}">
                @endif
                
                <div class="flex flex-col">
                    <label for="date_prevue" class="text-xs text-[#4A3728] font-bold uppercase mb-1">Retour prévu le :</label>
                    <div class="flex items-center gap-2">
                        <input type="date" 
                               name="date_prevue" 
                               id="date_prevue"
                               value="{{ request('date_prevue') }}"
                               class="border-[#D2B48C] rounded shadow-sm focus:ring-[#8B4513] focus:border-[#8B4513] text-sm p-2 bg-white">
                        
                        <button type="submit" class="bg-[#4A3728] text-white px-3 py-2 rounded text-sm hover:bg-[#5D4037] transition font-bold shadow-sm">
                            Filtrer
                        </button>

                        @if(request('date_prevue'))
                            <a href="{{ route('emprunts.index', request()->except('date_prevue')) }}" class="text-xs text-red-600 hover:underline font-bold" title="Effacer la date">
                                ✕
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- ✅ TABLEAU DES REGISTRES --}}
        <div class="bg-[#FFFDF9] rounded-lg shadow-xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase text-xs tracking-wider">
                        <th class="p-5">Usager</th>
                        <th class="p-5">Ouvrage</th>
                        <th class="p-5">Date d'emprunt</th>
                        <th class="p-5">Retour Prévu</th>
                        <th class="p-5 text-center">Statut</th>
                        <th class="p-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[#5D4037] font-serif">
                    @forelse($emprunts as $emprunt)
                        @php
                            $estEnRetard = !$emprunt->date_retour && \Carbon\Carbon::parse($emprunt->date_retour_prevue)->isPast();
                        @endphp
                        <tr class="border-b border-[#F4F1EA] {{ $estEnRetard ? 'bg-red-50' : '' }} hover:bg-[#FDFBF7] transition-colors">
                            <td class="p-5 font-bold">{{ $emprunt->usager->name ?? 'Usager inconnu' }}</td>
                            <td class="p-5 italic text-[#4A3728]">{{ $emprunt->exemplaire->livre->titre ?? 'Livre inconnu' }}</td>
                            <td class="p-5">{{ \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') }}</td>
                            <td class="p-5 {{ $estEnRetard ? 'text-red-600 font-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y') }}
                            </td>
                            <td class="p-5 text-center">
                                @if($emprunt->date_retour)
                                    <span class="text-green-600 font-bold text-sm">✅ Rendu</span>
                                @elseif($estEnRetard)
                                    <span class="text-red-600 animate-pulse font-bold text-xs bg-red-100 px-2 py-1 rounded">⚠️ RETARD</span>
                                @else
                                    <span class="text-blue-600 text-sm">⏳ En cours</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                @if(!$emprunt->date_retour)
                                    <form action="{{ route('emprunts.retourner', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de cet ouvrage ?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-3 py-1 rounded text-xs hover:bg-[#5D4037] transition shadow-sm font-bold uppercase">
                                            Retourner
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic">Classé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center italic text-[#795548]">Aucun emprunt ne correspond à votre recherche actuelle.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ PAGINATION (Qui profite maintenant de ta superbe customisation app.css) --}}
        <div class="mt-6">
            {{ $emprunts->links() }}
        </div>
    </div>
</x-app-layout>