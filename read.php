<?php
require __DIR__ . '/vendor/autoload.php';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Informe.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
foreach ($worksheet->getRowIterator(1) as $row) {
    $col0 = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
    if (is_numeric($col0)) {
        echo "Found product at row " . $row->getRowIndex() . PHP_EOL;
        foreach ($row->getCellIterator() as $cell) {
            if ($cell->getValue() !== null && $cell->getValue() !== '') {
                $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($cell->getColumn()) - 1;
                echo $colIndex . ' (' . $cell->getColumn() . ') : ' . $cell->getValue() . PHP_EOL;
            }
        }
        break;
    }
}
