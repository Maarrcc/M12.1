<?php include_once '../app/views/templates/navbar.php'; ?>

<div class="container mt-4">
    <h2>Editar Curso</h2>
    <form action="/M12.1/my-app/public/index.php?controller=curs&action=update" method="POST">
        <input type="hidden" name="id_curs" value="<?php echo $curs['id_curs']; ?>">
        <div class="form-group">
            <label for="nom_cicle">Ciclo</label>
            <select name="nom_cicle" id="nom_cicle" class="form-control" required>
                <option value="DAW" <?php echo $curs['nom_cicle'] == 'DAW' ? 'selected' : ''; ?>>DAW</option>
                <option value="DAM" <?php echo $curs['nom_cicle'] == 'DAM' ? 'selected' : ''; ?>>DAM</option>
                <option value="ASIX" <?php echo $curs['nom_cicle'] == 'ASIX' ? 'selected' : ''; ?>>ASIX</option>
            </select>
        </div>
        <div class="form-group">
            <label for="any_academic">Año Académico</label>
            <select name="any_academic" id="any_academic" class="form-control" required>
                <option value="Primer" <?php echo $curs['any_academic'] == 'Primer' ? 'selected' : ''; ?>>Primer</option>
                <option value="Segon" <?php echo $curs['any_academic'] == 'Segon' ? 'selected' : ''; ?>>Segon</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Curso</button>
        <a href="/M12.1/my-app/public/index.php?controller=curs" class="btn btn-secondary">Volver</a>
    </form>
</div>