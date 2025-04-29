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
            'Tên khách hàng',
            'SĐT',
            'Địa chỉ',
            'Email',
            'Sản phẩm trong đơn hàng',
            'Trạng thái',
            'Tổng tiền',
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
            'B' => 20,
            'C' => 25,
            'D' => 15,
            'E' => 50,
            'F' => 30,
            'G' => 50,
            'H' => 20,
            'J' => 20,
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
        })->implode("\n");
        return [
            $total - (++$this->i),
            $row->id,
            $row->user->name,
            $row->user->phone_number,
            $row->user->address,
            $row->user->email,
            $products,
            $row->status,
            $row->total_price,
        ];
    }
}
