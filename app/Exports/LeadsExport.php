<?php

namespace App\Exports;

use App\Models\Lead;
use App\Repositories\Lead\LeadRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected $request, protected ?LeadRepository $leadRepository = null) {}

    public function collection(): Collection
    {
        $query = Lead::with(['status', 'source', 'country']);

        if ($this->request) {
            if ($this->request->has('status') && $this->request->status) {
                $query->where('status_id', $this->request->status);
            }

            if ($this->request->has('search') && $this->request->search) {
                $search = $this->request->search;
                $this->leadRepository->queryLead($query, $search);
            }
            $sortDirection = $this->request->get('sort', 'desc');
            $query->orderBy('created_at', $sortDirection);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Company',
            'Email',
            'Phone',
            'Status',
            'Source',
            'Address',
            'City',
            'State',
            'Country',
            'Website',
            'Position',
            'Lead Value',
            'Date Contacted',
            'Created At',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->name,
            $lead->company,
            $lead->email,
            $lead->phone,
            $lead->status?->name,
            $lead->source?->source,
            $lead->address,
            $lead->city,
            $lead->state,
            $lead->country?->name,
            $lead->website,
            $lead->position,
            $lead->lead_value,
            $lead->date_contacted,
            $lead->created_at,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,    // ID
            'B' => 18,   // Name
            'C' => 20,   // Company
            'D' => 28,   // Email
            'E' => 13,   // Phone
            'F' => 16,   // Status
            'G' => 18,   // Source
            'H' => 32,   // Address
            'I' => 16,   // City
            'J' => 13,   // State
            'K' => 16,   // Country
            'L' => 24,   // Website
            'M' => 18,   // Position
            'N' => 14,   // Lead Value
            'O' => 20,   // Date Contacted (increased width for no wrap)
            'P' => 20,   // Created At (increased width for no wrap)
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['argb' => 'FFFFFFFF'], // White font
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], // Indigo-600 background
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF6B7280'],
                    ],
                ],
            ],

            // ID column center alignment (including header)
            'A:A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => ['size' => 9],
            ],

            // Date columns - NO WRAP (Date Contacted & Created At)
            'O:P' => [
                'font' => ['size' => 9],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'wrapText' => false,  // Date columns no wrap
                ],
            ],

            // Other data columns styling (with wrap)
            'B:N' => [
                'font' => ['size' => 9],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
            ],

            // All data rows borders
            'A2:P1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE5E7EB'],
                    ],
                ],
            ],
        ];
    }
}
