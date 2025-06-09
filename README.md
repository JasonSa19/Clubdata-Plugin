# Vereinsdaten Manager (Clubdata)

A WordPress Must-Use Plugin to manage and display club information (address, phone, email) from the backend and via shortcode.

## Features
- Adds a custom admin page for managing club data (address, phone, email)
- Stores club data in the WordPress options table
- Provides a shortcode `[clubdata]` to display club information on the frontend
- Sanitizes and validates all input data
- Includes helper functions for retrieving and formatting club data
- Custom admin styles for a better backend experience

## Installation
1. Place the `Clubdata-Plugin` folder in your `wp-content/mu-plugins/` directory.
2. Ensure the main plugin file is named `Clubdata.php` and is located at `wp-content/mu-plugins/Clubdata-Plugin/Clubdata.php`.
3. (Optional) Add or modify `admin-style.css` in the same directory for custom admin styling.

**Note:** Must-Use (MU) plugins are automatically activated by WordPress and do not appear in the regular plugins list.

## Admin Usage
1. Log in to your WordPress admin dashboard.
2. In the left sidebar, find the menu item labeled **Vereinsdaten**.
3. Click on **Vereinsdaten** to open the club data management page.
4. Fill in the fields for **Telefonnummer** (phone), **E-Mail**, and **Addresse** (address).
5. Click the **Vereinsdaten speichern** button to save your changes.

## Shortcode Usage
You can display the club data anywhere on your site using the `[clubdata]` shortcode.

**Example:**
```
[clubdata]
```

This will output the address, email, and phone number as entered in the backend, formatted for display.

### Output Example
```
<div class="clubdata">
    <div class="address">...address...</div>
    <div class="email">...email...</div>
    <p class="phone"><strong>Tel.:</strong> <a href="tel:...">...</a></p>
</div>
```

## Template/Theme Integration
You can also retrieve club data programmatically in your theme or plugin code:

``php
// Get all club data as an array
$clubdata = get_clubdata();

// Get a specific field (e.g., phone)
$phone = get_clubdata('phone');

// Get a formatted phone anchor
$phone_href = trim_phone_number($phone);

// Get a formatted email anchor
$email = get_clubdata('email');
$email_href = trim_email($email);
``

## Helper Functions
- `get_clubdata($field = '')`: Returns all club data as an array, or a specific field as a string.
- `trim_phone_number($phone)`: Returns a sanitized phone number suitable for `tel:` links.
- `trim_email($email)`: Returns a sanitized email suitable for `mailto:` links.

## Customization
- To change the admin page styles, edit `admin-style.css` in the plugin directory.
- To add more fields, modify the `$fields` array in `vdm_settings_init()` and update the sanitization and rendering functions accordingly.

## Uninstall
To remove the plugin, simply delete the `Clubdata-Plugin` folder from your `mu-plugins` directory. (Note: This will not automatically remove the saved club data from the database.)

## Support
For questions or issues, contact the plugin author or open an issue in your project repository.

---
**Author:** jasonsa19 