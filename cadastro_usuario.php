<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $andar = $_POST['andar'];
    $grupo = $_POST['grupo'];
    $acesso = $_POST['acesso'];
    $email = $_POST['email'];
    
    // Verifica se as senhas coincidem
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        echo "<div class='alert alert-danger'>As senhas não coincidem!</div>";
    } else {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $usuariosFile = 'json/usuarios.json';
        $usuarios = json_decode(file_get_contents($usuariosFile), true);

        $novoUsuario = [
            "nome" => $nome,
            "andar" => $andar,
            "grupo" => $grupo,
            "acesso" => $acesso,
            "email" => $email,
            "senha" => $senha
        ];

        $usuarios[] = $novoUsuario;
        file_put_contents($usuariosFile, json_encode($usuarios, JSON_PRETTY_PRINT));

        echo "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
    // Adiciona um delay de 3 segundos antes de redirecionar
    header("refresh:3;url=index.php");
    exit();
}


include "require/header.php";
include "require/aside.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Advogado</title>
    <!-- Inclusão do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Cadastro de Usuário</h2>


                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="andar" class="form-label">Andar:</label>
                        <select class="form-select" id="andar" name="andar" required>
                            <option value="" disabled selected>Selecione o andar</option>
                            <option value="RJ">RJ</option>
                            <option value="10º">10º</option>
                            <option value="11º">11º</option>
                            <option value="12º">12º</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grupo" class="form-label">Grupo:</label>
                        <select class="form-select" id="grupo" name="grupo" required>
                            <option value="" disabled selected>Selecione um grupo</option>
                            <option value="GI">GI</option>
                            <option value="GII">GII</option>
                            <option value="GIII">GIII</option>
                            <option value="GIV">GIV</option>
                            <option value="GRJ">GRJ</option>
                            <option value="Estagiários">Estagiários</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Sócios">Sócios</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="acesso" class="form-label">Acesso:</label>
                        <select class="form-select" id="acesso" name="acesso" required>
                            <option value="restrito">Restrito</option>
                            <option value="irrestrito">Irrestrito</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3 password-container">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                        <i class="eye-icon" id="togglePassword">&#128065;</i>
                    </div>
                    <div class="mb-3 password-container">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="index.php" class="btn btn-secondary w-100">Voltar para Home</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusão do JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/script/cadastroUsuario.js"></script>
            
    <?php include "require/footer.php";?>

</body>
</html>
