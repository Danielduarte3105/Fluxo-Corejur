        
// Abrir e fechar a barra de busca
const searchToggle = document.getElementById('search-toggle');
const searchBar = document.getElementById('search-bar');
const searchInput = document.getElementById('search-input');

searchToggle.addEventListener('click', () => {
    if (searchBar.style.display === 'none' || searchBar.style.display === '') {
        searchBar.style.display = 'block';
        searchInput.focus(); // Foca automaticamente na barra de pesquisa
    } else {
        searchBar.style.display = 'none';
    }
});

// Buscar e destacar resultados
searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    const items = document.querySelectorAll('body *:not(script):not(style):not(#search-bar)'); // Seleciona todos os elementos visíveis no corpo

    items.forEach(item => {
        const text = item.textContent || item.innerText;
        if (text && text.toLowerCase().includes(query)) {
            item.classList.add('highlight'); // Adiciona destaque
        } else {
            item.classList.remove('highlight'); // Remove o destaque
        }
    });
});

