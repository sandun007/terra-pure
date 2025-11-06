<?php

namespace App\Http\Controllers;

use App\Models\InvPara;
use Illuminate\Http\Request;

class CodeGenerator extends Controller
{
    public function generateCode($type)
    {
//        ======================================== Category =============================================
        if ($type == 'category') {
            $invPara = InvPara::select('category_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->category_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['category_code' => $nextNumber]);
                $code = 'DX/CAT/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Category =============================================

//        ======================================== Brand =============================================
        if ($type == 'brand') {
            $invPara = InvPara::select('brand_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->brand_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['brand_code' => $nextNumber]);
                $code = 'DX/BRAND/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Category =============================================

//        ======================================== Product =============================================
        if ($type == 'product') {
            $invPara = InvPara::select('product_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->product_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['product_code' => $nextNumber]);
                $code = 'DX/ITM/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Product =============================================

//        ======================================== Invoice =============================================
        if ($type == 'inv') {
            $invPara = InvPara::select('inv_no')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->inv_no;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['inv_no' => $nextNumber]);
                $code = 'DX/INV/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Invoice =============================================

//        ======================================== Seller =============================================
        if ($type == 'seller') {
            $invPara = InvPara::select('seller_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->seller_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['seller_code' => $nextNumber]);
                $code = 'DX/SLR/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Seller =============================================

//        ======================================== Supplier =============================================
        if ($type == 'supplier') {
            $invPara = InvPara::select('supplier_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->supplier_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['supplier_code' => $nextNumber]);
                $code = 'DX/SUP/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Supplier =============================================

//        ======================================== Order =============================================
        if ($type == 'order') {
            $invPara = InvPara::select('order_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->order_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['order_code' => $nextNumber]);
                $code = 'DX/ORD/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Order =============================================

//        ======================================== Excel File No =============================================
        if ($type == 'file') {
            $invPara = InvPara::select('file_no')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->file_no;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['file_no' => $nextNumber]);
                $seller = auth()->user()->code; // e.g., DX/SLR/1001
                $code = explode('/', $seller);
                $lastPart = end($code); // returns "1001"

                $code = 'DX/FILE/' . $formattedNumber;
                return $lastPart;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== Excel File No =============================================

//        ======================================== Stock =============================================
        if ($type == 'stock') {
            $invPara = InvPara::select('stock_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->stock_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['stock_code' => $nextNumber]);
                $code = 'DX/STK/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Stock =============================================

//        ======================================== GRN =============================================
        if ($type == 'grn') {
            $invPara = InvPara::select('grn_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->grn_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['grn_code' => $nextNumber]);
                $code = 'DX/GRN/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End GRN =============================================

//        ======================================== Employee =============================================
        if ($type == 'employee') {
            $invPara = InvPara::select('employee_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->employee_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber = $nextNumber + 1;
                InvPara::query()
                    ->update(['employee_code' => $nextNumber]);
                $code = 'DX/EMP/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Employee =============================================




        return response()->json(['error' => 'Invalid type'], 400);
    }


    public function newCodeLoad($type)
    {
//        ======================================== Category =============================================
        if ($type == 'category') {
            $invPara = InvPara::select('category_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->category_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/CAT/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Category =============================================

//        ======================================== Brand =============================================
        if ($type == 'brand') {
            $invPara = InvPara::select('brand_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->brand_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/BRAND/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Category =============================================

//        ======================================== Product =============================================
        if ($type == 'product') {
            $invPara = InvPara::select('product_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->product_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/ITM/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Product =============================================

//        ======================================== Invoice =============================================
        if ($type == 'inv') {
            $invPara = InvPara::select('inv_no')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->inv_no;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/INV/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Invoice =============================================

//        ======================================== Seller =============================================
        if ($type == 'seller') {
            $invPara = InvPara::select('seller_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->seller_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/SLR/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Seller =============================================

//        ======================================== Supplier =============================================
        if ($type == 'supplier') {
            $invPara = InvPara::select('supplier_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->supplier_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/SUP/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Supplier =============================================

//        ======================================== Order =============================================
        if ($type == 'order') {
            $invPara = InvPara::select('order_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->order_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/ORD/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Order =============================================

//        ======================================== Stock =============================================
        if ($type == 'stock') {
            $invPara = InvPara::select('stock_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->stock_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/STK/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Stock =============================================

//        ======================================== GRN =============================================
        if ($type == 'grn') {
            $invPara = InvPara::select('grn_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->grn_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/GRN/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End GRN =============================================

//        ======================================== Employee Code =============================================
        if ($type == 'employee') {
            $invPara = InvPara::select('employee_code')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->employee_code;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $code = 'DX/EMP/' . $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== End Employee =============================================




//        ======================================== Excel File No =============================================
        if ($type == 'file') {
            $invPara = InvPara::select('file_no')->first();

            if ($invPara) {
                $nextNumber = (int)$invPara->file_no;
                $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                $seller = auth()->user()->code; // e.g., DX/SLR/1001
                $code = explode('/', $seller);
                $lastPart = end($code); // returns "1001"
                $formattedDate = date('Y_m_d'); // returns 2025_07_07
                $code = 'DX/FILE/' .$lastPart.'/'.$formattedDate.'/'. $formattedNumber;
                return $code;
            } else {
                return response()->json(['error' => 'InvPara not found'], 404);
            }
        }
//        ======================================== Excel File No =============================================

        return response()->json(['error' => 'Invalid type'], 400);
    }



//newCodeLoad
}
