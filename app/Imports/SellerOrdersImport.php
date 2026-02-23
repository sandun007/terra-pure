<?php

namespace App\Imports;

use App\Models\SellerOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class SellerOrdersImport implements ToModel, WithHeadingRow, WithValidation
{
    private $sellerId;
    private $orderDate;
    private $platform;
    private $rowCount = 0;

    public function __construct($sellerId, $orderDate, $platform = null)
    {
        $this->sellerId = $sellerId;
        $this->orderDate = $orderDate;
        $this->platform = $platform;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        return new SellerOrder([
            'order_id' => SellerOrder::generateOrderId(),
            'order_date' => $this->orderDate,
            'platform' => $this->platform,
            'customer_name' => $row['customer_name'] ?? $row['name'] ?? null,
            'customer_phone' => $row['customer_phone'] ?? $row['phone'] ?? $row['mobile'] ?? null,
            'customer_address' => $row['customer_address'] ?? $row['address'] ?? null,
            'district' => $row['district'] ?? null,
            'total_amount' => $row['total_amount'] ?? $row['amount'] ?? 0,
            'status' => 'pending',
            'seller_id' => $this->sellerId,
        ]);
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}
