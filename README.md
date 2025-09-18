# GeoFencing Extension for Magento 2

This module provides geofencing functionality for products in Magento 2. It allows store owners to define specific geographical areas for a product by providing a list of locations.

### Features:
- **Multi-Location Support:** Define multiple geofenced locations (pincodes, cities, addresses) for a single product.
- **Pincode Availability Check:** Customers can enter their pincode on the product page to check if the item is available in their area.
- **Interactive Mini-Map:** If enabled, a map displays on the product page with markers for all defined locations.
- **Admin Configuration:** Easily enable/disable the module, set your Google Maps API key, and toggle the mini-map feature from the Magento admin.

### How to Use

1.  **Configure the Module:**
    - Navigate to `Stores -> Configuration -> AgriCart -> Geofencing` in your Magento admin panel.
    - Enable the module, paste in your Google Maps API key, and configure the mini-map display as desired.

2.  **Set Product Locations:**
    - Edit any product in your catalog.
    - Find the **"Geo Location"** field.
    - Enter a list of locations. **Important: Add one location per line.**
    - **Example:**
      ```
      New York, NY
      90210
      London
      75001
      ```
    - Save the product.

The geofencing functionality will now be active on the product's page.