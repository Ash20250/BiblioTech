<x-guest-layout>
    <div class="text-center mb-8">
        <span class="text-4xl">📜</span>
        <h1 class="font-serif text-2xl text-[#4A3728] uppercase tracking-widest mt-2">BiblioTech</h1>
        <div class="h-px bg-[#D2B48C] w-1/2 mx-auto mt-2"></div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label class="block font-serif text-[#4A3728] text-sm font-bold">Email</label>
            <input type="email" name="email" class="block mt-1 w-full border-[#D2B48C] rounded-md shadow-sm focus:ring-[#8B4513]" required autofocus>
        </div>

        <div class="mt-4">
            <label class="block font-serif text-[#4A3728] text-sm font-bold">Mot de passe</label>
            <input type="password" name="password" class="block mt-1 w-full border-[#D2B48C] rounded-md shadow-sm focus:ring-[#8B4513]" required>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-[#D2B48C] text-[#8B4513]">
                <span class="ml-2 text-sm text-[#795548] italic">Se souvenir</span>
            </label>
            <button type="submit" class="bg-[#4A3728] text-white px-6 py-2 rounded font-bold hover:bg-[#8B4513] transition">
                ENTRER
            </button>
        </div>
    </form>
</x-guest-layout>