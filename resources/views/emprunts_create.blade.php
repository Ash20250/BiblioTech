<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl text-[#4A3728]">
            {{ __("✍️ Inscrire un nouveau prêt au registre") }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#FFFDF9] shadow-xl border-2 border-[#D2B48C] rounded-lg p-8">
            <form action="{{ route('emprunt.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block font-serif text-[#4A3728] font-bold mb-2">Choisir l'adhérent (Membre)</label>
                    <select name="usager_id" required class="w-full border-[#D2B48C] bg-white rounded-md shadow-sm focus:ring-[#8B4513] focus:border-[#8B4513] p-2">
                        <option value="">-- Sélectionner un membre --</option>
                        @foreach($usagers as $usager)
                            <option value="{{ $usager->id }}">{{ $usager->name }} ({{ $usager->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block font-serif text-[#4A3728] font-bold mb-2">Choisir l'ouvrage (Exemplaires disponibles)</label>
                    <select name="exemplaire_id" required class="w-full border-[#D2B48C] bg-white rounded-md shadow-sm focus:ring-[#8B4513] focus:border-[#8B4513] p-2">
                        <option value="">-- Sélectionner un exemplaire --</option>
                        @foreach($exemplaires as $ex)
                            <option value="{{ $ex->id }}">
                                ID:{{ $ex->id }} - {{ $ex->livre->titre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between mt-8 border-t border-[#F4F1EA] pt-6">
                    <a href="{{ route('emprunts.index') }}" class="text-[#8B4513] hover:underline font-serif">
                        Annuler et revenir au registre
                    </a>
                    <button type="submit" class="bg-[#4A3728] text-[#F4F1EA] px-6 py-3 rounded shadow-lg font-bold uppercase tracking-widest hover:bg-[#5D4037] transition transform active:scale-95 border-none cursor-pointer">
                        Confirmer le prêt
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>