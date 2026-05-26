<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {
	
	public function ReportHeader(  )
	{
		$this->Image('images/ctm logo.jpg', 0, 15, 35, 35, 'JPG', '', '', false, 150, 'L', false, false, 0, false, false, false);
		$this->SetFont('', 'B', 15);
		$this->Write( 0, 'Reporte de alertas y notificaciones', '', 0, 'C', true, 0, false, false, 0 );
		$this->Write( 0, '2016-02-22', '', 0, 'C', true, 0, false, false, 0 );
		$this->Ln();
		$this->Ln();
		$this->SetFont('', 'B', 13);
		$this->Cell( 40, 6, 'Ruta: ', '', 0, 'L', 0);
		$this->SetFont('', '', 13);
		$this->Cell( 70, 6, '11', '', 0, 'L', 0);
		$this->Ln();
		$this->SetFont('', 'B', 13);
		$this->Cell( 40, 6, 'Unidad: ', '', 0, 'L', 0);
		$this->SetFont('', '', 13);
		$this->Cell( 70, 6, '1', '', 0, 'L', 0);
		$this->Ln();
		$this->SetFont('', 'B', 13);
		$this->Cell( 40, 6, 'Operador: ', '', 0, 'L', 0);
		$this->SetFont('', '', 13);
		$this->Cell( 70, 6, 'Francisco Marquez Gutierrez', '', 0, 'L', 0);
		$this->Ln();
		$this->SetFont('', '', 12);
	}
	// Load table data from file
	public function LoadData($file) {
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line) {
			$data[] = explode(';', chop($line));
		}
		return $data;
	}

	// Colored table
	public function ColoredTable($header,$data) {
		// Colors, line width and bold font
		$this->SetFillColor(0, 128, 128);
		$this->SetTextColor(255);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$w = array(45, 45, 45, 45);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(166, 166, 166);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach($data as $row) {
			$this->Cell($w[0], 6, utf8_encode( $row[0] ), 'LR', 0, 'C', $fill);
			$this->Cell($w[1], 6, utf8_encode( $row[1] ), 'LR', 0, 'C', $fill);
			$this->Cell($w[2], 6, utf8_encode( $row[2] ), 'LR', 0, 'C', $fill);
			$this->Cell($w[3], 6, utf8_encode( $row[3] ), 'LR', 0, 'C', $fill);
			//$this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
			//$this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 011', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//	require_once(dirname(__FILE__).'/lang/eng.php');
//	$pdf->setLanguageArray($l);
//}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

$pdf->ReportHeader();

// column titles
$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');

// data loading
$data = $pdf->LoadData('data/table_data_demo.txt');

$data1 = [
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' ),
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' ),
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' ),
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' ),
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' ),
	array( 'azul', 'verde', '00:02:15', 'rosa' ),
	array( 'naranja', 'café', 789, 'violeta' )
				];

// set some text to print
$txt = <<<EOD
TCPDF Example 002

Default page header and footer are disabled using setPrintHeader() and setPrintFooter() methods.
EOD;

$salida = 'Hora de salida editada por checador a las YYYY:MM:DD HH:ii:ss';

$llegada = 'Hora de llegada editada por checador a las YYYY:MM:DD HH:ii:ss';

$pdf->Ln();
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
for( $i = 0; $i < 20; $i++ )
{
	// print colored table
	$pdf->ColoredTable($header, $data1);
	$pdf->Ln();
	$pdf->Write(0, $salida, '', 0, 'C', true, 0, false, false, 0);
	//$pdf->Ln();
	$pdf->Write(0, $llegada, '', 0, 'C', true, 0, false, false, 0);
	$pdf->Ln();
}


// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
