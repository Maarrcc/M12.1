<?php require_once '../app/views/templates/navbar.php'; ?>

<div class="container mt-4">
    <h2>Gestió de Cursos</h2>
    <link rel="stylesheet" href="/M12.1/my-app/public/css/cursos.css">
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/navbar.css" />

    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Cicle</th>
                <th>Any Acadèmic</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curs): ?>
                <tr>
                    <td><?php echo htmlspecialchars($curs['nom_cicle']); ?></td>
                    <td><?php echo htmlspecialchars($curs['any_academic']); ?></td>
                    <td>
                        <a href="/M12.1/my-app/public/index.php?controller=curs&action=edit&id=<?php echo $curs['id_curs']; ?>" 
                           class="btn btn-sm btn-info">Editar</a>
                        <form action="/M12.1/my-app/public/index.php?controller=curs&action=delete" method="POST" style="display:inline;">
                            <input type="hidden" name="id_curs" value="<?php echo $curs['id_curs']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este curso?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>