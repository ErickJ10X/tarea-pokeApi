<div class="hover:shadow-lg max-w-sm bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
    <div class="p-5">
        <form action="/libro" method="get">
            <input type="hidden" name="id" value="<?= htmlspecialchars($libro->id ?? '') ?>">
            <button type="submit" class="cursor-pointer mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <?= htmlspecialchars($libro->titulo ?? '') ?>
            </button>
            <p class="mb-3 font-normal text-gray-700 dark:text-white">
                <strong>Fecha de Publicación:</strong> <?= htmlspecialchars($libro->f_publicacion ?? '') ?>
            </p>
        </form>
    </div>
</div>