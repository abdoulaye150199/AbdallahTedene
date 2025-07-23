<?php
    namespace App\Core;

    class Error_404
    {
        public static function render()
        {
            http_response_code(404);
            echo <<<HTML
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Page non trouvée</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-gray-900 text-white flex items-center justify-center min-h-screen px-4">
                <div class="text-center max-w-xl bg-gray-800 p-10 rounded-2xl shadow-2xl">
                    <h1 class="text-[120px] font-extrabold text-green-500 leading-none drop-shadow-lg">404</h1>
                    <p class="text-2xl md:text-3xl font-semibold mt-4 text-white">Oups ! Page introuvable</p>
                    <p class="text-gray-400 mt-2">La page que vous recherchez a peut-être été déplacée ou supprimée.</p>
                    
                    <div class="mt-6">
                        <a href="/" class="inline-block px-6 py-3 bg-green-400 text-gray-900 font-bold rounded-lg hover:bg-green-300 transition-all duration-300 shadow-lg hover:scale-105">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </body>
            </html>
            HTML;
            exit;
        }
    }
