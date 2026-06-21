<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __('📜 Consigner un Nouveau Prêt d\'Ouvrage') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-3xl mx-auto">
            
            {{-- Bouton Retour --}}
            <div class="mb-6">
                <a href="{{ route('emprunts.index') }}" class="inline-flex items-center text-sm font-serif text-[#8B4513] hover:text-[#5D4037] transition-colors">
                    ← Retour au registre des mouvements
                </a>
            </div>

            {{-- Affichage des erreurs globales de validation --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6 font-serif">
                    <p class="font-bold">⚠️ Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Carte du Formulaire --}}
            <div class="bg-[#FFFDF9] shadow-2xl border-2 border-[#D2B48C] rounded-lg overflow-hidden p-8">
                
                <form action="{{ route('emprunts.store') }}" method="POST" class="space-y-6 font-serif text-[#5D4037]">
                    @csrf

                    {{-- 1. Sélection de l'Emprunteur (Usager) sans l'adresse email --}}
                    <div>
                        <label for="usager_id" class="block text-sm font-bold uppercase tracking-wider mb-2">
                            👤 Sélectionner l'Emprunteur (Client)
                        </label>
                        <select name="usager_id" id="usager_id" required class="w-full bg-[#FCF9F2] border border-[#D2B48C] rounded-md px-4 py-2.5 focus:border-[#4A3728] focus:ring-[#4A3728] transition shadow-sm">
                            <option value="">-- Choisir un usager --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('usager_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('usager_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. Sélection de l'Exemplaire Disponible sans le Code N/A --}}
                    <div>
                        <label for="exemplaire_id" class="block text-sm font-bold uppercase tracking-wider mb-2">
                            📖 Sélectionner l'Exemplaire Disponible
                        </label>
                        <select name="exemplaire_id" id="exemplaire_id" required class="w-full bg-[#FCF9F2] border border-[#D2B48C] rounded-md px-4 py-2.5 focus:border-[#4A3728] focus:ring-[#4A3728] transition shadow-sm">
                            <option value="">-- Choisir un exemplaire de livre libre --</option>
                            @foreach($exemplaires as $exemplaire)
                                <option value="{{ $exemplaire->id }}" {{ old('exemplaire_id') == $exemplaire->id ? 'selected' : '' }}>
                                    N° {{ $exemplaire->id }} - "{{ $exemplaire->livre->titre ?? 'Livre inconnu' }}"
                                </option>
                            @endforeach
                        </select>
                        @error('exemplaire_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Dates d'emprunt et de retour --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Date d'emprunt (Par défaut aujourd'hui) --}}
                        <div>
                            <label for="date_emprunt" class="block text-sm font-bold uppercase tracking-wider mb-2">
                                📅 Date de Sortie
                            </label>
                            <input type="date" name="date_emprunt" id="date_emprunt" 
                                   value="{{ old('date_emprunt', date('Y-m-d')) }}" 
                                   required
                                   class="w-full bg-[#FCF9F2] border border-[#D2B48C] rounded-md px-4 py-2 focus:border-[#4A3728] focus:ring-[#4A3728] transition shadow-sm">
                            @error('date_emprunt')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date de retour prévue (Par défaut aujourd'hui + 30 jours) --}}
                        <div>
                            <label for="date_retour_prevue" class="block text-sm font-bold uppercase tracking-wider mb-2">
                                ⏳ Date de Retour Prévue
                            </label>
                            <input type="date" name="date_retour_prevue" id="date_retour_prevue" 
                                   value="{{ old('date_retour_prevue', date('Y-m-d', strtotime('+30 days'))) }}" 
                                   required
                                   class="w-full bg-[#FCF9F2] border border-[#D2B48C] rounded-md px-4 py-2 focus:border-[#4A3728] focus:ring-[#4A3728] transition shadow-sm">
                            @error('date_retour_prevue')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Bouton de soumission --}}
                    <div class="pt-4 text-right">
                        <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-6 py-3 rounded-md font-bold uppercase tracking-widest text-sm hover:bg-[#5D4037] transition shadow-md border-none">
                            ✍️ Valider et Enregistrer le Prêt
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>