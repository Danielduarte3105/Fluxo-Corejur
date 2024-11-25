<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/header.css">
    <link rel="stylesheet" href="/src/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #0064cc; color: #fff;">
        <div class="container-fluid">
            <!-- Botão de menu lateral -->
            <button id="toggleMenu" class="btn btn-link text-white me-3" type="button">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <!-- Logotipo e Nome -->
            <a class="navbar-brand text-white" href="#">
                Sylvio Sacramento Fernandes
            </a>

            <!-- Ícones do lado direito -->
            <div class="d-flex align-items-center ms-auto">
                <!-- Botão de pesquisa -->
                <button id="search" class="btn btn-link text-white me-2">
                    <i class="fas fa-search fa-lg"></i>
                </button>
                <!-- Campo de busca -->
                <input type="text" id="searchInput" class="form-control me-2" placeholder="Pesquisar..." style="display:none;" />

                <!-- Modal do Calendário -->
                <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="calendarModalLabel">Selecione Data Para Lançar Tarefa futura</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="calendar-container">
                                    <div class="calendar-header d-flex justify-content-between align-items-center">
                                        <button class="btn btn-sm btn-outline-primary" id="prevMonth">←</button>
                                        <h6 id="calendarMonthYear"></h6>
                                        <button class="btn btn-sm btn-outline-primary" id="nextMonth">→</button>
                                    </div>
                                    <div class="calendar-weekdays">
                                        <div>Dom</div>
                                        <div>Seg</div>
                                        <div>Ter</div>
                                        <div>Qua</div>
                                        <div>Qui</div>
                                        <div>Sex</div>
                                        <div>Sáb</div>
                                    </div>

                                    <div id="calendarDays" class="calendar-days"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão do calendário -->
                <button id="calendar" class="btn btn-link text-white me-2" data-bs-toggle="modal" data-bs-target="#calendarModal">
                    <i class="fas fa-calendar-alt fa-lg"></i>
                </button>

                <!-- Botão do Editor de Texto -->
                <button id="archive" class="btn btn-link text-white me-2">
                    <i class="fas fa-file-alt fa-lg"></i>
                </button>

                <!-- Modal do Editor de Texto -->
                <div class="modal fade" id="editorModal" tabindex="-1" aria-labelledby="editorModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do Modal -->
                            <div class="modal-header" style="background-color: #0064cc; color: #fff;">
                                <h4 class="modal-title" id="editorModalLabel">Criar Documento</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #fff;"></button>
                            </div>

                            <!-- Corpo do Modal -->
                            <div class="modal-body">
                            <form id="editorForm" action="salvar_pdf.php" method="POST">
                                <!-- Título -->
                                <div class="mb-3">
                                    <label for="docTitle" class="form-label fw-bold" style="font-size: 1.2rem;">Título do Documento</label>
                                    <input type="text" class="form-control form-control-lg" id="docTitle" name="titulo" placeholder="Digite o título aqui" style="border-radius: 8px;">
                                </div>

                                <!-- Conteúdo -->
                                <div class="mb-3">
                                    <label for="docContent" class="form-label fw-bold" style="font-size: 1.2rem;">Conteúdo do Documento</label>
                                    <textarea id="docContent" class="form-control" rows="15" name="conteudo" placeholder="Escreva o conteúdo do documento aqui..." style="border-radius: 8px;"></textarea>
                                </div>

                                <!-- Botão para salvar -->
                                <div class="modal-footer" style="border-top: none; justify-content: center;">
                                    <button id="savePdf" class="btn btn-success btn-lg" style="border-radius: 8px; width: 50%;">Salvar como PDF</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </nav>

    <script src="/src/script/menulateralheader.js"></script>
    <script src="/src/script/designardataheader.js"></script>
    <script src="/src/script/filtrodebuscaheader.js"></script>
    <script src="/src/script/salvamentotextoheader.js"></script>
    <script src="/src/script/header.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const archiveButton = document.getElementById("archive");
    const editorModal = new bootstrap.Modal(document.getElementById("editorModal"));

    if (archiveButton) {
        archiveButton.addEventListener("click", function () {
            editorModal.show();
        });
    }
    });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            CKEDITOR.replace("docContent", {
                height: 400,
                toolbar: [
                    { name: "basicstyles", items: ["Bold", "Italic", "Underline", "Strike"] },
                    { name: "paragraph", items: ["NumberedList", "BulletedList", "-", "Outdent", "Indent"] },
                    { name: "styles", items: ["Format", "Font", "FontSize"] },
                    { name: "colors", items: ["TextColor", "BGColor"] },
                    { name: "tools", items: ["Maximize"] },
                ],
            });
        });
    </script>

</body>
</html>
