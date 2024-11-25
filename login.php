<?php
session_start();

include 'log_atividade.php';

// Função de autenticação
function autenticarUsuario($nome, $senha) {
    $usuariosFile = 'json/usuarios.json';
    $usuarios = json_decode(file_get_contents($usuariosFile), true);

    foreach ($usuarios as $user) {
        if ($user['nome'] == $nome && password_verify($senha, $user['senha'])) {
            return true;
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['usuario'];
    $senha = $_POST['senha'];

    if (autenticarUsuario($nome, $senha)) {
        $_SESSION['usuario'] = $nome;
        header('Location: index.php');
        exit;
    } else {
        echo "<div class='alert alert-danger text-center mt-2'>Usuário ou senha incorretos!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFA - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <img src="/src/img/logo-sfa.png" alt="LOGO SFA" style="max-width: 260px;" class="mb-2">
            </div>
            <form method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Login</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="senha" name="senha" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <div class="text-center mt-3">
                <a href="mailto:daniel.silva@sfa.adv.br?subject=Não consigo acessar o painel de tarefas SFA&body=Erro ao acessar o painel de tarefas SFA." 
                   class="text-decoration-none">
                   Precisa de ajuda? Clique aqui
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('senha');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</body>
</html>
