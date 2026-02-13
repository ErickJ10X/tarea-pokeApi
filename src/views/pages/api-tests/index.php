<?php
/**
 * API Tests Index View
 *
 * Página de pruebas interactivas para validar la integración con PokeAPI.
 * Proporciona formularios y ejemplos para probar todos los endpoints disponibles.
 *
 * Variables esperadas:
 * @var string $title Título de la página
 */

$title = 'Pruebas API PokeAPI';
ob_start();
?>

<div class="w-full bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">🧪 Pruebas API PokeAPI</h1>
            <p class="text-sm sm:text-base text-gray-600 mb-4">
                Esta página permite probar todos los endpoints integrados de la API PokeAPI.
                Utiliza los formularios de abajo para realizar consultas y ver las respuestas en JSON.
            </p>
            <a href="/" class="inline-flex items-center text-blue-500 hover:text-blue-700 font-semibold text-sm sm:text-base">
                ← Volver al inicio
            </a>
        </div>

        <!-- Information Banner -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4 mb-8">
            <p class="text-xs sm:text-sm text-yellow-800">
                <strong>📌 Nota:</strong> Las respuestas se devuelven en formato JSON.
                Abre la consola de desarrollador (F12) para ver las respuestas formateadas.
            </p>
        </div>

        <!-- Test Forms Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8">
            <!-- Test 1: Get Pokemon by ID -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">1. Obtener por ID</h2>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Obtiene información detallada de un Pokémon usando su ID.</p>

                <form class="space-y-3" onsubmit="testGetPokemonById(event)">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">ID del Pokémon</label>
                        <input type="number" id="pokemonId" min="1" max="1025" placeholder="Ej: 25"
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm"
                               value="25">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors text-sm">
                        Probar
                    </button>
                </form>

                <div id="result1" class="mt-4 hidden bg-gray-100 rounded p-3 overflow-auto max-h-48 sm:max-h-64">
                    <pre id="result1Text" class="text-xs"></pre>
                </div>
            </div>

            <!-- Test 2: Search Pokemon by Name -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">2. Buscar por Nombre</h2>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Busca un Pokémon por su nombre exacto.</p>

                <form class="space-y-3" onsubmit="testSearchPokemon(event)">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Nombre del Pokémon</label>
                        <input type="text" id="pokemonName" placeholder="Ej: pikachu"
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm"
                               value="pikachu">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors text-sm">
                        Probar
                    </button>
                </form>

                <div id="result2" class="mt-4 hidden bg-gray-100 rounded p-3 overflow-auto max-h-48 sm:max-h-64">
                    <pre id="result2Text" class="text-xs"></pre>
                </div>
            </div>

            <!-- Test 3: List Pokemon with Pagination -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">3. Listar (Paginación)</h2>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Obtiene una lista paginada de Pokémon.</p>

                <form class="space-y-3" onsubmit="testGetPokemons(event)">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Límite (máx 20)</label>
                        <input type="number" id="limit" min="1" max="20" placeholder="Ej: 10"
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm"
                               value="10">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Offset</label>
                        <input type="number" id="offset" min="0" placeholder="Ej: 0"
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm"
                               value="0">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors text-sm">
                        Probar
                    </button>
                </form>

                <div id="result3" class="mt-4 hidden bg-gray-100 rounded p-3 overflow-auto max-h-48 sm:max-h-64">
                    <pre id="result3Text" class="text-xs"></pre>
                </div>
            </div>

            <!-- Test 4: Filter by Type -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">4. Filtrar por Tipo</h2>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Obtiene todos los Pokémon de un tipo específico.</p>

                <form class="space-y-3" onsubmit="testFilterByType(event)">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Tipo de Pokémon</label>
                        <select id="pokemonType" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                            <option value="fire">Fire (Fuego)</option>
                            <option value="water">Water (Agua)</option>
                            <option value="grass">Grass (Planta)</option>
                            <option value="electric">Electric (Eléctrico)</option>
                            <option value="flying">Flying (Volador)</option>
                            <option value="psychic">Psychic (Psíquico)</option>
                            <option value="bug">Bug (Bicho)</option>
                            <option value="rock">Rock (Roca)</option>
                            <option value="ghost">Ghost (Fantasma)</option>
                            <option value="dark">Dark (Oscuro)</option>
                            <option value="dragon">Dragon (Dragón)</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors text-sm">
                        Probar
                    </button>
                </form>

                <div id="result4" class="mt-4 hidden bg-gray-100 rounded p-3 overflow-auto max-h-48 sm:max-h-64">
                    <pre id="result4Text" class="text-xs"></pre>
                </div>
            </div>

            <!-- Test 5: Get All Types -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">5. Obtener Tipos</h2>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Obtiene la lista de todos los tipos de Pokémon disponibles.</p>

                <form class="space-y-3" onsubmit="testGetAllTypes(event)">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors text-sm">
                        Probar
                    </button>
                </form>

                <div id="result5" class="mt-4 hidden bg-gray-100 rounded p-3 overflow-auto max-h-48 sm:max-h-64">
                    <pre id="result5Text" class="text-xs"></pre>
                </div>
            </div>

            <!-- Endpoint Reference -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">📚 Referencia</h2>
                <div class="space-y-2 text-xs sm:text-sm text-gray-700 overflow-x-auto">
                    <p><code class="bg-blue-100 px-2 py-1 rounded inline-block">GET /api/tests/get-pokemon-by-id?id={id}</code></p>
                    <p><code class="bg-blue-100 px-2 py-1 rounded inline-block">GET /api/tests/search-pokemon?name={name}</code></p>
                    <p><code class="bg-blue-100 px-2 py-1 rounded inline-block">GET /api/tests/get-pokemons?limit={l}&offset={o}</code></p>
                    <p><code class="bg-blue-100 px-2 py-1 rounded inline-block">GET /api/tests/filter-by-type?type={type}</code></p>
                    <p><code class="bg-blue-100 px-2 py-1 rounded inline-block">GET /api/tests/get-all-types</code></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loading" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 sm:p-8 text-center">
        <div class="animate-spin inline-block w-8 h-8 border-4 border-gray-300 border-t-blue-500 rounded-full"></div>
        <p class="mt-4 text-gray-700 text-xs sm:text-sm">Cargando...</p>
    </div>
</div>

<script>
/**
 * Muestra el estado de carga
 * @param {boolean} show - Si mostrar o no el estado de carga
 */
function showLoading(show) {
    document.getElementById('loading').classList.toggle('hidden', !show);
}

/**
 * Muestra el resultado de una prueba
 * @param {string} elementId - ID del elemento donde mostrar el resultado
 * @param {object} data - Datos a mostrar
 * @param {boolean} success - Si fue exitoso
 */
function displayResult(elementId, data, success) {
    const resultDiv = document.getElementById(elementId);
    const resultText = document.getElementById(elementId + 'Text');
    resultText.textContent = JSON.stringify(data, null, 2);
    resultDiv.classList.remove('hidden');
}

/**
 * Prueba el endpoint de obtener Pokémon por ID
 * @param {Event} event - Evento del formulario
 */
async function testGetPokemonById(event) {
    event.preventDefault();
    const id = document.getElementById('pokemonId').value;
    showLoading(true);
    try {
        const response = await fetch(`/api/tests/get-pokemon-by-id?id=${id}`);
        const data = await response.json();
        displayResult('result1', data, response.ok);
    } catch (error) {
        displayResult('result1', { error: error.message }, false);
    } finally {
        showLoading(false);
    }
}

/**
 * Prueba el endpoint de búsqueda por nombre
 * @param {Event} event - Evento del formulario
 */
async function testSearchPokemon(event) {
    event.preventDefault();
    const name = document.getElementById('pokemonName').value;
    showLoading(true);
    try {
        const response = await fetch(`/api/tests/search-pokemon?name=${encodeURIComponent(name)}`);
        const data = await response.json();
        displayResult('result2', data, response.ok);
    } catch (error) {
        displayResult('result2', { error: error.message }, false);
    } finally {
        showLoading(false);
    }
}

/**
 * Prueba el endpoint de listar Pokémon
 * @param {Event} event - Evento del formulario
 */
async function testGetPokemons(event) {
    event.preventDefault();
    const limit = document.getElementById('limit').value;
    const offset = document.getElementById('offset').value;
    showLoading(true);
    try {
        const response = await fetch(`/api/tests/get-pokemons?limit=${limit}&offset=${offset}`);
        const data = await response.json();
        displayResult('result3', data, response.ok);
    } catch (error) {
        displayResult('result3', { error: error.message }, false);
    } finally {
        showLoading(false);
    }
}

/**
 * Prueba el endpoint de filtrar por tipo
 * @param {Event} event - Evento del formulario
 */
async function testFilterByType(event) {
    event.preventDefault();
    const type = document.getElementById('pokemonType').value;
    showLoading(true);
    try {
        const response = await fetch(`/api/tests/filter-by-type?type=${type}`);
        const data = await response.json();
        displayResult('result4', data, response.ok);
    } catch (error) {
        displayResult('result4', { error: error.message }, false);
    } finally {
        showLoading(false);
    }
}

/**
 * Prueba el endpoint de obtener todos los tipos
 * @param {Event} event - Evento del formulario
 */
async function testGetAllTypes(event) {
    event.preventDefault();
    showLoading(true);
    try {
        const response = await fetch(`/api/tests/get-all-types`);
        const data = await response.json();
        displayResult('result5', data, response.ok);
    } catch (error) {
        displayResult('result5', { error: error.message }, false);
    } finally {
        showLoading(false);
    }
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';
?>

