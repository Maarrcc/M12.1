<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió d'Usuaris</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/HorarioCrud.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    
    <div class="horari-manage-container">
        <h2>Gestió d'Usuaris</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (empty($usuaris)): ?>
                <p>No hi ha usuaris registrats.</p>
            <?php else: ?>
                <table class="horari-table">
                    <thead>
                        <tr>
                            <th>Nom d'Usuari</th>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuaris as $usuari): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuari['nom_usuari']); ?></td>
                                <td><?php echo htmlspecialchars($usuari['nom']); ?></td>
                                <td><?php echo htmlspecialchars($usuari['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuari['rol']); ?></td>
                                <td>
                                    <?php if ($usuari['id_usuari'] != $_SESSION['user']['id_usuari']): ?>
                                        <form action="/M12.1/my-app/public/index.php?controller=usuaris&action=delete" method="POST" style="display: inline;">
                                            <input type="hidden" name="id_usuari" value="<?php echo $usuari['id_usuari']; ?>">
                                            <button type="submit" class="btn-delete" onclick="return confirm('Estàs segur que vols eliminar aquest usuari?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>