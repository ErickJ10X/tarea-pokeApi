<?php
$title = 'Inicio';
ob_start();
?>
<div class="max-w-7xl mx-auto">
    <!-- Encabezado principal -->
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Bienvenido a Docker MVC Template</h1>
        <p class="text-xl text-gray-600">
            Aplicación web demostrativa que integra gestión de datos con una API REST pública (PokeAPI)
        </p>
    </div>



    <!-- API Information -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">API Integrada</h2>

        <div class="space-y-3 text-gray-700">
            <p>
                <strong>Nombre:</strong> PokeAPI<br>
                <strong>URL:</strong> <a href="https://pokeapi.co/" target="_blank" class="text-blue-600 hover:text-blue-800 underline">https://pokeapi.co/</a><br>
                <strong>Documentación:</strong> <a href="https://pokeapi.co/docs/v2" target="_blank" class="text-blue-600 hover:text-blue-800 underline">API Documentation</a>
            </p>

            <p class="mt-4">
                PokeAPI es una API REST gratuita que proporciona acceso a una base de datos completa de información sobre Pokémon,
                incluyendo detalles sobre especies, tipos, estadísticas, habilidades, evoluciones y mucho más.
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__.'/../layouts/base.php';

