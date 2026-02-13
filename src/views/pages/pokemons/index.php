<?php
/**
 * Pokemons Index View
 *
 * Vista para mostrar una lista paginada de Pokémon.
 * Permite navegar entre páginas de Pokémon obtenidos de la API PokeAPI.
 *
 * Variables esperadas:
 * @var array $pokemons Array de instancias de Pokemon
 * @var int $total Número total de Pokémon disponibles
 * @var int $page Número de página actual
 * @var int $limit Número de Pokémon por página
 */

$title = 'Pokédex';
ob_start();
$totalPages = ceil($total / $limit);
?>

<div class="w-full bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Pokédex</h1>
            <p class="text-base sm:text-lg text-gray-600 mb-8">Explora la base de datos de Pokémon obtenida de PokeAPI.</p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 w-full">
                <a href="/pokemon/search"
                   class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 text-center text-base shadow-md hover:shadow-lg">
                    🔍 Buscar Pokémon
                </a>
                <a href="/pokemon/filter-by-type"
                   class="flex-1 bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 text-center text-base shadow-md hover:shadow-lg">
                    🏷️ Filtrar por Tipo
                </a>
            </div>
        </div>

        <?php if (empty($pokemons)): ?>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">
                <p>No se pudieron cargar los Pokémon. Por favor, intenta más tarde.</p>
            </div>
        <?php else: ?>
            <!-- Grid de Pokémon -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <?php foreach ($pokemons as $pokemon): ?>
                    <?php require VIEWS_PATH . '/components/pokemon-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Controls -->
            <nav class="flex justify-center items-center mt-12 mb-8">
                <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                    <div class="flex items-center justify-center gap-2">
                        <?php if ($page > 1): ?>
                            <a href="/pokemons?page=1"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                               title="Primera página">
                                ⏮ Primera
                            </a>
                            <a href="/pokemons?page=<?= $page - 1 ?>"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                               title="Página anterior">
                                ◀ Anterior
                            </a>
                        <?php else: ?>
                            <span class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                ⏮ Primera
                            </span>
                            <span class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                ◀ Anterior
                            </span>
                        <?php endif; ?>

                        <div class="bg-gray-100 rounded-lg px-6 py-2 mx-2 border border-gray-300">
                            <span class="font-bold text-gray-800 text-center">
                                Página <span class="text-blue-600 text-lg"><?= $page ?></span> de <span class="text-blue-600 text-lg"><?= $totalPages ?></span>
                            </span>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="/pokemons?page=<?= $page + 1 ?>"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                               title="Página siguiente">
                                Siguiente ▶
                            </a>
                            <a href="/pokemons?page=<?= $totalPages ?>"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                               title="Última página">
                                Última ⏭
                            </a>
                        <?php else: ?>
                            <span class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                Siguiente ▶
                            </span>
                            <span class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                Última ⏭
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <p class="text-center text-gray-600 text-base font-semibold mt-6">
                📊 Total de Pokémon en la base de datos: <span class="text-blue-600 text-lg"><?= number_format($total) ?></span>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';
?>

