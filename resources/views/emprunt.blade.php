<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif text-2xl text-[#4A3728]">
                {{ __("📅 Registre des Prêts et Emprunts") }}
            </h2>
            <a href="{{ route('emprunt.create') }}" class="bg-[#8B4513] hover:bg-[#A0522D] text-[#F4F1EA] px-4 py-2 rounded shadow-md uppercase text-xs tracking-widest font-bold transition-all transform active:scale-95 no-underline border-none">
                + Inscrire un nouveau prêt
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#FFFDF9] rounded-lg shadow-xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase text-sm">
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
                            $estEnRetard = !$emprunt->date_retour_effectif && \Carbon\Carbon::parse($emprunt->date_retour_prevue)->isPast();
                        @endphp
                        <tr class="border-b border-[#F4F1EA] {{ $estEnRetard ? 'bg-red-50' : '' }} hover:bg-[#FDFBF7] transition-colors">
                            <td class="p-5 font-bold">{{ $emprunt->usager->name ?? 'Usager inconnu' }}</td>
                            <td class="p-5 italic text-[#4A3728]">{{ $emprunt->exemplaire->livre->titre ?? 'Livre inconnu' }}</td>
                            <td class="p-5">{{ \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') }}</td>
                            <td class="p-5 {{ $estEnRetard ? 'text-red-600 font-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y') }}
                            </td>
                            <td class="p-5 text-center">
                                @if($emprunt->date_retour_effectif)
                                    <span class="text-green-600 font-bold">✅ Rendu</span>
                                @elseif($estEnRetard)
                                    <span class="text-red-600 animate-pulse font-bold text-xs">⚠️ RETARD</span>
                                @else
                                    <span class="text-blue-600">⏳ En cours</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                @if(!$emprunt->date_retour_effectif)
                                    <form action="{{ route('emprunts.retourner', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de cet ouvrage ?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-3 py-1 rounded text-xs hover:bg-[#5D4037] transition shadow-sm">
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
                            <td colspan="6" class="p-10 text-center italic text-[#795548]">Aucun emprunt enregistré dans les archives.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $emprunts->links() }}
        </div>

        <div class="mt-4 text-xs text-[#8B4513] italic">
            * Les emprunts sont limités à une durée de 30 jours calendaires.
        </div>
    </div>
</x-app-layout>