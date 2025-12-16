/**
 * Wholesaler Storage Utility
 * Handles localStorage operations for wholesaler data
 */

class WholesalerStorage {
    constructor() {
        this.wholesalerData = null;
        this.loadData();
    }

    /**
     * Load wholesaler data from localStorage
     */
    loadData() {
        try {
            const storedData = localStorage.getItem('wholesaler_data');
            if (storedData) {
                this.wholesalerData = JSON.parse(storedData);

                // Validate data
                if (!this.validateData(this.wholesalerData)) {
                    console.warn('Invalid wholesaler data in localStorage, clearing...');
                    this.clearData();
                    return false;
                }

                return true;
            }
        } catch (error) {
            console.error('Error loading wholesaler data from localStorage:', error);
            this.clearData();
        }
        return false;
    }

    /**
     * Validate wholesaler data
     */
    validateData(data) {
        if (!data || typeof data !== 'object') return false;

        // Check for required fields
        if (!data.wholesaler_id || !data.timestamp) return false;

        // Check if data is expired (older than 24 hours)
        const age = Date.now() - (data.timestamp * 1000);
        if (age > 24 * 60 * 60 * 1000) return false; // 24 hours

        return true;
    }

    /**
     * Get wholesaler ID
     */
    getWholesalerId() {
        return this.wholesalerData?.wholesaler_id || null;
    }

    /**
     * Get business name
     */
    getBusinessName() {
        return this.wholesalerData?.business_name || '';
    }

    /**
     * Get profile image URL
     */
    getProfileImage() {
        return this.wholesalerData?.profile_image || null;
    }

    /**
     * Get email
     */
    getEmail() {
        return this.wholesalerData?.email || '';
    }

    /**
     * Save data to localStorage
     */
    saveData(data) {
        try {
            const saveData = {
                ...data,
                timestamp: Math.floor(Date.now() / 1000)
            };

            localStorage.setItem('wholesaler_data', JSON.stringify(saveData));
            this.wholesalerData = saveData;

            return true;
        } catch (error) {
            console.error('Error saving to localStorage:', error);
            return false;
        }
    }

    /**
     * Clear all wholesaler data from localStorage
     */
    clearData() {
        try {
            localStorage.removeItem('wholesaler_data');
            this.wholesalerData = null;

            // Also clear related items
            localStorage.removeItem('wholesaler_orders_cache');
            localStorage.removeItem('wholesaler_products_cache');

            return true;
        } catch (error) {
            console.error('Error clearing localStorage:', error);
            return false;
        }
    }

    /**
     * Check if wholesaler data exists
     */
    hasData() {
        return this.wholesalerData !== null;
    }

    /**
     * Get all data
     */
    getAllData() {
        return this.wholesalerData || {};
    }

    /**
     * Set specific data
     */
    setData(key, value) {
        if (!this.wholesalerData) {
            this.wholesalerData = {};
        }

        this.wholesalerData[key] = value;
        return this.saveData(this.wholesalerData);
    }
}

// Create global instance
window.WholesalerStorage = new WholesalerStorage();

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WholesalerStorage;
}
