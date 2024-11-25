document.getElementById('search').addEventListener('click', function() {
    const searchInput = document.getElementById('searchInput');
    
    // Alterna a visibilidade do campo de pesquisa
    if (searchInput.style.display === 'none' || searchInput.style.display === '') {
        searchInput.style.display = 'inline-block'; // Exibe o campo de pesquisa
        searchInput.focus(); // Foca no campo de pesquisa
    } else {
        searchInput.style.display = 'none'; // Esconde o campo de pesquisa
        clearTimeout(window.searchTimeout); // Limpa qualquer timeout anterior
    }
});

// Adiciona o evento para realizar a busca ao digitar no campo
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.trim(); // Pega o texto digitado

    // Remove qualquer filtro e destaque anterior
    const highlightedItems = document.querySelectorAll('.highlight');
    highlightedItems.forEach(item => {
        item.classList.remove('highlight');
        item.style.backgroundColor = ''; // Remove o fundo de destaque
    });

    if (searchTerm) {
        // Realiza a busca e destaca os itens encontrados
        const bodyText = document.body.innerHTML;
        const regex = new RegExp(`(${searchTerm})`, 'gi'); // Cria um regex para a busca exata, sem diferenciação de maiúsculas/minúsculas
        const matches = bodyText.match(regex);

        if (matches) {
            // Marca os itens encontrados
            const elements = document.body.querySelectorAll('*');
            elements.forEach(element => {
                if (element.children.length === 0 && regex.test(element.textContent)) { // Verifica se o elemento não tem filhos
                    element.classList.add('highlight');
                    element.style.backgroundColor = 'yellow'; // Destaca o fundo
                }
            });
        } else {
            alert('Nenhum item encontrado.');
        }

        // Reinicia o timeout para ocultar o campo após 1 segundo sem texto
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            searchInput.style.display = 'none'; // Esconde o campo de pesquisa após 1 segundo sem digitação
        }, 1000);
    } else {
        // Se o campo estiver vazio, reinicia o timeout para esconder a barra
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            searchInput.style.display = 'none'; // Esconde o campo de pesquisa
        }, 1000);
    }
});
