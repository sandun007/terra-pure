<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WholesalerStorageService
{
    /**
     * Get the authenticated wholesaler
     */
    public function getWholesaler()
    {
        return Auth::guard('wholesaler')->user();
    }

    /**
     * Get wholesaler ID
     */
    public function getWholesalerId()
    {
        $wholesaler = $this->getWholesaler();
        return $wholesaler ? $wholesaler->id : null;
    }

    /**
     * Save wholesaler data to localStorage via JavaScript
     */
    public function getLocalStorageScript()
    {
        $wholesaler = $this->getWholesaler();

        if (!$wholesaler) {
            return '';
        }

        $data = [
            'wholesaler_id' => $wholesaler->id,
            'business_name' => $wholesaler->business_name,
            'email' => $wholesaler->email,
            'profile_image' => $wholesaler->img ? Storage::url($wholesaler->img) : null,
            'timestamp' => now()->timestamp
        ];

        $jsonData = json_encode($data);

        return "
            <script>
                // Save wholesaler data to localStorage
                if (typeof localStorage !== 'undefined') {
                    localStorage.setItem('wholesaler_data', '{$this->escapeJsonForJS($jsonData)}');

                    // Set a cookie for backup (7 days expiry)
                    document.cookie = 'wholesaler_id={$wholesaler->id}; path=/; max-age=' + (7 * 24 * 60 * 60) + '; samesite=lax';
                }
            </script>
        ";
    }

    /**
     * Get file storage locations for wholesaler
     */
    public function getStorageLocations($wholesalerId = null)
    {
        $wholesalerId = $wholesalerId ?? $this->getWholesalerId();

        if (!$wholesalerId) {
            return [];
        }

        return [
            'profile_images' => "public/wholesaler/profile/{$wholesalerId}",
            'shop_logos' => "public/wholesaler/shop/logo/{$wholesalerId}",
            'shop_banners' => "public/wholesaler/shop/banner/{$wholesalerId}",
            'product_images' => "public/wholesaler/products/{$wholesalerId}",
            'brand_images' => "public/wholesaler/brands/{$wholesalerId}",
            'category_images' => "public/wholesaler/categories/{$wholesalerId}",
            'documents' => "public/wholesaler/documents/{$wholesalerId}",
        ];
    }

    /**
     * Get base storage path for wholesaler
     */
    public function getBaseStoragePath($wholesalerId = null)
    {
        $wholesalerId = $wholesalerId ?? $this->getWholesalerId();
        return "wholesaler/{$wholesalerId}";
    }

    /**
     * Clean JSON string for JavaScript embedding
     */
    private function escapeJsonForJS($json)
    {
        return str_replace(
            ['\\', '"', "'", "\n", "\r", "\t"],
            ['\\\\', '\\"', "\\'", '\\n', '\\r', '\\t'],
            $json
        );
    }

    /**
     * Clear localStorage data
     */
    public function getClearStorageScript()
    {
        return "
            <script>
                // Clear wholesaler data from localStorage
                if (typeof localStorage !== 'undefined') {
                    localStorage.removeItem('wholesaler_data');
                    localStorage.removeItem('wholesaler_id');
                }
            </script>
        ";
    }

    /**
     * Validate and sanitize wholesaler data from localStorage
     */
    public function validateLocalStorageData($data)
    {
        if (!isset($data['wholesaler_id']) || !is_numeric($data['wholesaler_id'])) {
            return false;
        }

        // Validate timestamp (data should be less than 24 hours old)
        if (isset($data['timestamp'])) {
            $age = time() - $data['timestamp'];
            if ($age > 86400) { // 24 hours in seconds
                return false;
            }
        }

        return true;
    }
}
