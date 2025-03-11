<nav class="nav-container">
    <div class="nav-menu">
        <a href="#" class="nav-logo">EduPlanner</a>
        <ul class="nav-links">
            <li><a href="/M12.1/my-app/public/index.php?controller=horari&action=index">Inici</a></li>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Administrar</a>
                    <ul class="dropdown-menu">
                        <li><a href="/M12.1/my-app/public/index.php?controller=alumnes&action=assign">Assignar Alumne</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=assignatures&action=create">Nova Assignatura</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=aula&action=create">Nova Aula</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=professor&action=new">Nou Professor</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=professor&action=assign">Assignar Professor</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=canvi&action=create">Registrar Canvi</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=import&action=index">Importar Usuaris</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <li><a href="#" class="nav-button">Contacte</a></li>
        </ul>
    </div>
</nav>