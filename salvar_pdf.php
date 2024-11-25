<?php
require('fpdf/fpdf.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados enviados
    $titulo = $_POST['titulo'] ?? 'Sem Título';
    $conteudo = $_POST['conteudo'] ?? 'Sem Conteúdo';

    // Cria o PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Adiciona o título
    $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
    $pdf->Ln(10);

    // Adiciona o conteúdo
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, utf8_decode($conteudo));

    // Salva o PDF no servidor ou envia para o usuário
    $filename = 'documento_' . time() . '.pdf';
    $pdf->Output('F', $filename); // Salva no servidor

    // Download automático
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Exclui o arquivo temporário do servidor
    unlink($filename);
    exit;
}
?>
