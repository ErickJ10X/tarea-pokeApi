<?php
/**
 * Pokemon Card Component
 *
 * Vista parcial para mostrar una tarjeta de Pokémon.
 * Muestra la imagen, nombre, tipos y un enlace para ver más detalles.
 *
 * Variables esperadas:
 * @var \App\Models\Entity\Pokemon $pokemon Instancia del Pokémon a mostrar
 */
?>
<div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
    <!-- Image Section -->
    <div class="h-32 sm:h-40 flex items-center justify-center p-3 sm:p-4" style="background-color: <?= $pokemon->getTypeColor() ?>;">
        <?php if ($pokemon->imageUrl): ?>
            <img src="<?= htmlspecialchars($pokemon->imageUrl) ?>"
                 alt="<?= htmlspecialchars($pokemon->name) ?>"
                 class="w-28 h-28 sm:w-36 sm:h-36 object-contain drop-shadow-lg">
        <?php else: ?>
            <div class="text-white text-center">
                <p class="text-xs sm:text-sm font-semibold">No hay imagen</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content Section -->
    <div class="p-3 sm:p-4 flex flex-col flex-grow">
        <!-- Name -->
        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-2 capitalize line-clamp-2">
            <?= htmlspecialchars($pokemon->name) ?>
        </h3>

        <!-- Types -->
        <p class="text-xs sm:text-sm text-gray-600 mb-2">
            <span class="font-semibold">Tipos:</span>
        </p>
        <div class="mb-3 flex flex-wrap gap-1">
            <?php foreach ($pokemon->types as $type): ?>
                <span class="inline-block bg-gray-200 rounded-full px-2 py-0.5 text-xs font-semibold text-gray-700 capitalize">
                    <?= htmlspecialchars($type) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Measurements -->
        <div class="text-xs text-gray-500 space-y-1 mb-4 flex-grow">
            <p>
                <span class="font-semibold">Altura:</span>
                <span><?= number_format($pokemon->getHeightInMeters(), 2) ?>m</span>
            </p>
            <p>
                <span class="font-semibold">Peso:</span>
                <span><?= number_format($pokemon->getWeightInKilograms(), 2) ?>kg</span>
            </p>
        </div>

        <!-- Button -->
        <a href="/pokemon?id=<?= $pokemon->id ?>"
           class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded transition-colors duration-200 text-xs sm:text-sm">
            Ver Detalles
        </a>
    </div>
</div>

