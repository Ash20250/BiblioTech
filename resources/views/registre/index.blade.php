<x-app-layout>
    <div class="py-12 bg-[#F4F1EA] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-serif text-[#4A3728] font-bold">📜 Registre des Emprunts</h1>
            </div>

            <div class="flex gap-4 mb-6">
                <a href="{{ route('registre.index') }}" class="px-4 py-2 bg-[#8B4513] text-white rounded text-sm font-bold uppercase tracking-widest hover:bg-[#A0522D] transition-all">Tous</a>
                <a href="{{ route('registre.index', ['statut' => 'en_cours']) }}" class="px-4 py-2 bg-[#D2B48C] text-[#4A3728] rounded text-sm font-bold uppercase tracking-widest hover:bg-[#C19A6B] transition-all">En cours</a>
                <a href="{{ route('registre.index', ['statut' => 'retard']) }}" class="px-4 py-2 bg-red-800 text-white rounded text-sm font-bold uppercase tracking-widest hover:bg-red-900 transition-all">En retard</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#D2B48C]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#4A3728] text-[#F4F1EA]">
                        <tr>
                            <th class="p-4 uppercase text-xs tracking-widest">Utilisateur</th>
                            <th class="p-4 uppercase text-xs tracking-widest">Livre</th>
                            <th class="p-4 uppercase text-xs tracking-widest">Statut</th>
                            <th class="p-4 uppercase text-xs tracking-widest">Date de retour</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($emprunts as $emprunt)
                            <tr class="hover:bg-[#F4F1EA] transition-colors">
                                <td class="p-4 text-sm text-gray-700">{{ $emprunt->user->name ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-gray-700 font-medium">{{ $emprunt->livre->titre }}</td>
                                <td class="p-4 text-sm">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase 
                                        {{ $emprunt->statut === 'retard' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $emprunt->statut }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-gray-500">{{ $emprunt->date_retour_prevue }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500 italic">Aucun emprunt trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>