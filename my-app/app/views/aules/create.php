<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Nova Aula</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>

<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="login-container">
        <h2>Nova Aula</h2>
        <form action="/M12.1/my-app/public/index.php?controller=aula&action=store" method="POST">
            <div class="form-group">
                <label for="nom_aula">Nom Aula:</label>
                <input type="text" id="nom_aula" name="nom_aula" required>
            </div>
            <div class="form-group">
                <label for="capacitat">Capacitat:</label>
                <input type="number" id="capacitat" name="capacitat" required min="1">
            </div>
            <button type="submit" class="btn-login">Crear Aula</button>
        </form>
    </div>
</body>

</html>