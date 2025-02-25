<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Assignatures</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Assignatures</h1>
        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
            <a href="/M12.1/my-app/public/index.php?controller=assignatures&action=create" class="btn btn-primary">Nova Assignatura</a>
        <?php endif; ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Descripció</th>
                    <th>Hores</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignatures as $assignatura): ?>
                    <tr>
                        <td><?= htmlspecialchars($assignatura['id_assignatura']) ?></td>
                        <td><?= htmlspecialchars($assignatura['nom']) ?></td>
                        <td><?= htmlspecialchars($assignatura['descripcio']) ?></td>
                        <td><?= htmlspecialchars($assignatura['hores']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>