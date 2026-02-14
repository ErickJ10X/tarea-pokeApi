<?php /** @var string $title */ /** @var array $routes */ ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Test Navigator') ?></title>
    <style>
        :root{ --bg:#f7fafc; --card:#ffffff; --accent:#0ea5a4; --muted:#6b7280 }
        body{ font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:var(--bg); color:#111827; margin:0; padding:24px }
        .container{ max-width:1100px; margin:0 auto }
        header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:20px }
        h1{ margin:0; font-size:22px }
        .grid{ display:grid; grid-template-columns: repeat(auto-fill, minmax(320px,1fr)); gap:16px }
        .card{ background:var(--card); border-radius:10px; padding:14px; box-shadow:0 1px 4px rgba(2,6,23,0.06) }
        .route{ padding:10px 0; border-bottom:1px dashed #eee }
        .route:last-child{ border-bottom: none }
        .method{ display:inline-block; padding:4px 8px; border-radius:6px; font-weight:700; color:white; font-size:12px }
        .method.GET{ background: #10b981 }
        .method.POST{ background: #3b82f6 }
        .method.DELETE{ background: #ef4444 }
        .method.PUT{ background: #f97316 }
        .method.PATCH{ background: #8b5cf6 }
        .controller{ color:var(--muted); font-size:13px }
        .nav-top{ display:flex; gap:8px; align-items:center }
        a.btn{ background:var(--accent); color:white; padding:8px 12px; border-radius:8px; text-decoration:none }
        button.small{ background:#111827; color:white; border:0; padding:6px 10px; border-radius:6px; cursor:pointer }
        input.param{ padding:6px; border-radius:6px; border:1px solid #e5e7eb; margin-right:6px }
        .controls{ margin-top:8px; display:flex; gap:8px; align-items:center }
        .result{ margin-top:8px; font-family: monospace; font-size:12px; color:#111827; background:#fafafa; padding:8px; border-radius:6px; border:1px solid #eee; max-height:120px; overflow:auto }
        footer{ margin-top:18px; color:var(--muted); font-size:14px }
        .meta{ display:flex; gap:8px; align-items:center; margin-top:6px }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Test Navigator</h1>
        <div class="nav-top">
            <a class="btn" href="/">Abrir Home</a>
            <a class="btn" href="/api/tests">API Tests</a>
        </div>
    </header>

    <p>Lista de rutas registradas en la aplicación. Haz click en cualquiera para probarlas en el navegador. Para rutas con placeholders (<code>:id</code>), introduce valores y presiona "Probar" o "Abrir".</p>

    <div class="grid">
        <?php
        if (empty($routes)):
            echo '<div class="card">No se han encontrado rutas.</div>';
        else:
            // Agrupar rutas por secciones simples según URI base
            $groups = [];
            foreach ($routes as $r) {
                $uri = $r['uri'];
                $parts = array_values(array_filter(explode('/', $uri)));
                $groupKey = $parts[0] ?? 'root';
                $groups[$groupKey][] = $r;
            }

            foreach ($groups as $group => $items): ?>
                <div class="card">
                    <h3 style="margin-top:0;text-transform:capitalize"><?= htmlspecialchars($group ?: 'root') ?></h3>
                    <?php foreach ($items as $it): ?>
                        <div class="route" data-uri="<?= htmlspecialchars($it['uri']) ?>" data-method="<?= htmlspecialchars($it['method']) ?>">
                            <div>
                                <span class="method <?= htmlspecialchars($it['method']) ?>"><?= htmlspecialchars($it['method']) ?></span>
                                <strong style="margin-left:8px"><?= htmlspecialchars($it['uri']) ?></strong>
                            </div>
                            <div class="controller"><?= htmlspecialchars($it['controller']) ?><?php if ($it['middleware']) echo ' • '.$it['middleware']; ?></div>

                            <?php
                            // Detectar placeholders como :id, :slug, etc.
                            preg_match_all('/:(\w+)/', $it['uri'], $matches);
                            $placeholders = $matches[1] ?? [];
                            ?>

                            <div class="controls">
                                <?php if (!empty($placeholders)): ?>
                                    <?php foreach ($placeholders as $ph): ?>
                                        <input class="param" data-name="<?= htmlspecialchars($ph) ?>" placeholder="<?= htmlspecialchars($ph) ?>" value="1">
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if ($it['method'] === 'GET'): ?>
                                    <button class="small btn-open">Abrir</button>
                                    <button class="small btn-fetch">Probar</button>
                                <?php else: ?>
                                    <form class="inline-form" method="post" style="display:inline-block; margin:0; padding:0">
                                        <?php if ($it['method'] !== 'POST'): ?>
                                            <input type="hidden" name="_method" value="<?= htmlspecialchars($it['method']) ?>">
                                        <?php endif; ?>
                                        <!-- inputs para placeholders serán creados por JS al enviar -->
                                        <button class="small" type="submit">Ejecutar <?= htmlspecialchars($it['method']) ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <div class="result" style="display:none"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <small>Generado automáticamente desde Router::getRoutesList().</small>
    </footer>
</div>

<script>
(function(){
    // Reemplaza placeholders en una URI con los valores provistos en inputs
    function buildUri(template, container){
        return template.replace(/:(\w+)/g, function(_, name){
            var input = container.querySelector('input[data-name="'+name+'"]').value || '';
            return encodeURIComponent(input);
        });
    }

    function findContainer(el){
        while(el && !el.classList.contains('route')) el = el.parentElement;
        return el;
    }

    document.querySelectorAll('.btn-open').forEach(function(btn){
        btn.addEventListener('click', function(e){
            var container = findContainer(e.target);
            var uriTemplate = container.getAttribute('data-uri');
            var final = buildUri(uriTemplate, container);
            window.open(final, '_blank');
        });
    });

    document.querySelectorAll('.btn-fetch').forEach(function(btn){
        btn.addEventListener('click', function(e){
            var container = findContainer(e.target);
            var uriTemplate = container.getAttribute('data-uri');
            var final = buildUri(uriTemplate, container);
            var resultBox = container.querySelector('.result');
            resultBox.style.display = 'block';
            resultBox.textContent = 'Probando ' + final + ' ...';

            fetch(final, { method: 'GET', credentials: 'same-origin' })
                .then(function(resp){
                    resultBox.textContent = 'Status: ' + resp.status + ' ' + resp.statusText + '\n';
                    return resp.text().then(function(text){
                        var snippet = text.slice(0, 800);
                        resultBox.textContent += '\n' + snippet;
                    });
                })
                .catch(function(err){
                    resultBox.textContent = 'Error: ' + err.message;
                });
        });
    });

    // Manejar formularios para métodos distintos de GET (POST/DELETE/PUT/PATCH)
    document.querySelectorAll('.inline-form').forEach(function(form){
        form.addEventListener('submit', function(e){
            e.preventDefault();
            var container = findContainer(e.target);
            var uriTemplate = container.getAttribute('data-uri');
            var final = buildUri(uriTemplate, container);
            var method = container.getAttribute('data-method');

            // Crear formulario dinámico y enviarlo
            var f = document.createElement('form');
            f.method = 'post';
            f.action = final;
            f.style.display = 'none';

            if (method !== 'POST'){
                var m = document.createElement('input'); m.type='hidden'; m.name='_method'; m.value = method; f.appendChild(m);
            }

            // Añadir inputs para cada placeholder con su valor
            container.querySelectorAll('input.param').forEach(function(inp){
                var name = inp.getAttribute('data-name');
                var field = document.createElement('input'); field.type='hidden'; field.name = name; field.value = inp.value; f.appendChild(field);
            });

            document.body.appendChild(f);
            f.submit();
        });
    });
})();
</script>
</body>
</html>

