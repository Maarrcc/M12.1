<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Nova Assignatura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>
<body>
<?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="login-container">
        <h2>Nova Assignatura</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        <form action="/M12.1/my-app/public/index.php?controller=assignatures&action=store" method="POST">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="descripcio">Descripció:</label>
                <textarea id="descripcio" name="descripcio" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="hores">Hores:</label>
                <input type="number" id="hores" name="hores" required min="1">
            </div>
            <button type="submit" class="btn-login">Crear Assignatura</button>
        </form>
    </div>
</body>
</html>