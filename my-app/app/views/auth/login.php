<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Horari</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Accés al Sistema</h2>
        <form method="POST" action="/M12.1/my-app/public/index.php?controller=auth&action=validate">
            <div class="form-group">
                <label for="username">Usuari:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contrasenya:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <button type="submit" class="btn-login">Iniciar Sessio</button>
        </form>
        <div class="register-link">
            <a href="/M12.1/my-app/public/index.php?controller=auth&action=register">Registrar nou usuari</a>
        </div>
    </div>
    <script src="/M12.1/my-app/public/js/login.js"></script>
</body>

</html>