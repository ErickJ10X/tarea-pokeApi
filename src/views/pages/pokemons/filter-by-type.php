<?php
/**
 * Pokemon Filter by Type View
 *
 * Vista para filtrar y mostrar Pokémon por tipo específico.
 * Proporciona un selector de tipos y muestra todos los Pokémon del tipo seleccionado.
 *
 * Variables esperadas:
 * @var array $types Array de nombres de tipos disponibles
 * @var string|null $selectedType Tipo actualmente seleccionado
 * @var array $pokemons Array de instancias de Pokemon del tipo seleccionado
 */

$title = 'Filtrar por Tipo';
ob_start();
?>

<div class="w-full bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="/pokemons" class="inline-flex items-center text-blue-500 hover:text-blue-700 font-semibold text-sm sm:text-base">
                ← Volver a Pokédex
            </a>
        </div>

        <div class="mb-10">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">🏷️ Filtrar Pokémon por Tipo</h1>
            <p class="text-base text-gray-600 mb-8">Selecciona un tipo para ver todos los Pokémon de esa categoría.</p>

            <!-- Type Selector Form -->
            <form method="GET">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <div class="flex-grow w-full">
                        <label for="type" class="block text-sm font-bold text-gray-800 mb-3">Selecciona un Tipo:</label>
                        <select name="type" id="type"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 text-base font-medium"
                                onchange="this.form.submit()">
                            <option value="">-- Selecciona un tipo --</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>"
                                        <?= $selectedType === $type ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(ucfirst($type)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 text-base shadow-md hover:shadow-lg">
                        🔍 Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Filter Results -->
        <?php if ($selectedType): ?>
            <div class="mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 capitalize">
                    Pokémon de tipo: <span class="text-blue-600"><?= htmlspecialchars($selectedType) ?></span>
                    <span class="text-sm text-gray-600">(<?= count($pokemons) ?> resultados)</span>
                </h2>

                <?php if (empty($pokemons)): ?>
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">
                        <p class="text-sm sm:text-base">No se encontraron Pokémon de este tipo.</p>
                    </div>
                <?php else: ?>
                    <!-- Pokemon Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        <?php foreach ($pokemons as $pokemon): ?>
                            <?php require VIEWS_PATH . '/components/pokemon-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6 mb-8">
                <p class="text-blue-800 text-center text-sm sm:text-base">
                    👆 Selecciona un tipo de Pokémon arriba para ver los resultados.
                </p>
            </div>

            <!-- Type Information -->
            <div class="mt-8">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Tipos disponibles en PokeAPI</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3">
                    <?php
                    $typeColors = [
                        'normal' => '#A8A878',
                        'fire' => '#F08030',
                        'water' => '#6890F0',
                        'electric' => '#F8D030',
                        'grass' => '#78C850',
                        'ice' => '#98D8D8',
                        'fighting' => '#C03028',
                        'poison' => '#A040A0',
                        'ground' => '#E0C068',
                        'flying' => '#A890F0',
                        'psychic' => '#F85888',
                        'bug' => '#A8B820',
                        'rock' => '#B8A038',
                        'ghost' => '#705898',
                        'dragon' => '#7038F8',
                        'dark' => '#705848',
                        'steel' => '#B8B8D0',
                        'fairy' => '#EE99AC'
                    ];
                    ?>
                    <?php foreach ($types as $type): ?>
                        <a href="/pokemon/filter-by-type?type=<?= urlencode($type) ?>"
                           class="p-2 sm:p-3 rounded text-white font-semibold text-center hover:shadow-lg transition-shadow capitalize text-xs sm:text-sm"
                           style="background-color: <?= htmlspecialchars($typeColors[$type] ?? '#999999') ?>;">
                            <?= htmlspecialchars($type) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';
?>

