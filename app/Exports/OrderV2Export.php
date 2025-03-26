<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use stdClass;

class OrderV2Export implements FromCollection, WithMapping, WithHeadings, WithColumnWidths
{
    /**
     * @var stdClass
     */
    private $responses = null;

    /**
     * @var int
     */
    private $i = 0;

    /**
     * @param $responses
     */
    public function __construct($responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $result_excel = $this->responses;
        return collect($result_excel);
    }

    /**
     * Heading
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'STT',
            'Mã đơn hàng',
            'Mã khách hàng',
            'Sản phẩm trong đơn hàng',
            'Tổng tiền',
            'Trạng thái',
        ];
    }

    /**
     * columnWidths
     * @return int[]
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 10,
            'C' => 10,
            'D' => 40,
            'E' => 20,
            'F' => 10,
        ];
    }

    /**
     * Map
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        $total = count($this->responses) + 1;
        $products = collect($row->orderItems)->map(function ($item) {
            return "{$item->product->name} (\${$item->price}) (x{$item->quantity})";
        })->implode(', ');
        return [
            $total - (++$this->i),
            $row->id,
            $row->user_id,
            $products,
            $row->total_price,
            $row->status,
        ];
    }
}
