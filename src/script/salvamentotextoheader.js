document.getElementById("saveDocx").addEventListener("click", function () {
    const title = document.getElementById("docTitle").value;
    const content = CKEDITOR.instances.docContent.getData();

    fetch("/save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, content, format: "docx" }),
    }).then(response => response.json()).then(data => {
        if (data.success) {
            window.location.href = data.file; // Baixar o arquivo
        }
    });
});

document.getElementById("savePdf").addEventListener("click", function () {
    const title = document.getElementById("docTitle").value;
    const content = CKEDITOR.instances.docContent.getData();

    fetch("/save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, content, format: "pdf" }),
    }).then(response => response.json()).then(data => {
        if (data.success) {
            window.location.href = data.file; // Baixar o arquivo
        }
    });
});
