        // Função para mostrar/ocultar senha
        const togglePassword = document.getElementById('togglePassword');
        const senha = document.getElementById('senha');
        const confirmarSenha = document.getElementById('confirmar_senha');

        togglePassword.addEventListener('click', function () {
            const type = senha.type === 'password' ? 'text' : 'password';
            senha.type = type;
            confirmarSenha.type = type;
        });