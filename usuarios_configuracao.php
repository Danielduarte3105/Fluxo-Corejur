<?php
session_start();
include 'log_atividade.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuariosFile = 'json/usuarios.json';
$usuarios = json_decode(file_get_contents($usuariosFile), true);

// Função para atualizar os dados de um usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar usuário
    if (isset($_POST['editar_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
        $novo_nome = $_POST['nome'];
        $novo_andar = $_POST['andar'];
        $novo_grupo = $_POST['grupo'];
        $novo_acesso = $_POST['acesso'];
        $novo_email = $_POST['email'];
        $nova_senha = $_POST['senha'] ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : '';

        foreach ($usuarios as &$usuario) {
            if ($usuario['email'] === $id_usuario) {
                $usuario['nome'] = $novo_nome;
                $usuario['andar'] = $novo_andar;
                $usuario['grupo'] = $novo_grupo;
                $usuario['acesso'] = $novo_acesso;
                $usuario['email'] = $novo_email;
                if ($nova_senha) {
                    $usuario['senha'] = $nova_senha;
                }
                break;
            }
        }

        file_put_contents($usuariosFile, json_encode($usuarios, JSON_PRETTY_PRINT));
        $_SESSION['mensagem'] = "Usuário atualizado com sucesso!";
        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit;
    }

    // Excluir usuário
    if (isset($_POST['delete_email'])) {
        $emailExcluir = $_POST['delete_email'];

        // Filtrar usuários, removendo o que possui o email que corresponde ao de exclusão
        $usuarios = array_filter($usuarios, function($usuario) use ($emailExcluir) {
            return $usuario['email'] !== $emailExcluir;
        });

        // Reindexar o array para evitar índices não contíguos
        $usuarios = array_values($usuarios);

        file_put_contents($usuariosFile, json_encode($usuarios, JSON_PRETTY_PRINT));
        $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
        
        // Redirecionar para a página atual
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

}

// Obter dados do usuário a ser editado
$usuarioEditar = null;
if (isset($_GET['email'])) {
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $_GET['email']) {
            $usuarioEditar = $usuario;
            break;
        }
    }
}

include "require/header.php";
include "require/aside.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Manutenção de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manutenção de Usuários</h2>

        <?php
        if (isset($_SESSION['mensagem'])) {
            echo '<div id="mensagem" class="alert alert-success">' . $_SESSION['mensagem'] . '</div>';
            unset($_SESSION['mensagem']);
        }
        ?>

        <script>
            setTimeout(function() {
                const mensagem = document.getElementById('mensagem');
                if (mensagem) {
                    mensagem.style.display = 'none';
                }
            }, 5000);
        </script>

        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">Voltar ao Painel</a>
            <a href="cadastro_usuario.php" class="btn btn-success">Cadastrar Usuário</a>
        </div>

        <?php if ($usuarioEditar): ?>
            <h3>Editando: <?php echo htmlspecialchars($usuarioEditar['nome']); ?></h3>
            <form method="POST">
                <input type="hidden" name="id_usuario" value="<?php echo $usuarioEditar['email']; ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['nome']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="andar" class="form-label">Andar</label>
                    <select class="form-select" id="andar" name="andar" required>
                        <option value="" disabled>Selecione o andar</option>
                        <option value="RJ" <?php echo ($usuarioEditar['andar'] === 'RJ') ? 'selected' : ''; ?>>RJ</option>
                        <option value="10º" <?php echo ($usuarioEditar['andar'] === '10º') ? 'selected' : ''; ?>>10º</option>
                        <option value="11º" <?php echo ($usuarioEditar['andar'] === '11º') ? 'selected' : ''; ?>>11º</option>
                        <option value="12º" <?php echo ($usuarioEditar['andar'] === '12º') ? 'selected' : ''; ?>>12º</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="grupo" class="form-label">Grupo</label>
                    <select class="form-select" id="grupo" name="grupo" required>
                        <option value="" disabled>Selecione um grupo</option>
                        <option value="GI" <?php echo ($usuarioEditar['grupo'] === 'GI') ? 'selected' : ''; ?>>GI</option>
                        <option value="GII" <?php echo ($usuarioEditar['grupo'] === 'GII') ? 'selected' : ''; ?>>GII</option>
                        <option value="GIII" <?php echo ($usuarioEditar['grupo'] === 'GIII') ? 'selected' : ''; ?>>GIII</option>
                        <option value="GIV" <?php echo ($usuarioEditar['grupo'] === 'GIV') ? 'selected' : ''; ?>>GIV</option>
                        <option value="GRJ" <?php echo ($usuarioEditar['grupo'] === 'GRJ') ? 'selected' : ''; ?>>GRJ</option>
                        <option value="Estagiários" <?php echo ($usuarioEditar['grupo'] === 'Estagiários') ? 'selected' : ''; ?>>Estagiários</option>
                        <option value="Administrativo" <?php echo ($usuarioEditar['grupo'] === 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                        <option value="Sócios" <?php echo ($usuarioEditar['grupo'] === 'Sócios') ? 'selected' : ''; ?>>Sócios</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="acesso" class="form-label">Acesso</label>
                    <input type="text" name="acesso" id="acesso" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['acesso']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($usuarioEditar['email']); ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha (opcional)</label>
                    <input type="password" name="senha" id="senha" class="form-control" placeholder="Deixe em branco para manter a senha atual">
                </div>

                <button type="submit" name="editar_usuario" class="btn btn-primary">Salvar Alterações</button>
            </form>
        <?php else: ?>
            <p>Usuário não encontrado.</p>
        <?php endif; ?>

        <hr>

        <h3>Usuários Cadastrados</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <a href="?email=<?php echo urlencode($usuario['email']); ?>" class="btn btn-warning btn-sm">Editar</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_email" value="<?php echo $usuario['email']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Você tem certeza que deseja excluir?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include "require/footer.php";?>
</body>
</html>
