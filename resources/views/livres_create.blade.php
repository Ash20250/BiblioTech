<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("📚 Nouveau Livre") }}
        </h2>
    </x-slot>

    <div class="flex justify-center py-10">
        <div class="bg-[#FFFDF9] p-10 rounded-lg shadow-xl border-2 border-[#D2B48C] w-full max-w-xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif font-bold text-[#4A3728]">📖 Ajouter un Ouvrage</h1>
                <p class="text-[#795548] italic">Enregistrez un nouveau titre dans les archives de la bibliothèque.</p>
            </div>

            <form action="{{ route('livres.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="form-group">
                    <label for="titre" class="block text-sm font-bold text-[#4A3728] uppercase tracking-wide mb-2">Titre de l'œuvre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none transition-all">
                </div>

                <div class="form-group">
                    <label for="auteur" class="block text-sm font-bold text-[#4A3728] uppercase tracking-wide mb-2">Auteur</label>
                    <input type="text" name="auteur" id="auteur" value="{{ old('auteur') }}" required
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none transition-all">
                </div>

                <div class="form-group">
                    <label for="theme" class="block text-sm font-bold text-[#4A3728] uppercase tracking-wide mb-2">Thème (ex: Informatique, Roman...)</label>
                    <input type="text" name="theme" id="theme" value="{{ old('theme') }}"
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none transition-all">
                </div>

                <div class="form-group">
                    <label for="isbn" class="block text-sm font-bold text-[#4A3728] uppercase tracking-wide mb-2">Code ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md bg-white text-[#5D4037] focus:border-[#8B4513] outline-none transition-all">
                </div>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" 
                        class="w-full py-4 bg-[#8B4513] hover:bg-[#5D4037] text-[#F4F1EA] font-bold rounded-md shadow-lg transform active:scale-95 transition-all uppercase tracking-widest">
                        Inscrire à l'inventaire
                    </button>

                    <a href="{{ route('catalogue') }}" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-center font-bold rounded-md transition-all uppercase text-sm tracking-widest">
                        Annuler
                    </a>
                </div>
            </form>
            </div>
    </div>
</x-app-layout>