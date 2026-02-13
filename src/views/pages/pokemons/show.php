<?php
/**
 * Pokemon Show View
 *
 * Vista para mostrar los detalles completos de un Pokémon específico.
 * Muestra información detallada incluyendo tipos, estadísticas, habilidades,
 * peso, altura e imagen.
 *
 * Variables esperadas:
 * @var \App\Models\Entity\Pokemon $pokemon Instancia del Pokémon a mostrar
 */

$title = ucfirst($pokemon->name);
ob_start();
?>

<div class="w-full bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="/pokemons" class="inline-flex items-center text-blue-500 hover:text-blue-700 font-semibold text-sm sm:text-base">
                ← Volver a Pokédex
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header with image -->
            <div class="p-4 sm:p-6 lg:p-8" style="background-color: <?= $pokemon->getTypeColor() ?>;">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Title and ID -->
                    <div class="text-white text-center sm:text-left flex-1">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-2 capitalize break-words">
                            <?= htmlspecialchars($pokemon->name) ?>
                        </h1>
                        <p class="text-lg sm:text-xl opacity-90">
                            #<?= str_pad($pokemon->id, 3, '0', STR_PAD_LEFT) ?>
                        </p>
                    </div>

                    <!-- Image -->
                    <?php if ($pokemon->imageUrl): ?>
                        <div class="flex-shrink-0">
                            <img src="<?= htmlspecialchars($pokemon->imageUrl) ?>"
                                 alt="<?= htmlspecialchars($pokemon->name) ?>"
                                 class="w-32 h-32 sm:w-48 sm:h-48 lg:w-64 lg:h-64 object-contain drop-shadow-lg">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-4 sm:p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Basic Info Section -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Información Básica</h2>

                    <div class="space-y-4">
                        <!-- Type -->
                        <div class="border-b pb-3">
                            <p class="text-xs sm:text-sm text-gray-600 font-semibold mb-2">Tipo/Tipos</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($pokemon->types as $type): ?>
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xs sm:text-sm font-semibold text-gray-700 capitalize">
                                        <?= htmlspecialchars($type) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Height -->
                        <div class="border-b pb-3">
                            <p class="text-xs sm:text-sm text-gray-600 font-semibold">Altura</p>
                            <p class="text-base sm:text-lg text-gray-800">
                                <?= number_format($pokemon->getHeightInMeters(), 2) ?> m
                            </p>
                        </div>

                        <!-- Weight -->
                        <div class="border-b pb-3">
                            <p class="text-xs sm:text-sm text-gray-600 font-semibold">Peso</p>
                            <p class="text-base sm:text-lg text-gray-800">
                                <?= number_format($pokemon->getWeightInKilograms(), 2) ?> kg
                            </p>
                        </div>

                        <!-- Abilities -->
                        <?php if (!empty($pokemon->abilities)): ?>
                            <div class="border-b pb-3">
                                <p class="text-xs sm:text-sm text-gray-600 font-semibold mb-2">Habilidades</p>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($pokemon->abilities as $ability): ?>
                                        <span class="inline-block bg-blue-100 rounded px-2 py-1 text-xs sm:text-sm text-blue-800 capitalize">
                                            <?= htmlspecialchars($ability) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stats Section -->
                <?php if (!empty($pokemon->stats)): ?>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Estadísticas Base</h2>

                        <div class="space-y-3 sm:space-y-4">
                            <?php foreach ($pokemon->stats as $statName => $statValue): ?>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-700 capitalize">
                                            <?= htmlspecialchars(str_replace('-', ' ', $statName)) ?>
                                        </p>
                                        <p class="text-xs sm:text-sm font-bold text-gray-900">
                                            <?= $statValue ?>
                                        </p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <?php $percentage = min(($statValue / 150) * 100, 100); ?>
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation Footer -->
            <div class="bg-gray-100 p-4 sm:p-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="/pokemons"
                   class="flex-1 inline-block text-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base">
                    ← Volver a Pokédex
                </a>
                <a href="/pokemon/search"
                   class="flex-1 inline-block text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base">
                    🔍 Buscar Otro
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';
?>

