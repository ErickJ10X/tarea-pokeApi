<?php
/**
 * Pokemon Search View
 *
 * Vista para buscar un Pokémon específico por nombre.
 * Proporciona un formulario de búsqueda y muestra el resultado si se encontró.
 *
 * Variables esperadas:
 * @var \App\Models\Entity\Pokemon|null $pokemon Instancia del Pokémon encontrado o null
 * @var string $query Término de búsqueda utilizado
 */

$title = 'Buscar Pokémon';
ob_start();
?>

<div class="w-full bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="/pokemons" class="inline-flex items-center text-blue-500 hover:text-blue-700 font-semibold text-sm sm:text-base">
                ← Volver a Pokédex
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8">🔍 Buscar Pokémon</h1>

            <!-- Search Form -->
            <form method="GET" class="mb-10">
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <input type="hidden" name="q" value="">
                    <div class="flex-grow">
                        <input type="text"
                               name="q"
                               placeholder="Escribe el nombre del Pokémon..."
                               value="<?= htmlspecialchars($query) ?>"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500 text-base"
                               autofocus>
                    </div>
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 text-base shadow-md hover:shadow-lg whitespace-nowrap">
                        🔎 Buscar
                    </button>
                </div>
            </form>

            <!-- Search Results -->
            <?php if (!empty($query)): ?>
                <?php if ($pokemon): ?>
                    <div class="mb-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Resultado de la búsqueda</h2>

                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <!-- Result Header -->
                            <div class="p-4 sm:p-6" style="background-color: <?= $pokemon->getTypeColor() ?>;">
                                <div class="flex flex-col sm:flex-row items-center gap-4">
                                    <div class="text-white text-center sm:text-left flex-1">
                                        <h3 class="text-2xl sm:text-3xl font-bold mb-1 capitalize break-words">
                                            <?= htmlspecialchars($pokemon->name) ?>
                                        </h3>
                                        <p class="text-base sm:text-lg opacity-90">
                                            #<?= str_pad($pokemon->id, 3, '0', STR_PAD_LEFT) ?>
                                        </p>
                                    </div>

                                    <?php if ($pokemon->imageUrl): ?>
                                        <div class="flex-shrink-0">
                                            <img src="<?= htmlspecialchars($pokemon->imageUrl) ?>"
                                                 alt="<?= htmlspecialchars($pokemon->name) ?>"
                                                 class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 object-contain drop-shadow-lg">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Result Details -->
                            <div class="p-4 sm:p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-600 font-semibold mb-2">Tipos</p>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($pokemon->types as $type): ?>
                                                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs sm:text-sm font-semibold text-gray-700 capitalize">
                                                    <?= htmlspecialchars($type) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-600 font-semibold mb-2">Medidas</p>
                                        <p class="text-sm sm:text-base text-gray-800">
                                            <span class="font-semibold">Altura:</span> <?= number_format($pokemon->getHeightInMeters(), 2) ?> m<br>
                                            <span class="font-semibold">Peso:</span> <?= number_format($pokemon->getWeightInKilograms(), 2) ?> kg
                                        </p>
                                    </div>
                                </div>

                                <a href="/pokemon?id=<?= $pokemon->id ?>"
                                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 sm:px-6 rounded transition-colors duration-200 text-sm sm:text-base">
                                    Ver Detalles Completos
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <p class="font-semibold text-sm sm:text-base">No se encontró ningún Pokémon con el nombre: <strong><?= htmlspecialchars($query) ?></strong></p>
                        <p class="text-xs sm:text-sm mt-2">Por favor, verifica la ortografía e intenta de nuevo.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center text-gray-500">
                    <p class="mb-4 text-sm sm:text-base">👇 Ingresa el nombre de un Pokémon para buscar</p>
                    <p class="text-xs sm:text-sm">Por ejemplo: Pikachu, Charizard, Blastoise, etc.</p>
                </div>
            <?php endif; ?>

            <!-- Suggestions -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs sm:text-sm text-blue-800 font-semibold mb-3">💡 Sugerencias populares:</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                    <?php $populares = ['pikachu', 'charizard', 'blastoise', 'venusaur', 'dragonite', 'mewtwo']; ?>
                    <?php foreach ($populares as $nombre): ?>
                        <a href="/pokemon/search?q=<?= urlencode($nombre) ?>"
                           class="text-blue-600 hover:text-blue-800 hover:underline text-xs sm:text-sm capitalize">
                            <?= htmlspecialchars($nombre) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';
?>

