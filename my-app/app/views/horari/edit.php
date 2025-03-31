<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<link rel="stylesheet" href="/M12.1/my-app/public/css/horari.css">

<div class="container mt-4">
    <h2>Editar Horari</h2>
    <form action="/M12.1/my-app/public/index.php?controller=horari&action=update" method="POST">
        <input type="hidden" name="id_horari" value="<?php echo $horari['id_horari']; ?>">

        <div class="form-group">
            <label for="dia">Dia</label>
            <select name="dia" id="dia" class="form-control" required>
                <option value="Dilluns" <?php echo $horari['dia'] === 'Dilluns' ? 'selected' : ''; ?>>Dilluns</option>
                <option value="Dimarts" <?php echo $horari['dia'] === 'Dimarts' ? 'selected' : ''; ?>>Dimarts</option>
                <option value="Dimecres" <?php echo $horari['dia'] === 'Dimecres' ? 'selected' : ''; ?>>Dimecres</option>
                <option value="Dijous" <?php echo $horari['dia'] === 'Dijous' ? 'selected' : ''; ?>>Dijous</option>
                <option value="Divendres" <?php echo $horari['dia'] === 'Divendres' ? 'selected' : ''; ?>>Divendres</option>
            </select>
        </div>

        <div class="form-group">
            <label for="hora_inici">Hora Inici</label>
            <input type="time" name="hora_inici" id="hora_inici" class="form-control" 
                   value="<?php echo $horari['hora_inici']; ?>" required>
        </div>

        <div class="form-group">
            <label for="hora_fi">Hora Fi</label>
            <input type="time" name="hora_fi" id="hora_fi" class="form-control" 
                   value="<?php echo $horari['hora_fi']; ?>" required>
        </div>

        <div class="form-group">
            <label for="id_assignatura">Assignatura</label>
            <select name="id_assignatura" id="id_assignatura" class="form-control" required>
                <?php foreach ($assignatures as $assignatura): ?>
                    <option value="<?php echo $assignatura['id_assignatura']; ?>"
                            <?php echo $horari['id_assignatura'] == $assignatura['id_assignatura'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($assignatura['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_professor">Professor</label>
            <select name="id_professor" id="id_professor" class="form-control" required>
                <?php foreach ($professors as $professor): ?>
                    <option value="<?php echo $professor['id_professor']; ?>"
                            <?php echo $horari['id_professor'] == $professor['id_professor'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($professor['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_aula">Aula</label>
            <select name="id_aula" id="id_aula" class="form-control" required>
                <?php foreach ($aulas as $aula): ?>
                    <option value="<?php echo $aula['id_aula']; ?>"
                            <?php echo $horari['id_aula'] == $aula['id_aula'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($aula['nom_aula']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_curs">Curs</label>
            <select name="id_curs" id="id_curs" class="form-control" required>
                <?php foreach ($cursos as $curs): ?>
                    <option value="<?php echo $curs['id_curs']; ?>"
                            <?php echo $horari['id_curs'] == $curs['id_curs'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($curs['nom_cicle'] . ' ' . $curs['any_academic']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualitzar Horari</button>
        <a href="/M12.1/my-app/public/index.php?controller=horari" class="btn btn-secondary">Tornar</a>
    </form>
</div>