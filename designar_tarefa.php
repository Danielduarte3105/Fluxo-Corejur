<?php
    session_start();
    include 'log_atividade.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }

    // Carregar os advogados cadastrados no sistema
    $usuariosFile = 'json/usuarios.json';
    $usuarios = json_decode(file_get_contents($usuariosFile), true) ?: [];

    // Verificar o envio do formulário
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descricao = $_POST['descricao'];
        $responsavel = $_POST['responsavel'] ?? ''; // Pegando o responsável se existir
        $tipo_tarefa = $_POST['tipo_tarefa']; // Capturando o tipo de tarefa do formulário
        $data_limite = $_POST['data_limite']; // Capturando a data limite do formulário

        // Carregar o arquivo de tarefas
        $tasksFile = 'json/tasks.json';
        if (!file_exists($tasksFile)) {
            file_put_contents($tasksFile, json_encode([], JSON_PRETTY_PRINT));
        }
        $tasks = json_decode(file_get_contents($tasksFile), true) ?: [];

        // Criar a nova tarefa
        $task = [
            'id' => uniqid(),
            'descricao' => $descricao,
            'responsavel' => $responsavel, // Responsável adicionado à tarefa
            'solicitante' => $_SESSION['usuario'], // Adiciona o solicitante com o nome do usuário logado
            'status' => 'pendente',
            'data_designacao' => date('Y-m-d H:i:s'),
            'tipo_tarefa' => $tipo_tarefa, // Adicionando o tipo de tarefa
            'data_limite' => $data_limite, // Adicionando a data limite
        ];

        // Adicionar a tarefa ao arquivo
        $tasks[] = $task;
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

        // Exibir mensagem de sucesso
        echo "<div class='alert alert-success mt-3'>Tarefa designada com sucesso!</div>";
    }

    include "require/header.php";
    include "require/aside.php";
?>


<?php
// Verificar se uma data limite foi passada via GET
$data_limite = isset($_GET['data_limite']) ? $_GET['data_limite'] : '';
?>


<div class="container">
    <h1 class="text-center mb-4">Lançar Tarefa</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">

                <!-- Tarefa -->
                <div class="form-section">
                    <h4>Tarefa</h4>
                    <div class="mb-3">
                        <label for="tipo_tarefa" class="form-label">Tipo de Tarefa</label>
                        <select class="form-select" id="tipo_tarefa" name="tipo_tarefa" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="Suporte TI">Suporte TI</option>
                            <option value="Manutenção">Manutenção</option>
                            <option value="Sistemas">Sistemas</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="data_limite" class="form-label">Data Limite</label>
                        <input type="date" class="form-control" id="data_limite" name="data_limite" required value="<?php echo htmlspecialchars($data_limite); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Instruções</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Equipe -->
                <div class="form-section">
                    <h4>Equipe</h4>
                    <div class="mb-3">
                        <label class="form-label">Definir Responsável</label>
                        <input type="radio" class="btn-check" name="responsavel" id="unico_responsavel" value="unico" checked>
                        <label class="btn btn-outline-primary" for="unico_responsavel">Único Responsável</label>
                    </div>
                    <div class="mb-3">
                        <label for="responsavel" class="form-label">Responsável</label>
                        <select class="form-select" id="responsavel" name="responsavel">
                            <!-- Lista de responsáveis será carregada aqui -->
                        </select>
                    </div>
                </div>

                <!-- Botões -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Lançar Tarefa</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="/src/script/designarTarefas.js"></script>

<?php include 'require/footer.php'?>
