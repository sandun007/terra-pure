<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                $order->waybill_number,
                $order->order_no,
                $order->customer_name,
                $order->customer_phone_01,
                $order->customer_phone_02,
                $order->customer_address,
                $order->email,
                $order->price,
                $order->city,
                $order->weight,
                $order->description,
                $order->remark,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'waybill_number',
            'order_no',
            'customer_name',
            'customer_phone',
            'customer_secondary_phone',
            'customer_address',
            'customer_email',
            'cod',
            'destination_city',
            'weight',
            'description',
            'remark',
        ];
    }
}
