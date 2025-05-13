<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió de Canvis</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/HorarioCrud.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../templates/navbar.php'; ?>
    
    <div class="horari-manage-container">
        <h2>Gestió de Canvis</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="actions-container">
            <a href="/M12.1/my-app/public/index.php?controller=canvis&action=create" class="btn-add">
                <i class="fas fa-plus"></i> Registrar Nou Canvi
            </a>
        </div>

        <div class="table-container">
            <?php if (empty($canvis)): ?>
                <p>No hi ha canvis registrats.</p>
            <?php else: ?>
                <table class="horari-table">
                    <thead>
                        <tr>
                            <th>Curs</th>
                            <th>Dia</th>
                            <th>Horari</th>
                            <th>Tipus de Canvi</th>
                            <th>Data Inici</th>
                            <th>Data Fi</th>
                            <th>Estat</th>
                            <th>Accions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($canvis as $canvi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($canvi['curs']); ?></td>
                                <td><?php echo htmlspecialchars($canvi['dia']); ?></td>
                                <td><?php echo htmlspecialchars($canvi['hora_inici'] . ' - ' . $canvi['hora_fi']); ?></td>
                                <td><?php echo htmlspecialchars($canvi['tipus_canvi']); ?></td>
                                <td><?php echo htmlspecialchars($canvi['data_canvi']); ?></td>
                                <td><?php echo $canvi['data_fi'] ? htmlspecialchars($canvi['data_fi']) : 'No especificada'; ?></td>
                                <td>
                                    <span class="badge <?php echo $canvi['estat'] === 'actiu' ? 'badge-success' : 'badge-inactive'; ?>">
                                        <?php echo htmlspecialchars($canvi['estat']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="/M12.1/my-app/public/index.php?controller=canvis&action=delete" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_canvi" value="<?php echo $canvi['id_canvi']; ?>">
                                        <button type="submit" class="btn-delete" onclick="return confirm('Estàs segur que vols eliminar aquest canvi?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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