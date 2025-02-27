<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Registrar Canvi</title>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/formularioGigante.css">
</head>

<body>
    <div class="login-container">
        <h2>Registrar Nou Canvi</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/M12.1/my-app/public/index.php?controller=canvi&action=store" method="POST">
            <div class="form-group">
                <label for="dia">Dia de la setmana:</label>
                <select name="dia" id="dia" required>
                    <option value="">Selecciona un dia</option>
                    <option value="Dilluns">Dilluns</option>
                    <option value="Dimarts">Dimarts</option>
                    <option value="Dimecres">Dimecres</option>
                    <option value="Dijous">Dijous</option>
                    <option value="Divendres">Divendres</option>
                </select>
            </div>

            <div class="form-group" id="horari_selector" style="display:none;">
                <label for="id_horari">Horari:</label>
                <select name="id_horari" id="id_horari" required>
                    <option value="">Primer selecciona un dia</option>
                </select>
            </div>

            <input type="hidden" id="id_curs" name="id_curs" required>

            <div class="form-group">
                <label for="tipus_canvi">Tipus de canvi:</label>
                <select name="tipus_canvi" id="tipus_canvi" required>
                    <option value="">Selecciona tipus</option>
                    <option value="Absència professor">Absència professor</option>
                    <option value="Canvi aula">Canvi aula</option>
                    <option value="Canvi professor">Canvi professor</option>
                    <option value="Classe cancelada">Classe cancelada</option>
                    <option value="Altres">Altres</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_canvi">Data del canvi:</label>
                <input type="date" id="data_canvi" name="data_canvi" required>
            </div>

            <div class="form-group" id="data_fi_group">
                <label for="data_fi">Data fi:</label>
                <input type="date" id="data_fi" name="data_fi">
            </div>

            <div class="form-group field-group" id="professor_group">
                <label for="id_professor_substitut">Professor substitut:</label>
                <select name="id_professor_substitut" id="id_professor_substitut">
                    <option value="">Selecciona professor</option>
                    <?php foreach ($professors as $professor): ?>
                        <option value="<?php echo $professor['id_professor']; ?>">
                            <?php echo htmlspecialchars($professor['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group field-group" id="aula_group">
                <label for="id_aula_substituta">Aula substituta:</label>
                <select name="id_aula_substituta" id="id_aula_substituta">
                    <option value="">Selecciona aula</option>
                    <?php foreach ($aules as $aula): ?>
                        <option value="<?php echo $aula['id_aula']; ?>">
                            <?php echo htmlspecialchars($aula['nom_aula']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcio_canvi">Descripció:</label>
                <textarea id="descripcio_canvi" name="descripcio_canvi" required></textarea>
            </div>

            <button type="submit" class="btn-login">Registrar Canvi</button>
        </form>
    </div>

    <script src="/M12.1/my-app/public/js/canvis.js"></script>
</body>

</html>