<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12" style="background-color: #f4ece1; min-height: 100vh; font-family: 'Georgia', serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-amber-900 italic">📜 Mon Espace Adhérent</h2>
                @if(isset($nbLivresEnCours) && $nbLivresEnCours > 0)
                    <div class="bg-amber-800 text-white px-4 py-2 rounded-full shadow-md border border-amber-600">
                        {{ $nbLivresEnCours }} livre(s) en votre possession
                    </div>
                @endif
            </div>

            <div class="bg-white border-b-4 border-amber-800 rounded-lg shadow-lg p-6 mb-8 border border-amber-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs uppercase text-gray-400 font-bold tracking-widest">Nom de l'adhérent</p>
                        <p class="text-xl text-amber-900 font-bold">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400 font-bold tracking-widest">Email enregistré</p>
                        <p class="text-xl text-amber-900">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border-t-4 border-amber-800 rounded-lg shadow-lg overflow-hidden border border-amber-200">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-amber-900 mb-6 underline decoration-amber-500">📚 Historique de mes lectures</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-amber-900 text-white">
                                    <th class="p-4 italic font-medium">Titre de l'ouvrage</th>
                                    <th class="p-4 font-medium text-sm uppercase">Emprunté le</th>
                                    <th class="p-4 font-medium text-sm uppercase text-center">Retour prévu</th>
                                    <th class="p-4 font-medium text-sm uppercase text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emprunts as $emprunt)
                                    <tr class="border-b border-amber-50 border-dashed hover:bg-amber-50 transition">
                                        <td class="p-4 font-bold text-amber-800">
                                            {{ $emprunt->exemplaire->livre->titre }}
                                        </td>
                                        <td class="p-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($emprunt->date_emprunt)->format('d/m/Y') }}
                                        </td>
                                        <td class="p-4 text-center text-gray-600">
                                            {{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->format('d/m/Y') }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @if($emprunt->date_retour_effectif)
                                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold border border-green-200 uppercase tracking-tighter">
                                                    ✅ RENDU le {{ \Carbon\Carbon::parse($emprunt->date_retour_effectif)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold border border-blue-200 shadow-sm animate-pulse tracking-tighter">
                                                    ⏳ EN COURS
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-gray-400 italic bg-gray-50">
                                            Aucun livre n'a encore été inscrit à votre nom dans nos registres.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center pb-10">
                <a href="{{ route('catalogue') }}" class="text-amber-800 hover:text-amber-600 font-bold underline transition-colors">
                    ← Retourner au catalogue pour emprunter un nouveau livre
                </a>
            </div>
        </div>
    </div>
</x-app-layout>