<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Assignar Professor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/login.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>

<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    <div class="login-container">
        <h2>Assignar Professor a Assignatura</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/M12.1/my-app/public/index.php?controller=professor&action=store" method="POST">
            <div class="form-group">
                <label for="id_professor">Professor:</label>
                <select name="id_professor" id="id_professor" required>
                    <option value="">Selecciona un professor</option>
                    <?php foreach ($professors as $professor): ?>
                        <option value="<?php echo $professor['id_professor']; ?>">
                            <?php echo htmlspecialchars($professor['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_assignatura">Assignatura:</label>
                <select name="id_assignatura" id="id_assignatura" required>
                    <option value="">Selecciona una assignatura</option>
                    <?php foreach ($assignatures as $assignatura): ?>
                        <option value="<?php echo $assignatura['id_assignatura']; ?>">
                            <?php echo htmlspecialchars($assignatura['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-login">Assignar Professor</button>
        </form>
    </div>
</body>

</html>