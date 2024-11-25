<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function registrar_log($action) {
    $logFile = 'json/logs.json';
    
    // Verifica se o arquivo de logs já existe
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true);
    } else {
        $logs = [];
    }

    // Cria o log com dados da sessão
    $logEntry = [
        'task_id' => isset($_GET['task_id']) ? $_GET['task_id'] : 'Não informado',
        'advogado_id' => $_SESSION['usuario'],
        'resposta' => $action,
        'data_resposta' => date('Y-m-d H:i:s')
    ];

    // Adiciona o novo log ao array
    $logs[] = $logEntry;

    // Salva os logs no arquivo
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

// Chame a função registrar_log onde você quiser registrar a atividade, por exemplo, após responder uma tarefa
if (isset($_GET['task_id']) && isset($_GET['resposta'])) {
    registrar_log($_GET['resposta']);
}
?>
