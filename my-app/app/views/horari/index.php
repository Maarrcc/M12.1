<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/index.css" />
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/navbar.css" />
    <title>Horari Interactiu</title>
</head>

<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="container">
        <h1>Horari</h1>
        <div class="controls">
            <div class="selectors">
                <select id="selector-cicle">
                    <?php
                    $cicles = ['DAW', 'DAM', 'ASIX'];
                    foreach ($cicles as $cicle) {
                        echo "<option value='$cicle'>$cicle</option>";
                    }
                    ?>
                </select>
                <select id="selector-any">
                    <?php
                    $anys = ['Primer', 'Segon'];
                    foreach ($anys as $any) {
                        echo "<option value='$any'>$any</option>";
                    }
                    ?>
                </select>
                <!-- Selector de día para móvil -->
                <select id="selector-dia" class="mobile-only">
                    <option value="Dilluns">Dilluns</option>
                    <option value="Dimarts">Dimarts</option>
                    <option value="Dimecres">Dimecres</option>
                    <option value="Dijous">Dijous</option>
                    <option value="Divendres">Divendres</option>
                </select>
            </div>
            <div class="selector-setmana">
                <button id="setmanaAnterior">
                    < </button>
                        <span id="setmanaActual"></span>
                        <button id="setmanaSeguent">></button>
            </div>
        </div>

        <div class="horari-container">
            <!-- Encabezado -->
            <div class="horari-header">
                <div class="header-cell hora-label"></div> <!-- Celda vacía en la esquina -->
                <div class="header-cell">Dilluns</div>
                <div class="header-cell">Dimarts</div>
                <div class="header-cell">Dimecres</div>
                <div class="header-cell">Dijous</div>
                <div class="header-cell">Divendres</div>
            </div>

            <!-- Contenido dinámico del horario -->
            <div class="horari-grid" id="horari">
                <!-- Las celdas se generarán con JavaScript -->
            </div>
        </div>
    </div>

    <script src="/M12.1/my-app/public/js/config.js"></script>
    <script src="/M12.1/my-app/public/js/horari.js"></script>
    <script src="/M12.1/my-app/public/js/dates.js"></script>
    <script src="/M12.1/my-app/public/js/cursos.js"></script>
</body>

</html>