<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Nou Professor</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>

<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="login-container">
        <h2>Nou Professor</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/M12.1/my-app/public/index.php?controller=professor&action=create" method="POST">
            <div class="form-group">
                <label for="id_usuari">Usuari:</label>
                <select name="id_usuari" id="id_usuari" required>
                    <option value="">Selecciona un usuari</option>
                    <?php foreach ($usuaris as $usuari): ?>
                        <option value="<?php echo $usuari['id_usuari']; ?>">
                            <?php echo htmlspecialchars($usuari['nom'] . ' (' . $usuari['nom_usuari'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-login">Crear Professor</button>
        </form>
    </div>
</body>

</html>