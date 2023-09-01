<?php

namespace App\Exports;

use App\Models\Role;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $startDate;
    public $endDate;


    public function __construct($startDate,$endDate){
        
        
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        
    }
    function array(): array
    {
        $role = Auth::user()->roles()->first();
        $view_records = Role::findOrFail($role->id)->inRole('record_view');
        // dd($this->startDate);
        // Check If User Has Permission View  All Records
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/',  $this->startDate)) {
            $this->startDate .= ' 00:00:00';
        } else {
            $this->startDate = '1970-01-01 00:00:00';
        }
        $this->endDate .= ' 23:59:59';

        
        // dd($this->startDate, $this->endDate);
        $Sales = Sale::with('details', 'client', 'facture', 'warehouse','user')
            ->where('deleted_at', '=', null)
            // ->where('created_at', '>=', $this->startDate)
            ->whereBetween('created_at',[$this->startDate,$this->endDate])
            ->where(function ($query) use ($view_records) {
                if (!$view_records) {
                    return $query->where('user_id', '=', Auth::user()->id);
                }
            })->orderBy('id', 'DESC')->get();

        if ($Sales->isNotEmpty()) {
            $data = [];

            foreach ($Sales as $sale) {

                $item['Ref'] = $sale->Ref;
                $item['client'] = $sale['client']->name;
                $item['created_by'] = $sale['user']->username;
                $item['statut'] = $sale->statut;
                $item['GrandTotal'] = number_format($sale->GrandTotal, 2);
                $item['Paid'] = number_format($sale->paid_amount, 2);
                $item['due'] = number_format($sale->GrandTotal - $sale->paid_amount, 2);
                $item['payment_status'] = $sale->payment_statut;
                $item['shipping_status'] = $sale->shipping_status;

                $data[] = $item;
            }
        } else {
            $data = [];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:H1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ];

            },
        ];

    }

    public function headings(): array
    {
        return [
            'Reference',
            'Customer',
            'Created by',
            'Status',
            'Total',
            'Paid',
            'Due',
            'Payment Status',
            'Shipping Status',
        ];
    }
}
