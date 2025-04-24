<?php require_once '../app/views/templates/navbar.php'; ?>

<div class="container">
    <h2>Gestió de Cursos</h2>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/M12.1/my-app/public/css/cursos.css">
    <link rel="stylesheet" type="text/css" href="/M12.1/my-app/public/css/navbar.css" />

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
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
                            <form action="/M12.1/my-app/public/index.php?controller=curs&action=delete" method="POST" style="display:inline;">
                                <input type="hidden" name="id_curs" value="<?php echo $curs['id_curs']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Estàs segur que vols eliminar aquest curs?')">
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