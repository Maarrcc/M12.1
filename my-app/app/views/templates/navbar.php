<nav class="nav-container">
    <div class="nav-menu">
        <a href="#" class="nav-logo">EduPlanner</a>
        <button class="nav-toggle" aria-label="Toggle menu">
            <span class="hamburger"></span>
        </button>
        <ul class="nav-links">
            <li><a href="/M12.1/my-app/public/index.php?controller=horari&action=index">Inici</a></li>
            <li><a href="/M12.1/my-app/public/index.php?controller=assignaturesAlumnes&action=index">Notificacions</a>
            </li>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Alumnes</a>
                    <ul class="dropdown-menu">
                        <li><a href="/M12.1/my-app/public/index.php?controller=alumnes&action=assign">Assignar Alumne</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Professors</a>
                    <ul class="dropdown-menu">
                        <li><a href="/M12.1/my-app/public/index.php?controller=professor&action=new">Nou Professor</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=professor&action=assign">Assignar
                                Professor</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Acadèmic</a>
                    <ul class="dropdown-menu">
                        <li><a href="/M12.1/my-app/public/index.php?controller=horari&action=create">Nou Horari</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=assignatures&action=create">Nova
                                Assignatura</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=aula&action=create">Nova Aula</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Gestió</a>
                    <ul class="dropdown-menu">
                        <li><a href="/M12.1/my-app/public/index.php?controller=curs">Gestió Cursos</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=horari&action=manage">Gestió Horaris</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=canvis&action=index">Gestió Canvis</a>
                        </li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=usuaris">Gestió Usuaris</a></li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=import&action=index">Importar Usuaris</a>
                        </li>
                        <li><a href="/M12.1/my-app/public/index.php?controller=canvis&action=create">Registrar Canvi</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="/M12.1/my-app/public/index.php?controller=auth&action=logout"
                        class="nav-button"><?php echo htmlspecialchars($_SESSION['user']['nom_usuari'] ?? 'Usuario'); ?></a>
                </li>
            <?php else: ?>
                <li><a href="/M12.1/my-app/public/index.php?controller=auth&action=login" class="nav-button">Iniciar
                        Sessió</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script src="/M12.1/my-app/public/js/navbar.js"></script>