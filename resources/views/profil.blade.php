<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12" style="background-color: #f4ece1; min-height: 100vh; font-family: 'Georgia', serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-amber-900 italic">📜 Mon Espace Adhérent</h2>
                
                @if($nbLivresEnCours > 0)
                    <div class="bg-amber-800 text-white px-4 py-2 rounded-full shadow-md border border-amber-600">
                        {{ $nbLivresEnCours }} livre(s) en votre possession
                    </div>
                @endif
            </div>

            {{-- Infos Adhérent --}}
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

            {{-- Mes Coups de Cœur --}}
            <div class="mb-8 bg-white border-l-4 border-red-600 rounded-lg shadow-lg p-6 border border-amber-200">
                <h3 class="text-xl font-bold text-amber-900 mb-6 flex items-center gap-2">
                    <span class="text-red-600">❤️</span> Mes Coups de Cœur
                </h3>

                @if($favoris->isEmpty())
                    <p class="italic text-gray-500 text-sm">Votre liste de favoris est vide.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($favoris as $livre)
                            <div class="relative bg-[#FFFDF9] p-4 border border-amber-200 rounded-md shadow-sm hover:shadow-md transition group">
                                <div class="flex justify-between items-start">
                                    <div class="pr-8">
                                        <h4 class="font-bold text-amber-900 leading-tight">{{ $livre->titre }}</h4>
                                        <p class="text-xs italic text-amber-700 mt-1">{{ $livre->auteur->nom ?? 'Auteur inconnu' }}</p>
                                    </div>
                                    <form action="{{ route('favoris.toggle', $livre->id) }}" method="POST" class="absolute top-4 right-4">
                                        @csrf
                                        <button type="submit" class="text-xl hover:scale-125 transition">❤️</button>
                                    </form>
                                </div>
                                <div class="mt-4 pt-3 border-t border-amber-100 flex justify-between items-center">
                                    <span class="text-[10px] uppercase font-bold text-amber-600 px-2 py-0.5 bg-amber-50 rounded">
                                        {{ $livre->categorie->nom ?? 'Général' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- SECTION RÉSERVATIONS --}}
            <div class="mb-8 bg-white border-l-4 border-orange-500 rounded-lg shadow-lg p-6 border border-amber-200">
                <h3 class="text-xl font-bold text-amber-900 mb-6 flex items-center gap-2">
                    <span class="text-orange-500">🔖</span> Mes Réservations en cours
                </h3>

                @if($reservations->isEmpty())
                    <p class="italic text-gray-500 text-sm">Vous n'avez aucune réservation en attente.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($reservations as $exemplaire)
                            <div class="flex justify-between items-center p-4 bg-orange-50 border border-orange-200 rounded-lg shadow-sm">
                                <div>
                                    <h4 class="font-bold text-amber-900">{{ $exemplaire->livre->titre }}</h4>
                                    <p class="text-[10px] text-orange-700 font-bold uppercase">Exemplaire : {{ $exemplaire->code_barre }}</p>
                                </div>
                                <form action="{{ route('reservation.annuler', $exemplaire->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-red-600 hover:text-red-800 font-bold underline uppercase">
                                        Annuler la réservation
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Historique / Emprunts --}}
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
                                        <td class="p-4 font-bold text-amber-800">{{ $emprunt->exemplaire->livre->titre }}</td>
                                        <td class="p-4 text-gray-600">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                        <td class="p-4 text-center text-gray-600">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                                        <td class="p-4 text-center">
                                            @if($emprunt->date_retour)
                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold border border-green-200">✅ RENDU</span>
                                            @else
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold border border-blue-200 shadow-sm">⏳ EN COURS</span>
                                                    
                                                    @if($emprunt->exemplaire->reserved_by_user_id)
                                                        <span class="bg-orange-500 text-white text-[9px] px-2 py-0.5 rounded font-bold">⚠️ ATTENDU</span>
                                                    @endif
                                                    
                                                    <form action="{{ route('emprunts.retourner', $emprunt->id) }}" method="POST" onsubmit="return confirm('Confirmer le retour de ce livre ?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="mt-2 text-[9px] bg-amber-700 hover:bg-amber-900 text-white px-2 py-1 rounded transition uppercase font-bold shadow">
                                                            Rendre le livre
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-gray-400 italic bg-gray-50">Aucun livre enregistré.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>