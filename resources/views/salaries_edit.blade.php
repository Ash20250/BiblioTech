<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728]">
            {{ __("Modifier la fiche salarié") }}
        </h2>
    </x-slot>

    <div class="flex justify-center py-10">
        <div class="bg-[#FFFDF9] p-10 rounded-lg shadow-xl border-2 border-[#D2B48C] w-full max-w-2xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif font-bold text-[#4A3728]">📜 Mise à jour du Registre</h1>
                <p class="text-[#795548] italic">Modification des informations de : <strong>{{ $salarie->nom }}</strong></p>
            </div>

            <form action="{{ route('salaries.update', $salarie->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Nom Complet</label>
                        <input type="text" name="nom" value="{{ old('nom', $salarie->nom) }}" required
                            class="w-full p-3 border-2 border-[#D2B48C] rounded-md focus:border-[#8B4513] outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Poste / Fonction</label>
                        <input type="text" name="poste" value="{{ old('poste', $salarie->poste) }}" required
                            class="w-full p-3 border-2 border-[#D2B48C] rounded-md focus:border-[#8B4513] outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#4A3728] uppercase mb-2">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email', $salarie->email) }}" required
                        class="w-full p-3 border-2 border-[#D2B48C] rounded-md focus:border-[#8B4513] outline-none">
                </div>

                <div class="bg-[#F4F1EA] p-4 rounded-md border border-[#D2B48C]">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="remote" value="0">
                        <input type="checkbox" name="remote" value="1" {{ $salarie->remote ? 'checked' : '' }}
                            class="w-5 h-5 text-[#8B4513] border-[#D2B48C] rounded focus:ring-[#8B4513]">
                        <span class="text-[#4A3728] font-bold uppercase text-sm">Autoriser le télétravail</span>
                    </label>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" 
                        class="flex-1 py-4 bg-[#8B4513] hover:bg-[#5D4037] text-white font-bold rounded-md shadow-lg uppercase tracking-widest transition-all">
                        Enregistrer les modifications
                    </button>
                    
                    <a href="{{ route('catalogue') }}" 
                        class="py-4 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-md uppercase tracking-widest transition-all text-center">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>