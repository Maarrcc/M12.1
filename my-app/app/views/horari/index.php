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
<nav class="nav-container">
        <div class="nav-menu">
            <a href="#" class="nav-logo">EduPlanner</a>
            <ul class="nav-links">
                <li><a href="/M12.1/my-app/public/index.php?controller=horari&action=index">Inici</a></li>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Administrar</a>
                        <ul class="dropdown-menu">
                            <li><a href="/M12.1/my-app/public/index.php?controller=alumnes&action=assign">Assignar Alumne</a></li>
                            <li><a href="/M12.1/my-app/public/index.php?controller=assignatures&action=create">Nova Assignatura</a></li>
                            <li><a href="/M12.1/my-app/public/index.php?controller=aula&action=create">Nova Aula</a></li>
                            <li><a href="/M12.1/my-app/public/index.php?controller=professor&action=assign">Assignar Professor</a></li>
                            <li><a href="/M12.1/my-app/public/index.php?controller=canvi&action=create">Registrar Canvi</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li><a href="#" class="nav-button">Contacte</a></li>
            </ul>
        </div>
    </nav>
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
            </div>
            <div class="selector-setmana">
                <button id="setmanaAnterior"><</button>
                <span id="setmanaActual"></span>
                <button id="setmanaSeguent">></button>
            </div>
        </div>
        <table id="horari">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Dilluns</th>
                    <th>Dimarts</th>
                    <th>Dimecres</th>
                    <th>Dijous</th>
                    <th>Divendres</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <script src="/M12.1/my-app/public/js/horari.js"></script>
    <script src="/M12.1/my-app/public/js/dates.js"></script>
    <script src="/M12.1/my-app/public/js/cursos.js"></script>
</body>
</html>