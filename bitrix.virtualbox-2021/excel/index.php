<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Title");

//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);
?>
<?
//echo 'Start:' . '<br>';


$PHPEXCELPATH = '../local/modules/PHPExcel-S/Classes';
// ���������� ����� ��� ������ � excel
require_once($PHPEXCELPATH.'/PHPExcel.php');
// ���������� ����� ��� ������ ������ � ������� excel
require_once($PHPEXCELPATH.'/PHPExcel/Writer/Excel5.php');

// ������� ������ ������ PHPExcel
$xls = new PHPExcel();
// ������������� ������ ��������� �����
$xls->setActiveSheetIndex(0);
// �������� �������� ����
$sheet = $xls->getActiveSheet();
// ����������� ����
$sheet->setTitle('������� ���������');

// ��������� ����� � ������ A1
$sheet->setCellValue("A1", '������� ��������� mbstring.func_overload='. ini_get('mbstring.func_overload'));
$sheet->getStyle('A1')->getFill()->setFillType(
    PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// ���������� ������
$sheet->mergeCells('A1:H1');

// ������������ ������
$sheet->getStyle('A1')->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

for ($i = 2; $i < 20; $i++) {
    for ($j = 2; $j < 10; $j++) {
        // ������� ������� ���������
        $sheet->setCellValueByColumnAndRow(
            $i - 2,
            $j,
            $i . "x" .$j . "=" . ($i*$j));
        // ��������� ������������
        $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
}

// ������� HTTP-���������
header ( "Pragma: no-cache" );
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=matrix.xls" );

// ������� ���������� �����
$objWriter = new PHPExcel_Writer_Excel5($xls);
//$objWriter->save('matrix.xlsx');
$objWriter->save('php://output');


//echo 'End:'.'<br>';

?>


<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>