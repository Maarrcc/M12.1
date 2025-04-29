<?php include_once __DIR__ . '/../templates/navbar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="/M12.1/my-app/public/css/HorarioCrud.css">
<link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/navbar.css" />

<div class="horari-manage-container">
    <h2>Gestió d'Horaris</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="actions-container">
        <a href="/M12.1/my-app/public/index.php?controller=horari&action=create" class="btn-add">
            <i class="fas fa-plus"></i> Afegir Nou Horari
        </a>
    </div>

    <div class="table-container">
        <table class="horari-table">
            <thead>
                <tr>
                    <th>Cicle</th>
                    <th>Any</th>
                    <th>Dia</th>
                    <th>Hora Inici</th>
                    <th>Hora Fi</th>
                    <th>Assignatura</th>
                    <th>Professor</th>
                    <th>Aula</th>
                    <th>Accions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horaris as $horari): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($horari['nom_cicle']); ?></td>
                        <td><?php echo htmlspecialchars($horari['any_academic']); ?></td>
                        <td><?php echo htmlspecialchars($horari['dia']); ?></td>
                        <td><?php echo htmlspecialchars($horari['hora_inici']); ?></td>
                        <td><?php echo htmlspecialchars($horari['hora_fi']); ?></td>
                        <td><?php echo htmlspecialchars($horari['assignatura']); ?></td>
                        <td><?php echo htmlspecialchars($horari['professor']); ?></td>
                        <td><?php echo htmlspecialchars($horari['aula']); ?></td>
                        <td>
                            <form action="/M12.1/my-app/public/index.php?controller=horari&action=delete" method="POST">
                                <input type="hidden" name="id_horari" value="<?php echo $horari['id_horari']; ?>">
                                <button type="submit" class="btn-delete" onclick="return confirm('Estàs segur que vols eliminar aquest horari?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>