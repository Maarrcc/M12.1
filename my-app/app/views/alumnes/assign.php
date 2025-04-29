<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Assignar Alumne</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>
<body>
<?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="login-container">
        <h2>Assignar Alumne amb Curs</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cursosActuals)): ?>
            <div class="cursos-actuales">
                <h3>Cursos Actuals</h3>
                <ul>
                    <?php foreach ($cursosActuals as $curs): ?>
                        <li><?php echo htmlspecialchars($curs['nom_cicle'] . ' - ' . $curs['any_academic']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>Pots assignar-te fins a 2 cursos en total.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($cursosActuals) || count($cursosActuals) < 2): ?>
            <form action="/M12.1/my-app/public/index.php?controller=alumnes&action=store" method="POST">
                <div class="form-group">
                    <label for="id_usuari">Alumne:</label>
                    <select name="id_usuari" id="id_usuari" required>
                        <?php foreach ($usuaris as $usuari): ?>
                            <option value="<?php echo $usuari['id_usuari']; ?>">
                                <?php echo htmlspecialchars($usuari['nom'] . ' (' . $usuari['nom_usuari'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_curs">Curs:</label>
                    <select name="id_curs" id="id_curs" required>
                        <?php foreach ($cursos as $curs): ?>
                            <option value="<?php echo $curs['id_curs']; ?>">
                                <?php echo htmlspecialchars($curs['nom_cicle'] . ' - ' . $curs['any_academic']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-login">Assignar Alumne</button>
            </form>
        <?php else: ?>
            <div class="info-message">
                Ja estàs assignat al màxim nombre de cursos permès (2).
            </div>
        <?php endif; ?>
    </div>
</body>
</html>