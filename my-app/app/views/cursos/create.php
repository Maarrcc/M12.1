<?php include_once '../app/views/templates/navbar.php'; ?>

<div class="container mt-4">
    <h2>Crear Nuevo Curso</h2>
    <form action="/M12.1/my-app/public/index.php?controller=curs&action=store" method="POST">
        <div class="form-group">
            <label for="nom_cicle">Ciclo</label>
            <select name="nom_cicle" id="nom_cicle" class="form-control" required>
                <option value="DAW">DAW</option>
                <option value="DAM">DAM</option>
                <option value="ASIX">ASIX</option>
            </select>
        </div>
        <div class="form-group">
            <label for="any_academic">Año Académico</label>
            <select name="any_academic" id="any_academic" class="form-control" required>
                <option value="Primer">Primer</option>
                <option value="Segon">Segon</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Curso</button>
        <a href="/M12.1/my-app/public/index.php?controller=curs" class="btn btn-secondary">Volver</a>
    </form>
</div>

