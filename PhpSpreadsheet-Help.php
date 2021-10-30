use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

//создать лист и задать отступы
$oSpreadsheet = new Spreadsheet();
$sheet = $oSpreadsheet->getActiveSheet();
$sheet->getPageMargins()
    ->setLeft(0.2)
    ->setRight(0.2)
    ->setTop(0.2)
    ->setBottom(0.2)
;

//задать ширину столбца
$sheet->getColumnDimension('A')->setWidth('18');

//задать высоту строки
$sheet->getRowDimension(5)->setRowHeight($item['height']);


//обеденить ячейки
$sheet->mergeCells('A1:B3');

//вставить текст в ячейку
$sheet
    ->setCellValue('A10', 'Данные пользователя');
    



//Рамка, обводка для разных диапазонов
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];

$sheet->getStyle('A3:B4')->applyFromArray($styleArray);
$sheet->getStyle('D3:E4')->applyFromArray($styleArray);

//рамка обводка для одного диапазона
    $sheet->getStyle('A2:E8')
        ->getBorders()
        ->getOutline()
        ->setBorderStyle(Border::BORDER_THICK)
        ->setColor(new Color('000000'))
    ;

//Отступ ячееек
$sheet->getStyle('A3:A6')->getAlignment()->setIndent(1);

//Сделать жырными
$sheet->getStyle('A22:A27')->getFont()->setBold(true);

// перенос текста для ячейки
$sheet->getCell('A12')->getStyle()->getAlignment()->setWrapText(true);

//выравние по центру
 $sheet->getCell('A13')->getStyle()
     ->getAlignment()
     ->setHorizontal('center');

//задание шрифта
$sheet->getCell('A15')->getStyle()
    ->getFont()
    ->setName('Arial')
    ->setSize(16);

//Вставка html кода в ячейку
$wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html();

$richText = $wizard->toRichTextObject('<b>Привет</b><br><i>Пока</i>');
$sheet->setCellValue('A13',$richText);


//авторматичиская ширина для колонок

        foreach(range('A','M') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }



//Отдать файл на скачивание в браузер
$oWriter = IOFactory::createWriter($oSpreadsheet, 'Xlsx');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"filename.xlsx\"");
header("Cache-Control: max-age=0");
$oWriter->save('php://output');

//сохранить на диск
$oWriter = IOFactory::createWriter($oSpreadsheet, 'Xlsx');
$oWriter->save('file.xlsx');
