<?php

namespace App\Exports;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    public function __construct(
        private array $filters = []
    ) {}

    public function collection()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->when($this->filters['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($this->filters['date_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('expense_date', '>=', $dateFrom);
            })
            ->when($this->filters['date_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('expense_date', '<=', $dateTo);
            })
            ->latest()
            ->get()
            ->map(fn ($expense) => [
                Carbon::parse($expense->expense_date)->format('d/m/Y'),
                $expense->category->name,
                $expense->description,
                $expense->amount,
            ]);
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Categoría',
            'Descripción',
            'Monto',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"S/ "#,##0.00',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->insertNewRowBefore(1, 2);

                $sheet->setCellValue('A1', 'REPORTE DE GASTOS');
                $sheet->mergeCells('A1:D1');

                $sheet->setCellValue('A2', 'Generado: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells('A2:D2');

                $sheet->insertNewRowBefore(3, 2);

                $sheet->setCellValue('A3', 'Filtros aplicados:');

                $filtersText = [];

                if (!empty($this->filters['search'])) {
                    $filtersText[] = 'Búsqueda: ' . $this->filters['search'];
                }

                if (!empty($this->filters['date_from'])) {
                    $filtersText[] = 'Desde: ' . \Carbon\Carbon::parse($this->filters['date_from'])->format('d/m/Y');
                }

                if (!empty($this->filters['date_to'])) {
                    $filtersText[] = 'Hasta: ' . \Carbon\Carbon::parse($this->filters['date_to'])->format('d/m/Y');
                }

                $sheet->setCellValue('B3', count($filtersText) ? implode(' | ', $filtersText) : 'Sin filtros');

                $sheet->mergeCells('B3:D3');

                $lastRow = $sheet->getHighestRow() + 1;

                $sheet->setCellValue('C' . $lastRow, 'TOTAL GENERAL');
                $sheet->setCellValue('D' . $lastRow, '=SUM(D6:D' . ($lastRow - 1) . ')');

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                    ],
                ]);

                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '6B7280'],
                    ],
                ]);

                $sheet->getStyle('A5:D5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '2563EB'],
                    ],
                ]);

                $sheet->getStyle('A3:D' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                $sheet->getStyle('C' . $lastRow . ':D' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);
                $sheet->getStyle('C:C')->getAlignment()->setWrapText(true);

                $sheet->getStyle('D6:D' . $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('"S/ "#,##0.00');
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(60);
                $sheet->getColumnDimension('D')->setWidth(15);
            },
        ];
    }
}
