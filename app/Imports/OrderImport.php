<?php

namespace App\Imports;

use App\Http\Controllers\CodeGenerator;
use App\Models\Order;
use App\Models\Product;
use App\Models\Saler;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
class OrderImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $total_cost=0,$row_count=0;
    public function startRow(): int
    {
        return 2; // Skip the first row (headers)
    }
    public function model(array $row)
    {
        if(auth()->user()->type == 'self_seller'){
            $this->total_cost = $this->total_cost + 40;

            $seller = Saler::query()
                ->where('id', auth()->user()->id)
                ->first();

            $deposit = $seller->cal_deposit - 40;

            if ($deposit >= 40) {
                $codeGenerate = new CodeGenerator();
                $code = $codeGenerate->newCodeLoad('order');
                $codeGenerate->generateCode('order');

                $user = auth()->user();

                $product = Product::query()
                    ->where('code', $row[3])
                    ->first();

                $product_name = $product->name ?? 'Unknown';

                Saler::query()
                    ->where('id', $seller->id)
                    ->update([
                        'cal_deposit' => $deposit,
                        'cost' => $this->total_cost,
                        'last_row_count'=>$this->row_count,
                    ]);
                $this->row_count++;
                return new Order([
                    'order_no' => $code,
                    'date' => date('Y-m-d'),
                    'seller_no' => $user->code,
                    'item_name' => $product_name,
                    'item_code' => $row[3],
                    'customer_name' => $row[12],
                    'customer_phone_01' => $row[13],
                    'customer_phone_02' => $row[14],
                    'customer_address' => $row[15],
                    'city' => $row[16],
                ]);
            } else {
                return null;
            }
        }
        else{
            $codeGenerate = new CodeGenerator();
            $code = $codeGenerate->newCodeLoad('order');
            $codeGenerate->generateCode('order');
            $user = auth()->user();
            $product = Product::query()
                ->where('code', $row[3])
                ->first();

            $product_name = $product->name ?? 'Unknown';
            return new Order([
                'order_no' => $code,
                'date' => date('Y-m-d'),
                'seller_no' => $user->code,
                'item_name' => $product_name,
                'item_code' => $row[3],
                'customer_name' => $row[12],
                'customer_phone_01' => $row[13],
                'customer_phone_02' => $row[14],
                'customer_address' => $row[15],
                'city' => $row[16],
            ]);
        }
       
    }

}
