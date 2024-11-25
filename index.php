<?php
    session_start();
    include 'log_atividade.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }

    // Carregar as tarefas do JSON
    $tasksFile = 'json/tasks.json';
    $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];

    // Filtrar tarefas do usuário logado, excluindo tarefas arquivadas e encerradas
    $usuario = $_SESSION['usuario'];
    $userTasks = array_filter($tasks, function ($task) use ($usuario) {
        return $task['responsavel'] === $usuario && $task['status'] !== 'arquivada' && $task['status'] !== 'encerrada';
    });

    // "Arquivar" tarefa (em vez de excluir)
    if (isset($_POST['delete_task_id'])) {
        $task_id_to_archive = $_POST['delete_task_id'];

        // Atualizar o status da tarefa para "arquivada"
        foreach ($tasks as &$task) {
            if ($task['id'] === $task_id_to_archive) {
                $task['status'] = 'arquivada';
                break;
            }
        }

        // Salvar alterações no arquivo JSON
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
        header('Location: index.php'); // Recarregar a página
        exit;
    }

    // Responder a uma tarefa
    if (isset($_POST['respond_task_id']) && isset($_POST['resposta'])) {
        $task_id_to_respond = $_POST['respond_task_id'];
        $resposta = $_POST['resposta'];

        // Atualizar o status e adicionar a resposta
        foreach ($tasks as &$task) {
            if ($task['id'] === $task_id_to_respond) {
                $task['status'] = 'respondida';
                $task['resposta'] = $resposta;
                break;
            }
        }

        // Salvar alterações no arquivo JSON
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
        header('Location: index.php'); // Recarregar a página
        exit;
    }

    // Encerrar tarefa
    if (isset($_POST['close_task_id'])) {
        $task_id_to_close = $_POST['close_task_id'];

        // Atualizar o status da tarefa para "encerrada"
        foreach ($tasks as &$task) {
            if ($task['id'] === $task_id_to_close) {
                $task['status'] = 'encerrada';
                break;
            }
        }

        // Salvar alterações no arquivo JSON
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
        header('Location: index.php'); // Recarregar a página
        exit;
    }

    include "require/header.php";
    include "require/aside.php";
?>
    <div class="container-fluid mt-1">
        <div class="py-2">
        <h2 class="display-6">Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
        </div>

        <?php
        if (isset($_SESSION['mensagem'])) {
            echo '<div id="mensagem" class="alert alert-success">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem']);
        }
        ?>
        <script>
            // Remover a mensagem após 5 segundos (5000ms)
            setTimeout(function() {
                const mensagem = document.getElementById('mensagem');
                if (mensagem) {
                    mensagem.style.display = 'none';
                }
            }, 5000);
        </script>

        <link rel="stylesheet" href="/src/css/index.css">
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Agenda de Tarefas</h3>
                <div>
                    <a href="designar_tarefa.php" class="btn btn-success mt-3">Lançar Tarefa</a>
                    <a href="" class="btn btn-primary mt-3">Lançar Publicação</a>
                </div>
            </div>

            <?php if (!empty($userTasks)): ?>
                <div class="task-container">
                    <?php foreach ($userTasks as $task): ?>
                        <?php
                        // Calculando a cor do indicador com base na data limite
                        $hoje = date('Y-m-d');
                        $dataLimite = isset($task['data_limite']) ? $task['data_limite'] : null;
                        $corIndicador = 'bg-secondary'; // Default: cinza (caso não tenha data limite)

                        if ($dataLimite) {
                            $diasDiferenca = (strtotime($dataLimite) - strtotime($hoje)) / (60 * 60 * 24);

                            if ($diasDiferenca < 0) {
                                $corIndicador = 'bg-danger'; // Atrasada
                            } elseif ($diasDiferenca <= 1) {
                                $corIndicador = 'bg-warning'; // Próxima do prazo
                            } else {
                                $corIndicador = 'bg-success'; // Dentro do prazo
                            }
                        }
                        ?>
                        <div class="task-card border p-3 mb-3 rounded shadow-sm">
                            <!-- Indicador de cor baseado no status -->
                            <div class="d-flex align-items-center mb-2">
                                <div class="priority-indicator <?php echo $corIndicador; ?> rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <h5 class="mb-0">
                                    Tarefa - <?php echo htmlspecialchars($task['tipo_tarefa']); ?>
                                </h5>
                            </div>
                            <!-- Informações da Tarefa -->
                            <div class="text-muted mb-2">
                                <?php echo htmlspecialchars($task['descricao']); ?>
                            </div>
                            <ul class="list-unstyled">
                                <li><strong>Solicitante:</strong> <?php echo htmlspecialchars($task['solicitante']); ?></li>
                                <li><strong>Status:</strong> <?php echo ucfirst($task['status']); ?></li>
                                <li><strong>Data Designação:</strong> <?php echo date('d/m/Y H:i', strtotime($task['data_designacao'])); ?></li>
                                <?php if ($dataLimite): ?>
                                    <li><strong>Data Limite:</strong> <?php echo date('d/m/Y', strtotime($dataLimite)); ?></li>
                                <?php endif; ?>
                            </ul>
                            <!-- Ações -->
                            <div class="mt-3">
                                <?php if ($task['status'] === 'pendente'): ?>
                                    <a href="responder_tarefa.php?task_id=<?php echo $task['id']; ?>" class="btn btn-success btn-sm">Responder</a>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Arquivar</button>
                                </form>
                                <?php if ($task['status'] === 'respondida'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="close_task_id" value="<?php echo $task['id']; ?>">
                                        <button type="submit" class="btn btn-dark btn-sm">Encerrar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Você não tem tarefas pendentes.</p>
            <?php endif; ?>
        </div>


        
    </div>

    <?php include "require/footer.php";?>
