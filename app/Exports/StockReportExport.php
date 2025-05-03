<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
// use stdClass

class StockReportExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithStrictNullComparison
{
  // /**
  //  * @var stdClass
  //  */
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
    // dd($this->responses);
  }

  /**
   * Heading
   * @return string[]
   */
  public function headings(): array
  {
    return [
      'STT',
      'Tên sản phẩm',
      'Số lượng tồn kho',
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
      'B' => 60,
      'C' => 8,
    ];
  }

  /**
   * Map
   * @param $row
   * @return array
   */
  public function map($row): array
  {
    return [
      (++$this->i),
      $row['product_name'],
      (int) ($row['stock'] ?? 0)
    ];
  }
}
