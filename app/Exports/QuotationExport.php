<?php

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuotationExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            ['PRODUCTO', 'CANTIDAD', 'PRECIO UNIT.', 'SUBTOTAL']
        ];
    }

    public function map($item): array
    {
        return [
            $item->product->nombre,
            $item->cantidad,
            $item->precio_unitario,
            $item->cantidad * $item->precio_unitario
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            6 => ['font' => ['bold' => true, 'background' => ['rgb' => 'EEEEEE']]],
        ];
    }
}
