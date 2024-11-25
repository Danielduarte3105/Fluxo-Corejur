<?php
session_start();
include 'log_atividade.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o ID da tarefa foi passado via GET
$task_id = isset($_GET['task_id']) ? $_GET['task_id'] : '';

$tasksFile = 'json/tasks.json';  // Definindo a variável $tasksFile
$logsFile = 'json/logs.json';

// Verifica se o arquivo de tarefas existe e contém dados
if (file_exists($tasksFile)) {
    $tasks = json_decode(file_get_contents($tasksFile), true);
} else {
    $tasks = [];  // Se o arquivo não existir, cria um array vazio
}

// Verifica se o arquivo de logs existe e contém dados
if (file_exists($logsFile)) {
    $logs = json_decode(file_get_contents($logsFile), true);
} else {
    $logs = [];  // Se o arquivo não existir, cria um array vazio
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $advogado_id = $_SESSION['usuario'];  // O ID do advogado será o nome de usuário
    $resposta = $_POST['resposta'];
    $arquivos = $_FILES['arquivos'];

    // Lógica para processar o arquivo anexo
    if ($arquivos['error'] === 0) {
        $diretorio = 'uploads/';
        $arquivo_nome = basename($arquivos['name']);
        $caminho_arquivo = $diretorio . $arquivo_nome;

        if (move_uploaded_file($arquivos['tmp_name'], $caminho_arquivo)) {
            echo "Arquivo enviado com sucesso!";
        } else {
            echo "Erro ao enviar o arquivo.";
        }
    }

    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['status'] = 'respondida';
            $task['data_resposta'] = date('Y-m-d H:i:s');
            break;
        }
    }

    $log = [
        'task_id' => $task_id,
        'advogado_id' => $advogado_id,
        'resposta' => $resposta,
        'data_resposta' => date('Y-m-d H:i:s'),
    ];

    $logs[] = $log;
    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    file_put_contents($logsFile, json_encode($logs, JSON_PRETTY_PRINT));

    echo "<div class='alert alert-success mt-3'>Resposta registrada com sucesso!</div>";
}

include "require/header.php";
include "require/aside.php";

// Buscar o nome do solicitante
$solicitante_nome = '';
if (!empty($task_id) && file_exists($tasksFile)) {
    foreach ($tasks as $task) {
        if ($task['id'] === $task_id) {
            $solicitante_nome = $task['solicitante'];  // Assumindo que o nome do solicitante está no campo 'solicitante'
            break;
        }
    }
}
?>

<div class="container py-1">
    <h2>Responder Tarefa</h2>

    <!-- Formulário para responder à tarefa -->
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="task_id" class="form-label">ID da Tarefa:</label>
            <input type="text" class="form-control" id="task_id" name="task_id" value="<?php echo htmlspecialchars($task_id); ?>" required readonly>
        </div>

        <div class="mb-3">
            <label for="solicitante" class="form-label">Nome do Solicitante:</label>
            <input type="text" class="form-control" id="solicitante" name="solicitante" value="<?php echo htmlspecialchars($solicitante_nome); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="data_limite" class="form-label">Data Limite:</label>
            <input type="text" class="form-control" id="data_limite" name="data_limite" value="26/11/2024" readonly>
        </div>

        <div class="mb-3">
            <label for="instrucoes" class="form-label">Instruções:</label>
            <textarea class="form-control" id="instrucoes" name="instrucoes" rows="1" readonly>Teste</textarea>
        </div>

        <div class="mb-3">
            <label for="resposta" class="form-label">Resposta:</label>
            <textarea class="form-control" id="resposta" name="resposta" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="arquivos" class="form-label">Anexar Arquivos:</label>
            <input type="file" class="form-control" id="arquivos" name="arquivos">
        </div>

        <button type="submit" class="btn btn-primary">Responder Tarefa</button>
    </form>

    <a href="index.php" class="btn btn-secondary mt-3">Voltar à Página Inicial</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php include "require/footer.php";?>
</body>
</html>
