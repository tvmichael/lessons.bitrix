<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("FPDF");
?>

<?

require($_SERVER["DOCUMENT_ROOT"].'/fpdf/fpdf.php');
//require('/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
