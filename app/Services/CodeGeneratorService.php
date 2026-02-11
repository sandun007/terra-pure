<?php

namespace App\Services;

use App\Models\SellerBrand;

class CodeGeneratorService
{
    public function generate(string $type, string $prefix = ''): string
    {
        $prefixes = [
            'seller_brand' => 'BR',
            'product' => 'PR',
            'order' => 'OR',
        ];

        $prefix = $prefixes[$type] ?? $prefix;

        do {
            $code = $prefix . strtoupper(uniqid('', true));
        } while (SellerBrand::where('code', $code)->exists());

        return $code;
    }
}
