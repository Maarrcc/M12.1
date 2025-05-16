<?php

include_once '../app/views/templates/navbar.php'; ?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/M12.1/my-app/public/css/formularioGigante.css">
<link rel="stylesheet" href="/M12.1/my-app/public/css/navbar.css">

<div class="login-container">
    <h2>Crear Nou Curs</h2>
    <form action="/M12.1/my-app/public/index.php?controller=curs&action=store" method="POST">
        <div class="form-group">
            <label for="nom_cicle">Nom del curs</label>
            <select name="nom_cicle" id="nom_cicle" class="form-control" required>
                <option value="">Selecciona un cicle</option>
                <option value="DAW">DAW</option>
                <option value="DAM">DAM</option>
                <option value="ASIX">ASIX</option>
            </select>
        </div>
        <div class="form-group">
            <label for="any_academic">Any escolar</label>
            <select name="any_academic" id="any_academic" class="form-control" required>
                <option value="">Selecciona un any</option>
                <option value="Primer">Primer</option>
                <option value="Segon">Segon</option>
            </select>
        </div>
        <button type="submit" class="btn-login">Crear Curs</button>
        <a href="/M12.1/my-app/public/index.php?controller=curs" class="btn btn-secondary">Tornar</a>
    </form>
</div>