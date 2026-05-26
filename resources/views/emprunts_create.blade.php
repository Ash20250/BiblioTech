<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728]">
            {{ __("✍️ Inscrire un nouveau prêt au registre") }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-serif shadow-sm">
                <p class="font-bold">⚠️ Attention :</p>
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#FFFDF9] shadow-xl border-2 border-[#D2B48C] rounded-lg p-8">
            <form action="{{ route('emprunt.store') }}" method="POST">
                @csrf

                <div class="mb-8">
                    <label class="block font-serif text-[#4A3728] font-bold mb-2">👤 Choisir l'adhérent (Membre)</label>
                    <select id="select-usager" name="usager_id" required class="w-full">
                        <option value="">Chercher par nom ou email...</option>
                        @foreach($usagers as $usager)
                            <option value="{{ $usager->id }}">{{ $usager->name }} ({{ $usager->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block font-serif text-[#4A3728] font-bold mb-2">📚 Choisir l'ouvrage (Exemplaires disponibles)</label>
                    <select id="select-exemplaire" name="exemplaire_id" required class="w-full">
                        <option value="">Chercher par titre, thème ou ID...</option>
                        @foreach($exemplaires as $ex)
                            <option value="{{ $ex->id }}">
                                [ID:{{ $ex->id }}] {{ $ex->livre->titre }} — {{ $ex->livre->theme ?? 'Général' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-4 bg-[#FDFBF7] rounded border border-[#F4F1EA]">
                    <div>
                        <label class="block font-serif text-[#4A3728] text-sm font-bold mb-2">📅 Date de sortie</label>
                        <input type="date" name="date_emprunt" id="date_emprunt" 
                               value="{{ date('Y-m-d') }}"
                               class="w-full border-[#D2B48C] rounded shadow-sm focus:ring-[#8B4513] focus:border-[#8B4513]">
                    </div>
                    <div>
                        <label class="block font-serif text-[#4A3728] text-sm font-bold mb-2">⏳ Retour prévu (+30 jours)</label>
                        <input type="date" name="date_retour_prevue" id="date_retour_prevue" 
                               value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                               class="w-full border-[#D2B48C] rounded shadow-sm focus:ring-[#8B4513] focus:border-[#8B4513]">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8 border-t border-[#F4F1EA] pt-6">
                    <a href="{{ route('emprunts.index') }}" class="text-[#8B4513] hover:underline font-serif text-sm">
                        ↩️ Annuler et revenir au registre
                    </a>
                    <button type="submit" class="bg-[#4A3728] text-[#F4F1EA] px-8 py-3 rounded shadow-lg font-bold uppercase tracking-widest hover:bg-[#5D4037] transition transform active:scale-95">
                        Confirmer le prêt
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        // 1. Initialisation des menus de recherche
        new TomSelect("#select-usager", { create: false, sortField: { field: "text" } });
        new TomSelect("#select-exemplaire", { create: false, sortField: { field: "text" } });

        // 2. Logique de date intelligente : quand on change la date d'emprunt, on ajuste le retour prévu
        document.getElementById('date_emprunt').addEventListener('change', function() {
            let dateSortie = new Date(this.value);
            dateSortie.setDate(dateSortie.getDate() + 30);
            document.getElementById('date_retour_prevue').value = dateSortie.toISOString().split('T')[0];
        });
    </script>
</x-app-layout>