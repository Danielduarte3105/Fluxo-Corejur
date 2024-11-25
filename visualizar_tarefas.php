<?php
session_start();
include 'log_atividade.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$tasksFile = 'json/tasks.json';
$tasks = json_decode(file_get_contents($tasksFile), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desarquivar_id'])) {
    // Desarquivar a tarefa
    $taskId = $_POST['desarquivar_id'];
    foreach ($tasks as &$task) {
        if ($task['id'] == $taskId && $task['status'] === 'arquivada') {
            $task['status'] = 'pendente'; // Mudar o status para pendente
            break;
        }
    }

    // Salvar as alterações no arquivo JSON
    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

    // Redirecionar para a página inicial
    $_SESSION['mensagem'] = 'Tarefa desarquivada com sucesso!';
    header('Location: index.php');
    exit;

}

include "require/header.php";
include "require/aside.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tarefas</title>
    <!-- Inclusão do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tarefas</h2>

        <!-- Tarefas Pendentes -->
        <h3 class="text-primary">Tarefas Pendentes</h3>
        <?php
        $pendentes = array_filter($tasks, function($task) {
            return $task['status'] === 'pendente';
        });

        if (!empty($pendentes)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID da Tarefa</th>
                        <th>Usuário</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Data de Designação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendentes as $task): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['responsavel']); ?></td>
                            <td><?php echo htmlspecialchars($task['descricao']); ?></td>
                            <td><?php echo ucfirst($task['status']); ?></td>
                            <td><?php echo $task['data_designacao']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Não há tarefas pendentes no momento.</p>
        <?php endif; ?>

        <!-- Tarefas Respondidas -->
        <h3 class="text-success mt-5">Tarefas Respondidas</h3>
        <?php
        $respondidas = array_filter($tasks, function($task) {
            return $task['status'] === 'respondida';
        });

        if (!empty($respondidas)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID da Tarefa</th>
                        <th>Usuário</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Data de Designação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($respondidas as $task): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['descricao']); ?></td>
                            <td><?php echo ucfirst($task['status']); ?></td>
                            <td><?php echo $task['data_designacao']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Não há tarefas respondidas no momento.</p>
        <?php endif; ?>

        <!-- Tarefas Arquivadas -->
        <h3 class="text-secondary mt-5">Tarefas Arquivadas</h3>
        <?php
        $arquivadas = array_filter($tasks, function($task) {
            return $task['status'] === 'arquivada';
        });

        if (!empty($arquivadas)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID da Tarefa</th>
                        <th>Usuário</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Data de Designação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivadas as $task): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['descricao']); ?></td>
                            <td><?php echo ucfirst($task['status']); ?></td>
                            <td><?php echo $task['data_designacao']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="desarquivar_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn btn-warning btn-sm">Desarquivar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Não há tarefas arquivadas no momento.</p>
        <?php endif; ?>


        <!-- Tarefas Encerradas -->
        <h3 class="text-danger mt-5">Tarefas Encerradas</h3>
        <?php
        $encerradas = array_filter($tasks, function($task) {
            return $task['status'] === 'encerrada';
        });

        if (!empty($encerradas)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID da Tarefa</th>
                        <th>Usuário</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Data de Designação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($encerradas as $task): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['descricao']); ?></td>
                            <td><?php echo ucfirst($task['status']); ?></td>
                            <td><?php echo $task['data_designacao']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Não há tarefas encerradas no momento.</p>
        <?php endif; ?>

        <!-- Botão para voltar à página inicial -->
        <a href="index.php" class="btn btn-secondary mt-4">Voltar à Página Inicial</a>
    </div>

    <!-- Inclusão do JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php include "require/footer.php";?>
</body>
</html>