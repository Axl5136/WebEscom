<?php
session_start();

if (!isset($_SESSION['boleta'])) {
    exit('Acceso denegado');
}

require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        $this->Image('imgs/IPN-Logo.png', 10, 8, 25);

        $this->Image('imgs/escudo_ESCOM.png', 175, 8, 25);

        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 8, 'INSTITUTO POLITECNICO NACIONAL', 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, 'ESCUELA SUPERIOR DE COMPUTO', 0, 1, 'C');

        $this->Ln(8);

        // Línea separadora
        $this->SetDrawColor(0, 86, 179);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-25);
        $this->Image('imgs/Logo_Equipo.png', 90, $this->GetY(), 30);
        $this->Ln(15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddPage();

// Título del cuerpo
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 12, 'ACUSE DE REGISTRO DE ASPIRANTE', 0, 1, 'C');
$pdf->Ln(5);

// Datos del alumno
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Boleta:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $_SESSION['boleta'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nombre completo:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $_SESSION['nombre_completo'], 0, 1);

$pdf->Ln(8);

// Grupo y Horario
$pdf->SetFillColor(230, 240, 255);
$pdf->Rect(10, $pdf->GetY(), 190, 25, 'F');

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 86, 179);

$pdf->Ln(4);
$pdf->Cell(50, 10, '   Grupo asignado:', 0, 0);
$pdf->Cell(0, 10, $_SESSION['grupo_asignado'], 0, 1);

$pdf->Cell(50, 10, '   Horario de examen:', 0, 0);
$pdf->Cell(0, 10, $_SESSION['horario_examen'], 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(15);

//Firma / validación
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Codigo de validacion: ' . strtoupper(substr(md5($_SESSION['boleta'] . date('Ymd')), 0, 10)), 0, 1, 'L');
$pdf->Ln(15);

$pdf->Cell(90, 6, '_______________________', 0, 0, 'C');
$pdf->Cell(10, 6, '', 0, 0);
$pdf->Cell(90, 6, '_______________________', 0, 1, 'C');

$pdf->Cell(90, 6, 'Firma del Aspirante', 0, 0, 'C');
$pdf->Cell(10, 6, '', 0, 0);
$pdf->Cell(90, 6, 'Sello / Autorizacion', 0, 1, 'C');

$pdf->Output('I', 'Acuse_Registro.pdf');