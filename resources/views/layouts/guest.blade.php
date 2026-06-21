<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BiblioTech - Connexion</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Sécurité si Tailwind ne charge pas les couleurs personnalisées */
            body { 
                background-color: #F4F1EA !important; 
            }
            .login-card {
                background-color: #FFFDF9 !important;
                border: 2px solid #D2B48C !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 login-card rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-4 text-[#795548] italic font-serif text-sm">
                © 2026 BiblioTech - Le savoir à portée de main
            </p>
        </div>
    </body>
</html>