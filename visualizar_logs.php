<?php
session_start();
include 'log_atividade.php'; // Inclui o script de log para garantir que as atividades sejam registradas

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$logsFile = 'json/logs.json';
$logs = json_decode(file_get_contents($logsFile), true);

include "require/header.php";
include "require/aside.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Logs</title>
    <!-- Inclusão do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Logs de Respostas</h2>

        <!-- Tabela de Logs -->
        <?php if (!empty($logs)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID da Tarefa</th>
                        <th>Advogado</th>
                        <th>Resposta</th>
                        <th>Data da Resposta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo isset($log['task_id']) ? $log['task_id'] : 'Não disponível'; ?></td>
                            <td><?php echo isset($log['advogado_id']) ? $log['advogado_id'] : 'Não disponível'; ?></td>
                            <td><?php echo isset($log['resposta']) ? $log['resposta'] : 'Não disponível'; ?></td>
                            <td><?php echo isset($log['data_resposta']) ? $log['data_resposta'] : 'Não disponível'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <!-- Modal para informar que não há logs -->
            <div class="modal fade" id="noLogsModal" tabindex="-1" aria-labelledby="noLogsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noLogsModalLabel">Sem Logs Disponíveis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Não há logs de respostas disponíveis para gerar o relatório.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Exibe o modal caso não haja logs
                var myModal = new bootstrap.Modal(document.getElementById('noLogsModal'), {
                    keyboard: false
                });
                myModal.show();
            </script>
        <?php endif; ?>

        <!-- Botão para gerar o PDF (disponível apenas se houver logs) -->
        <?php if (!empty($logs)): ?>
            <a href="download_pdf.php" class="btn btn-primary mt-3">Download PDF</a>
        <?php endif; ?>

        <!-- Botão para voltar à página inicial -->
        <a href="index.php" class="btn btn-secondary mt-3">Voltar à Página Inicial</a>
    </div>

    <!-- Inclusão do JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php include "require/footer.php";?>
</body>
</html>
