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
        <form id="loginForm">
            <div class="form-group">
                <label for="username">Usuari:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contrasenya:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div id="errorMessage" class="error-message">
                Usuari o contrasenya incorrectes
            </div>
            <button type="submit" class="btn-login">Iniciar Sessió</button>
        </form>
    </div>
    <script src="/M12.1/my-app/public/js/login.js"></script>
</body>
</html>