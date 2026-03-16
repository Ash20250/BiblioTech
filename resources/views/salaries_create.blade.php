<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728] leading-tight">
            {{ __("✍️ Inscription au Registre des Salariés") }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-3xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('catalogue') }}" class="text-[#8B4513] hover:text-[#5D4037] font-serif italic flex items-center gap-2 transition-colors text-sm">
                    <span>«</span> Retour au catalogue
                </a>
            </div>

            <div class="bg-[#FFFDF9] shadow-2xl border-2 border-[#D2B48C] rounded-lg overflow-hidden">
                <div class="bg-[#4A3728] p-4 text-center">
                    <p class="text-[#F4F1EA] font-serif uppercase tracking-widest text-xs">Nouvelle Fiche Individuelle</p>
                </div>

                <form action="{{ route('salaries.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block font-serif text-[#4A3728] font-bold">Nom Complet</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required 
                                class="w-full border-2 border-[#D2B48C] rounded-md p-3 bg-white focus:border-[#8B4513] focus:ring-0 outline-none transition-all">
                            @error('nom') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block font-serif text-[#4A3728] font-bold">Poste Occupé</label>
                            <input type="text" name="poste" value="{{ old('poste') }}" required 
                                class="w-full border-2 border-[#D2B48C] rounded-md p-3 bg-white focus:border-[#8B4513] focus:ring-0 outline-none transition-all">
                            @error('poste') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block font-serif text-[#4A3728] font-bold">Email Pro</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full border-2 border-[#D2B48C] rounded-md p-3 bg-white focus:border-[#8B4513] focus:ring-0 outline-none transition-all">
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block font-serif text-[#4A3728] font-bold">Ville / Campus</label>
                        <input type="text" name="ville" value="{{ old('ville') }}" required 
                            class="w-full border-2 border-[#D2B48C] rounded-md p-3 bg-white focus:border-[#8B4513] focus:ring-0 outline-none transition-all">
                        @error('ville') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-[#F4F1EA] rounded-md border border-[#D2B48C]">
                        <input type="checkbox" name="remote" id="remote" value="1" {{ old('remote') ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-[#D2B48C] text-[#8B4513] focus:ring-[#8B4513]">
                        <label for="remote" class="font-serif text-[#4A3728] cursor-pointer">Autoriser le télétravail</label>
                    </div>

                    <div class="pt-6 border-t border-[#D2B48C] flex justify-end">
                        <button type="submit" class="bg-[#8B4513] text-[#F4F1EA] px-10 py-3 rounded-md font-bold hover:bg-[#5D4037] transition-all shadow-lg uppercase tracking-widest text-sm">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>