        // Função para carregar os usuários do arquivo JSON e popular o select
        fetch('json/usuarios.json')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('responsavel');
                // Limpar a lista atual de opções
                select.innerHTML = '';

                // Adicionar uma opção padrão
                const defaultOption = document.createElement('option');
                defaultOption.text = "Selecione um responsável";
                defaultOption.disabled = true;
                defaultOption.selected = true;
                select.appendChild(defaultOption);

                // Adicionar os usuários ao select
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.nome;
                    option.text = usuario.nome;
                    select.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erro ao carregar o arquivo JSON:', error);
            });