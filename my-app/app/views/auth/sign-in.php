<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre - Sistema Horari</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Registre d'Usuari</h2>
        <form method="POST" action="/M12.1/my-app/public/index.php?controller=auth&action=store">
            <div class="form-group">
                <label for="nom_usuari">Nom d'usuari:</label>
                <input type="text" id="nom_usuari" name="nom_usuari" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom complet:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="email">Correu electrònic:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasenya">Contrasenya:</label>
                <input type="password" id="contrasenya" name="contrasenya" required>
                <div id="password-feedback" class="password-feedback"></div>
            </div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <div class="form-group">
                    <label for="rol">Tipus d'usuari:</label>
                    <select id="rol" name="rol" required>
                        <option value="alumne">Alumne</option>
                        <option value="professor">Professor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" name="rol" value="alumne">
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <button type="submit" class="btn-login">Registrar</button>
        </form>
    </div>
    <script src="/M12.1/my-app/public/js/password-validation.js"></script>
</body>
</html>