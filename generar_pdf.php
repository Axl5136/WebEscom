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
        $this->Image ('imgs/IPN-Logo.png', 10, 8, 25);

        $this->Image ('imgs/escudo_ESCOM.png', 175, 8, 25);

        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 8, utf8_decode('INSTITUTO POLITÉCNICO NACIONAL'), 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, utf8_decode('ESCUELA SUPERIOR DE CÓMPUTO'), 0, 1, 'C');

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
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddPage();

// Título del cuerpo
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 12, 'ACUSE DE REGISTRO DE ASPIRANTE', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, utf8_decode('Boleta:'), 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode($_SESSION['boleta']), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nombre completo:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode($_SESSION['nombre_completo']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Fecha de nacimiento:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $_SESSION['fecha_nacimiento'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Género:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($_SESSION['genero']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'CURP:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $_SESSION['curp'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Estado de procedencia:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($_SESSION['estado_procedencia']), 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Teléfono:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $_SESSION['telefono'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Escuela de procedencia:'), 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode($_SESSION['escuela_procedencia']), 0, 1);

if (!empty($_SESSION['nombre_escuela'])) {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(50, 8, utf8_decode('Nombre de la escuela:'), 0, 0);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 8, utf8_decode($_SESSION['nombre_escuela']), 0, 1);
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'Promedio:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $_SESSION['promedio'], 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, 'Correo institucional:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, $_SESSION['correo_institucional'], 0, 1);

$pdf->Ln(8);

// Grupo y Horario
$pdf->SetFillColor(230, 240, 255);
$pdf->Rect(10, $pdf->GetY(), 190, 45, 'F');

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 86, 179);

$pdf->Ln(4);
$laboratorios = [
    '1CM1' => 'Laboratorio 1',
    '1CM2' => 'Laboratorio 2',
    '1CM3' => 'Laboratorio 3',
    '1CM4' => 'Laboratorio 4',
    '1CM5' => 'Laboratorio 5',
];
$laboratorio = $laboratorios[$_SESSION['grupo_asignado']] ?? 'Por asignar';

$pdf->Cell(50, 10, utf8_decode('   Grupo asignado:'), 0, 0);
$pdf->Cell(0, 10, $_SESSION['grupo_asignado'], 0, 1);

$pdf->Cell(50, 10, utf8_decode('   Laboratorio:'), 0, 0);
$pdf->Cell(0, 10, $laboratorio, 0, 1);

$pdf->Cell(50, 10, utf8_decode('   Horario de examen:'), 0, 0);
$pdf->Cell(0, 10, $_SESSION['horario_examen'], 0, 1);

$pdf->Cell(50, 10, utf8_decode('   Duracion del examen:'), 0, 0);
$pdf->Cell(0, 10, '90 minutos', 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(15);

//Firma / validación
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode('Favor de presentar este acuse el día del examen para su validación.'), 0, 1, 'L');
$pdf->Cell(0, 6, utf8_decode('Favor de llegar con tiempo al laboratorio.'), 0, 1, 'L');
$pdf->Cell(0, 6, utf8_decode('Código de validación: ') . strtoupper(substr(md5($_SESSION['boleta'] . date('Ymd')), 0, 10)), 0, 1, 'L');
$pdf->Ln(15);

$pdf->Cell(90, 6, '_______________________', 0, 0, 'C');
$pdf->Cell(10, 6, '', 0, 0);
$pdf->Cell(90, 6, '_______________________', 0, 1, 'C');

$pdf->Cell(90, 6, 'Firma del Aspirante', 0, 0, 'C');
$pdf->Cell(10, 6, '', 0, 0);
$pdf->Cell(90, 6, utf8_decode('Sello / Autorización'), 0, 1, 'C');

$pdf->Output('I', $_SESSION['boleta'] . '.pdf');