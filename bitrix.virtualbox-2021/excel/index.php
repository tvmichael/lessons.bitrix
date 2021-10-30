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
// Подключаем класс для работы с excel
require_once($PHPEXCELPATH.'/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once($PHPEXCELPATH.'/PHPExcel/Writer/Excel5.php');

// Создаем объект класса PHPExcel
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
$sheet->setTitle('Таблица умножения');

// Вставляем текст в ячейку A1
$sheet->setCellValue("A1", 'Таблица умножения mbstring.func_overload='. ini_get('mbstring.func_overload'));
$sheet->getStyle('A1')->getFill()->setFillType(
    PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
$sheet->mergeCells('A1:H1');

// Выравнивание текста
$sheet->getStyle('A1')->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

for ($i = 2; $i < 20; $i++) {
    for ($j = 2; $j < 10; $j++) {
        // Выводим таблицу умножения
        $sheet->setCellValueByColumnAndRow(
            $i - 2,
            $j,
            $i . "x" .$j . "=" . ($i*$j));
        // Применяем выравнивание
        $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
}

// Выводим HTTP-заголовки
header ( "Pragma: no-cache" );
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=matrix.xls" );

// Выводим содержимое файла
$objWriter = new PHPExcel_Writer_Excel5($xls);
//$objWriter->save('matrix.xlsx');
$objWriter->save('php://output');


//echo 'End:'.'<br>';

?>


<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>