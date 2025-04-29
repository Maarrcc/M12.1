<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió de Notificacions</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/matricules.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>
<body>
<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

    <div class="container">
        <h2>Gestió de Notificacions d'Assignatures</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['user']['rol'] === 'alumne' && empty($curs)): ?>
            <div class="alert alert-warning">
                No estàs matriculat en cap curs. Contacta amb l'administrador.
            </div>
        <?php else: ?>

            <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Noves Subscripcions</h4>
                </div>
                <div class="card-body">
                    <form action="/M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=store" method="POST">
                        <div class="form-group mb-3">
                            <label for="id_alumne">Alumne:</label>
                            <select name="id_alumne" id="id_alumne" class="form-control" required>
                                <option value="">Selecciona un alumne</option>
                                <?php foreach ($alumnes as $alumne): ?>
                                    <option value="<?= $alumne['id_alumne'] ?>">
                                        <?= htmlspecialchars($alumne['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Assignatures:</label>
                            <div class="assignatures-grid">
                                <?php foreach ($assignatures as $assignatura): ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="assignatures[<?= $assignatura['id_assignatura'] ?>][id]" 
                                               value="<?= $assignatura['id_assignatura'] ?>" 
                                               class="form-check-input"
                                               id="ass_<?= $assignatura['id_assignatura'] ?>">
                                        <label class="form-check-label" for="ass_<?= $assignatura['id_assignatura'] ?>">
                                            <?= htmlspecialchars($assignatura['nom']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Preferències</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header"></div>
                    <h4><?= $_SESSION['user']['rol'] === 'admin' ? 'Totes les Preferències' : 'Les Meves Preferències' ?></h4>
                </div>
                <div class="card-body">
                    <?php if (empty($matricules)): ?>
                        <p class="text-muted">No hi ha subscripcions registrades.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                                            <th>Alumne</th>
                                        <?php endif; ?>
                                        <th>Assignatura</th>
                                        <th>Notificacions</th>
                                        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                                            <th>Accions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($matricules as $matricula): ?>
                                        <tr>
                                            <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                                                <td><?= htmlspecialchars($matricula['nom_complet']) ?></td>
                                            <?php endif; ?>
                                            <td><?= htmlspecialchars($matricula['nom_assignatura']) ?></td>
                                            <td>
                                                <?php if ($_SESSION['user']['rol'] === 'admin' || 
                                                      ($_SESSION['user']['rol'] === 'alumne' && isset($curs))): ?>
                                                    <form action="/M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=toggleNotificacions" 
                                                          method="POST" style="display: inline;">
                                                        <input type="hidden" name="id_alumne" value="<?= $matricula['id_alumne'] ?>">
                                                        <input type="hidden" name="id_assignatura" value="<?= $matricula['id_assignatura'] ?>">
                                                        <button type="submit" class="btn <?= $matricula['rebre_notificacions'] ? 'btn-success' : 'btn-secondary' ?> btn-sm">
                                                            <?= $matricula['rebre_notificacions'] ? 'Activades' : 'Desactivades' ?>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                                                <td>
                                                    <form action="/M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=delete" 
                                                          method="POST" style="display: inline;">
                                                        <input type="hidden" name="id_alumne" value="<?= $matricula['id_alumne'] ?>">
                                                        <input type="hidden" name="id_assignatura" value="<?= $matricula['id_assignatura'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Estàs segur que vols eliminar aquesta subscripció?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>