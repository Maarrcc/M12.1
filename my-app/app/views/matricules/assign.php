<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Gestió de Matrícules</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/matricules.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
</head>
<body>
<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

    <div class="container">
        <h2>Gestió de Matrícules</h2>

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

        <!-- Formulario de nueva matrícula -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Nova Matrícula</h4>
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
                                    <input type="checkbox" name="assignatures[]" 
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

                    <button type="submit" class="btn btn-primary">Matricular</button>
                </form>
            </div>
        </div>

        <!-- Tabla de matrículas existentes -->
        <div class="card">
            <div class="card-header">
                <h4>Matrícules Actuals</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Alumne</th>
                                <th>Assignatura</th>
                                <th>Accions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matricules as $matricula): ?>
                                <tr>
                                    <td><?= htmlspecialchars($matricula['nom_complet']) ?></td>
                                    <td><?= htmlspecialchars($matricula['nom_assignatura']) ?></td>
                                    <td>
                                        <form action="/M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=delete" 
                                              method="POST" style="display: inline;">
                                            <input type="hidden" name="id_alumne" value="<?= $matricula['id_alumne'] ?>">
                                            <input type="hidden" name="id_assignatura" value="<?= $matricula['id_assignatura'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Estàs segur que vols eliminar aquesta matrícula?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>