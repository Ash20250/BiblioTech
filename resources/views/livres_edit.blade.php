<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📝 Modification d'Ouvrage") }}
        </h2>
    </x-slot>

    <div class="flex justify-center py-10">
        <div class="bg-[#FFFDF9] p-10 rounded-lg shadow-xl border-2 border-[#D2B48C] w-full max-w-xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif font-bold text-[#4A3728]">📜 Rectifier l'Archive</h1>
                <p class="text-[#795548] italic">Vous modifiez les informations du livre : <strong>{{ $livre->titre }}</strong></p>
            </div>

            <form action="{{ route('livres.update', $livre->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH') 

                <div class="form-group">
                    <label for="titre" class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Titre de l'œuvre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $livre->titre) }}" required
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none">
                </div>

                <div class="form-group">
                    <label for="auteur_id" class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Auteur</label>
                    <select name="auteur_id" id="auteur_id" required 
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none">
                        @foreach($auteurs as $auteur)
                            <option value="{{ $auteur->id }}" {{ $livre->auteur_id == $auteur->id ? 'selected' : '' }}>
                                {{ $auteur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="categorie_id" class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" required 
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none">
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ $livre->categorie_id == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" 
                        class="flex-1 py-4 bg-[#8B4513] hover:bg-[#5D4037] text-[#F4F1EA] font-bold rounded-md shadow-lg uppercase tracking-widest transition-all">
                        Mettre à jour
                    </button>
                    
                    <a href="{{ route('catalogue') }}" 
                        class="py-4 px-6 bg-[#E5E7EB] hover:bg-gray-300 text-gray-700 font-bold rounded-md uppercase tracking-widest text-center transition-all">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>