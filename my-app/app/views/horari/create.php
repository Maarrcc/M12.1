<?php include_once __DIR__ . '/../templates/navbar.php'; ?>

<div class="container mt-4">
    <h2>Crear Nou Horari</h2>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/horariForm.css">

    <form action="/M12.1/my-app/public/index.php?controller=horari&action=store" method="POST">
        <div class="form-group">
            <label for="dia">Dia</label>
            <select name="dia" id="dia" class="form-control" required>
                <option value="Dilluns">Dilluns</option>
                <option value="Dimarts">Dimarts</option>
                <option value="Dimecres">Dimecres</option>
                <option value="Dijous">Dijous</option>
                <option value="Divendres">Divendres</option>
            </select>
        </div>

        <div class="form-group">
            <label for="hora_inici">Hora Inici</label>
            <input type="time" name="hora_inici" id="hora_inici" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="hora_fi">Hora Fi</label>
            <input type="time" name="hora_fi" id="hora_fi" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="id_assignatura">Assignatura</label>
            <select name="id_assignatura" id="id_assignatura" class="form-control" required>
                <?php foreach ($assignatures as $assignatura): ?>
                    <option value="<?php echo $assignatura['id_assignatura']; ?>">
                        <?php echo htmlspecialchars($assignatura['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_professor">Professor</label>
            <select name="id_professor" id="id_professor" class="form-control" required>
                <?php foreach ($professors as $professor): ?>
                    <option value="<?php echo $professor['id_professor']; ?>">
                        <?php echo htmlspecialchars($professor['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_aula">Aula</label>
            <select name="id_aula" id="id_aula" class="form-control" required>
                <?php foreach ($aulas as $aula): ?>
                    <option value="<?php echo $aula['id_aula']; ?>">
                        <?php echo htmlspecialchars($aula['nom_aula']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_curs">Curs</label>
            <select name="id_curs" id="id_curs" class="form-control" required>
                <?php foreach ($cursos as $curs): ?>
                    <option value="<?php echo $curs['id_curs']; ?>">
                        <?php echo htmlspecialchars($curs['nom_cicle'] . ' ' . $curs['any_academic']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Horari</button>
        <a href="/M12.1/my-app/public/index.php?controller=horari" class="btn btn-secondary">Tornar</a>
    </form>
</div>