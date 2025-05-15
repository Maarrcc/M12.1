<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Importar Dades</title>
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/import.css">
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/navbar.css">
</head>

<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>

    <div class="container">
        <h2>Importar Dades</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/M12.1/my-app/public/index.php?controller=import&action=importar" method="POST"
            enctype="multipart/form-data">

            <div class="form-group">
                <label for="tipo_dato">Tipus de dades:</label>
                <select name="tipo_dato" id="tipo_dato" required>
                    <option value="usuaris">Usuaris</option>
                    <option value="alumnes">Alumnes</option>
                    <option value="professors">Professors</option>
                </select>
            </div>

            <div class="form-group">
                <label for="csv_file">Arxiu CSV:</label>
                <input type="file" name="csv_file" accept=".csv" required>
            </div>

            <button type="submit" class="btn-primary">Importar</button>
        </form>

        <div class="help-text">
            <h3>Format dels arxius CSV:</h3>
            <strong>Cal afegir capçalera</strong>
            <p><strong>Usuaris:</strong><br>
                nom_usuari,nom,email,contrasenya,rol</p>

            <p><strong>Alumnes:</strong><br>
                nom_usuari,nom,email,contrasenya,id_curs</p>

            <p><strong>Professors:</strong><br>
                nom_usuari,nom,email,contrasenya</p>
        </div>
    </div>
</body>

</html>