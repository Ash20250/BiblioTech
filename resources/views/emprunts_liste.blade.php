<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📜 Registre Officiel des Prêts") }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        @if(session('success'))
            <div class="max-w-5xl mx-auto p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold shadow-md animate-bounce">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="max-w-5xl mx-auto bg-[#FFFDF9] rounded-lg shadow-2xl border-2 border-[#D2B48C] overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4A3728] text-[#F4F1EA] font-serif uppercase tracking-widest text-xs">
                        <th class="p-5 border-b border-[#8B4513]">👤 Emprunteur</th>
                        <th class="p-5 border-b border-[#8B4513]">📚 Ouvrage & Thème</th>
                        <th class="p-5 border-b border-[#8B4513]">📅 Sortie</th>
                        <th class="p-5 border-b border-[#8B4513]">⏳ Statut</th>
                        <th class="p-5 border-b border-[#8B4513] text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[#5D4037] font-serif text-base">
                    @forelse($emprunts as $emprunt)
                        @php
                            // 1. Calcul du retard
                            $estEnRetard = $emprunt->created_at->diffInDays(now()) > 14;

                            // 2. Logique étendue des couleurs par thème (alignée sur le Seeder)
                            $couleurs = [
                                'Informatique' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Management'   => 'bg-purple-100 text-purple-800 border-purple-200',
                                'Roman'        => 'bg-green-100 text-green-800 border-green-200',
                                'Droit'        => 'bg-red-100 text-red-800 border-red-200',
                                'SF'           => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'BD'           => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Sciences'     => 'bg-teal-100 text-teal-800 border-teal-200',
                                'Art'          => 'bg-pink-100 text-pink-800 border-pink-200',
                            ];
                            $themeStyle = $couleurs[$emprunt->livre->theme] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        
                        <tr class="border-b border-[#F4F1EA] {{ $estEnRetard ? 'bg-red-50' : 'hover:bg-[#FDFBF7]' }} transition-colors">
                            <td class="p-5">
                                <span class="font-bold text-[#4A3728]">{{ $emprunt->salarie->nom }}</span>
                                <span class="text-[10px] block text-[#795548] opacity-70 italic uppercase tracking-tighter">
                                    {{ $emprunt->salarie->ville }}
                                </span>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col items-start">
                                    <span class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider {{ $themeStyle }} mb-1">
                                        {{ $emprunt->livre->theme ?? 'Divers' }}
                                    </span>
                                    <span class="italic text-[#795548] leading-tight">
                                        {{ $emprunt->livre->titre }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-5 text-sm">
                                {{ $emprunt->created_at->format('d/m/Y') }}
                            </td>

                            <td class="p-5">
                                @if($estEnRetard)
                                    <span class="px-2 py-1 bg-red-600 text-white text-[10px] rounded-full font-bold uppercase shadow-sm">
                                        ⚠️ RETARD
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-[10px] rounded-full font-bold uppercase">
                                        En cours
                                    </span>
                                @endif
                            </td>

                            <td class="p-5 text-center">
                                <form action="{{ route('emprunt.retourner', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de ce livre en rayon ?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="bg-[#8B4513] hover:bg-green-700 text-[#F4F1EA] px-4 py-2 rounded-md font-bold text-[10px] uppercase tracking-widest shadow-md transition-all active:scale-95">
                                        📥 Retour
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center italic text-[#795548]">
                                <div class="text-5xl mb-4 opacity-30">📚</div>
                                <p class="text-xl">Le registre est vide.</p>
                                <p class="text-sm opacity-70">Tous les ouvrages sont actuellement disponibles.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('emprunt.create') }}" class="inline-block px-8 py-3 bg-[#D2B48C] text-[#4A3728] rounded-full font-bold hover:bg-[#8B4513] hover:text-white transition-all shadow-lg transform hover:-translate-y-1">
                + Enregistrer une nouvelle sortie
            </a>
        </div>
    </div>
</x-app-layout>