<div class="d-flex" id="content">

    <style>
        aside ul.list-group.list-group-flush li{background: transparent;}
        aside ul.list-group.list-group-flush li a{text-decoration: none;color: var(--bs-primary);}
        .aside-menu {
            transition: transform 0.7s ease;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1000;
        }
        .aside-menu.hidden {
            transform: translateX(-100%); 
        }

    </style>

    <!-- Menu Lateral -->
    <aside class="bg-gray border-end aside-menu" style="width: 250px; padding: 20px;">
        <h5 class="text-center">Menu</h5>
        <ul class="nav flex-column">
            <!-- Botão: Agenda de Tarefas -->
            <li class="nav-item mb-2">
                <a href="index.php" class="nav-link d-flex align-items-center text-dark">
                    <i class="bi bi-calendar-check me-2"></i> Agenda de Tarefas
                </a>
            </li>
            <!-- Botão: Designar Tarefa -->
            <li class="nav-item mb-2">
                <a href="designar_tarefa.php" class="nav-link d-flex align-items-center text-dark">
                    <i class="bi bi-clipboard-plus me-2"></i> Designar Tarefa
                </a>
            </li>
            <!-- Botão: Visualizar Tarefas -->
            <li class="nav-item mb-2">
                <a href="visualizar_tarefas.php" class="nav-link d-flex align-items-center text-dark">
                    <i class="bi bi-eye me-2"></i> Visualizar Tarefas
                </a>
            </li>
            <!-- Botão: Visualizar Logs -->
            <li class="nav-item mb-2">
                <a href="visualizar_logs.php" class="nav-link d-flex align-items-center text-dark">
                    <i class="bi bi-journal-text me-2"></i> Visualizar Logs
                </a>
            </li>
            <!-- Botão: Usuários -->
            <li class="nav-item mb-2">
                <a href="usuarios_configuracao.php" class="nav-link d-flex align-items-center text-dark">
                    <i class="bi bi-people me-2"></i> Usuários
                </a>
            </li>
            <!-- Botão: Logout -->
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link d-flex align-items-center text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                </a>
            </li>
        </ul>
    </aside>
    <!-- Conteúdo Principal -->
    <main id="main">
