<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#F4F1EA] p-6">
        
        <div class="w-full max-w-md bg-[#FFFDF9] rounded-2xl shadow-2xl border border-[#D2B48C]/30 overflow-hidden">
            
            <div class="bg-[#4A3728] p-8 text-center">
                <h1 class="text-3xl font-serif text-[#F4F1EA] tracking-wide">BiblioTech</h1>
                <p class="text-[#D2B48C] text-xs uppercase tracking-widest mt-2">Administration Système</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-[#4A3728] uppercase tracking-[0.2em] mb-2">Identifiant</label>
                    <input type="email" name="email" required autofocus 
                           class="w-full px-4 py-3 bg-[#FDFBF7] border border-[#D2B48C] rounded-lg focus:ring-2 focus:ring-[#8B4513] outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-[#4A3728] uppercase tracking-[0.2em] mb-2">Mot de passe</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 bg-[#FDFBF7] border border-[#D2B48C] rounded-lg focus:ring-2 focus:ring-[#8B4513] outline-none transition-all">
                </div>

                <button type="submit" 
                        class="w-full bg-[#8B4513] text-white py-3 rounded-lg font-bold tracking-widest uppercase hover:bg-[#5D4037] transition-all shadow-md active:translate-y-[2px]">
                    Connexion
                </button>
            </form>

            <div class="px-8 pb-8 text-center">
                <p class="text-[10px] text-[#795548] uppercase tracking-widest">© 2026 BiblioTech</p>
            </div>
        </div>
    </div>
</x-guest-layout>