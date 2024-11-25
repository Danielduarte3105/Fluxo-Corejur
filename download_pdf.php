<?php
// Incluir a biblioteca FPDF
include 'fpdf/fpdf.php';

// Caminho do arquivo de logs
$logsFile = 'json/logs.json';
$logs = json_decode(file_get_contents($logsFile), true);

// Verifica se há logs para gerar o PDF
if (empty($logs)) {
    echo "Não há logs para gerar o PDF.";
    exit;
}

// Criação do PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(200, 10, 'Logs de Respostas', 0, 1, 'C');
$pdf->Ln(10);

// Cabeçalho do PDF
$pdf->Cell(40, 10, 'ID da Tarefa', 1);
$pdf->Cell(40, 10, 'Advogado', 1);
$pdf->Cell(60, 10, 'Resposta', 1);
$pdf->Cell(50, 10, 'Data da Resposta', 1);
$pdf->Ln();

// Adiciona cada log ao PDF
foreach ($logs as $log) {
    $pdf->Cell(40, 10, isset($log['task_id']) ? $log['task_id'] : 'Não disponível', 1);
    $pdf->Cell(40, 10, isset($log['advogado_id']) ? $log['advogado_id'] : 'Não disponível', 1);
    $pdf->Cell(60, 10, isset($log['resposta']) ? $log['resposta'] : 'Não disponível', 1);
    $pdf->Cell(50, 10, isset($log['data_resposta']) ? $log['data_resposta'] : 'Não disponível', 1);
    $pdf->Ln();
}

// Forçar o download do PDF
$pdf->Output('D', 'logs.pdf');
?>
