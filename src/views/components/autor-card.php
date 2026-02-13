<div class="hover:shadow-lg max-w-sm bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
    <div class="p-5">
        <form action="/autor" method="get">
            <input type="hidden" name="id" value="<?= htmlspecialchars($autor->id ?? '') ?>">
            <button type="submit" class="cursor-pointer mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <?= htmlspecialchars($autor->nombre . ' ' . $autor->apellidos ?? '') ?>
            </button>
            <p class="mb-3 font-normal text-gray-700 dark:text-white">
                <strong>Nacionalidad:</strong> <?= htmlspecialchars($autor->nacionalidad ?? '') ?>
            </p>
        </form>
    </div>
</div>