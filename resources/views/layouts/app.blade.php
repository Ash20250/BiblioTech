<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTech - Gestion</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;700&display=swap');
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F1EA]">

    <nav class="bg-[#4A3728] text-[#F4F1EA] shadow-xl py-4 border-b-4 border-[#8B4513]">
        <div class="w-full px-4 flex justify-between items-center">
            
            <div class="flex items-center gap-8">
                @auth
                    <a href="{{ route('profil.usager') }}" class="hover:text-[#D2B48C] transition text-sm no-underline font-serif font-bold">
                        📜 Mon Profil
                    </a>
                @endauth

                <div class="font-serif text-2xl font-bold tracking-tighter">
                    <a href="/" class="no-underline text-inherit">📜 BiblioTech</a>
                </div>
            </div>

            <div class="flex items-center gap-6 font-serif">
                <a href="/" class="hover:text-[#D2B48C] transition text-sm no-underline">Accueil</a>
                <a href="/catalogue" class="hover:text-[#D2B48C] transition text-sm no-underline">Catalogue</a>
                
                @auth
                    <a href="/emprunts" class="hover:text-[#D2B48C] transition text-sm no-underline">Registre</a>

                    <div class="flex items-center gap-3">
                        <a href="/emprunts/nouveau" class="bg-[#8B4513] px-4 py-2 rounded shadow-inner hover:bg-[#5D4037] transition text-sm no-underline text-white">
                            Nouvel Emprunt
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                            @csrf
                            <button type="submit" class="text-[10px] uppercase tracking-widest opacity-60 hover:opacity-100 hover:text-red-400 transition bg-transparent border-none cursor-pointer p-0 font-sans">
                                Quitter
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <footer class="py-6 text-center text-[#8B4513] text-sm font-serif italic">
        — Registre Officiel BiblioTech 2026 —
    </footer>
</body>
</html>