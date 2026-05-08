<?php

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class QuotationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation->load('items.product');
    }

    public function collection()
    {
        return $this->quotation->items;
    }

    public function headings(): array
    {
        return [
            ['COTIZACIÓN #' . str_pad($this->quotation->id, 6, '0', STR_PAD_LEFT)],
            ['Cliente:', $this->quotation->nombre . ' ' . $this->quotation->apellidos],
            ['Documento:', $this->quotation->tipo_documento . ' ' . $this->quotation->numero_documento],
            ['Fecha:', $this->quotation->created_at->format('d/m/Y H:i')],
            [''],
            ['CÓDIGO', 'PRODUCTO', 'CANTIDAD', 'PRECIO UNIT.', 'SUBTOTAL']
        ];
    }

    public function map($item): array
    {
        return [
            $item->product->codigo,
            $item->product->nombre,
            $item->cantidad,
            $item->precio_unitario,
            $item->cantidad * $item->precio_unitario
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Estilo General de la Hoja
        $sheet->getStyle('A1:E' . $lastRow)->getFont()->setName('Arial');
        $sheet->getStyle('A1:E' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        // Estilo del Encabezado Principal (Cotización #)
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF10B981'], // Verde primario
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Datos del Cliente (Alineación)
        $sheet->getStyle('A2:A4')->getFont()->setBold(true);
        $sheet->getStyle('A2:B4')->applyFromArray([
            'font' => ['size' => 11]
        ]);

        // Estilo de la Cabecera de la Tabla (Fila 6)
        $sheet->getStyle('A6:E6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E293B'], // Gris oscuro
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCBD5E1'],
                ],
            ],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(25);

        // Estilo de los Datos de la Tabla (Desde la Fila 7)
        if ($lastRow >= 7) {
            $sheet->getStyle('A7:E' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE2E8F0'],
                    ],
                ],
            ]);
            // Centrar CÓDIGO y CANTIDAD
            $sheet->getStyle('A7:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C7:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Formato de Moneda para PRECIO y SUBTOTAL
            $sheet->getStyle('D7:E' . $lastRow)->getNumberFormat()->setFormatCode('"S/" #,##0.00');
        }

        return [];
    }
}
