<?php
/*
Plugin Name: Vereinsdaten Manager
Description: Manages club information (address, phone, name) in the backend
Version: 1.0
textdomain: clubdata
Author: jasonsa19
*/

if (!defined('ABSPATH')) exit;

/*** Backend Integration ***/
add_action('admin_menu', 'vdm_add_admin_page');
add_action('admin_init', 'vdm_settings_init');
add_action('admin_enqueue_scripts', 'vdm_enqueue_admin_styles');

/**
 * Adds an admin page for managing club data.
 *
 * This function creates a new menu item in the WordPress admin dashboard
 * for managing club data such as address, phone number, and email.
 * 
 * @return void
 */
function vdm_add_admin_page(): void
{
    add_menu_page(
        'Vereinsdaten',
        'Vereinsdaten',
        'manage_options',
        'clubdata',
        'vdm_render_admin_page',
        'dashicons-admin-site-alt',
        25
    );
}

/**
 * Initializes the settings for club data.
 *
 * This function registers the settings group and adds sections and fields
 * for managing club data such as address, phone number, and email.
 * 
 * @return void
 */
function vdm_settings_init(): void
{
    register_setting('vdm_settings_group', 'vdm_clubdata', [
        'sanitize_callback' => 'vdm_sanitize_options'
    ]);

    add_settings_section(
        'vdm_main_section',
        'Vereinsdaten',
        null,
        'clubdata'
    );

    $fields = [
        [
            'id'       => 'vdm_phone',
            'title'    => 'Telefonnummer',
            'callback' => 'vdm_render_text_field',
            'args'     => ['label_for' => 'vdm_phone', 'name' => 'phone'],
        ],
        [
            'id'       => 'vdm_email',
            'title'    => 'E-Mail',
            'callback' => 'vdm_render_text_field',
            'args'     => ['label_for' => 'vdm_email', 'name' => 'email'],
        ],
        [
            'id'       => 'vdm_address',
            'title'    => 'Addresse',
            'callback' => 'vdm_render_textarea_field',
            'args'     => ['label_for' => 'vdm_address', 'name' => 'address'],
        ],
    ];

    foreach ( $fields as $field ) {
        add_settings_field(
            $field['id'],
            $field['title'],
            $field['callback'],
            'clubdata',
            'vdm_main_section',
            $field['args']
        );
    }
}

/**
 * Enqueue admin styles for the plugin.
 * 
 * This function loads the custom CSS styles for the plugin's admin page.
 * 
 * @param string $hook The current admin page hook.
 * @return void
 */
function vdm_enqueue_admin_styles($hook): void
{
    if ($hook !== 'toplevel_page_clubdata') {
        return;
    }
    wp_enqueue_style(
        'vdm-admin-style',
        plugin_dir_url(__FILE__) . 'admin-style.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'admin-style.css')
    );
}

/**
 * Renders a text field for club data.
 *
 * This function generates a text input field for the club data settings page.
 * It retrieves the current value from the options table and displays it in the input field.
 * 
 * @param array $args The arguments for the text field, including label_for and name.
 * @return void
 */
function vdm_render_text_field($args): void
{
    $options = get_option('vdm_clubdata');
    echo '<input type="text" class="regular-text" id="' . $args['label_for'] . '" 
          name="vdm_clubdata[' . $args['name'] . ']" 
          value="' . esc_attr($options[$args['name']] ?? '') . '">';
}

/**
 * Renders a textarea field for club data.
 *
 * This function generates a textarea input field for the club data settings page.
 * It retrieves the current value from the options table and displays it in the textarea.
 * 
 * @param array $args The arguments for the textarea field, including label_for and name.
 * @return void
 */
function vdm_render_textarea_field($args): void
{
    $options = get_option('vdm_clubdata');
    echo '<textarea class="large-text" id="' . $args['label_for'] . '" 
          name="vdm_clubdata[' . $args['name'] . ']" rows="4">'
        . esc_textarea($options[$args['name']] ?? '') . '</textarea>';
}

/**
 * Sanitizes the club data options.
 *
 * This function ensures that the input data is sanitized before saving it to the database.
 * It removes unwanted characters and formats the data appropriately.
 * 
 * @param array $input The input data from the settings form.
 * @return array The sanitized data ready for storage.
 */
function vdm_sanitize_options($input): array
{
    $sanitized = [];
    $sanitized['phone'] = sanitize_text_field($input['phone'] ?? '');
    $sanitized['address'] = sanitize_textarea_field($input['address'] ?? '');
    $sanitized['email'] = sanitize_textarea_field($input['email'] ?? '');
    return $sanitized;
}

/**
 * Renders the admin page for club data management.
 *
 * This function displays the admin page where users can manage club data.
 * It includes a form for updating the club's address, phone number, and email.
 * 
 * @return void
 */
function vdm_render_admin_page(): void
{
    if (!current_user_can('manage_options')) return;
?>
    <div class="wrap vdm-admin-wrap">
        <h1>
            <span class="vdm-admin-icon">
                <span class="dashicons dashicons-admin-site-alt"></span>
            </span>
            <?= esc_html(get_admin_page_title()) ?>
        </h1>
        <div class="vdm-admin-content">
            <form action="options.php" method="post">
                <?php
                settings_fields('vdm_settings_group');
                do_settings_sections('clubdata');
                submit_button(__('Vereinsdaten speichern', 'clubdata'));
                ?>
            </form>
        </div>
    </div>
<?php
}

/**
 * Shortcode to display club data.
 *
 * This function generates the HTML output for the club data shortcode.
 * It retrieves the club data from the options table and formats it for display.
 * 
 * @return string The HTML output of the club data.
 */
function vdm_shortcode(): string
{
    $options = get_option('vdm_clubdata');
    ob_start(); ?>

    <div class="clubdata">

        <?php if (!empty($options['address'])) : ?>
            <div class="address">
                <?= wpautop(esc_html($options['address'])) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($options['email'])) : ?>
            <div class="email">
                <?= wpautop(esc_html($options['email'])) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($options['phone'])) : ?>
            <p class="phone">
                <strong>Tel.:</strong>
                <a href="tel:<?= esc_attr(preg_replace('/[^0-9+]/', '', $options['phone'])) ?>">
                    <?= esc_html($options['phone']) ?>
                </a>
            </p>
        <?php endif; ?>
    </div>

<?php return ob_get_clean();
}

/*** Frontend Integration ***/
add_shortcode('clubdata', 'vdm_shortcode');

/**
 * Retrieves club data from the options table.
 *
 * This function fetches the club data stored in the WordPress options table.
 * If a specific field is requested, it returns the sanitized value of that field.
 * If no field is specified, it returns all club data as an associative array.
 * 
 * @param string $field Optional. The specific field to retrieve. Defaults to an empty string.
 * @return array|string The club data or a specific field value.
 */
function get_clubdata($field = ''): array|string
{
    $options = get_option('vdm_clubdata');

    if ($field && isset($options[$field])) {
        return esc_html($options[$field]);
    }

    return $options ? array_map('esc_html', $options) : [];
}

/**
 * Trims the phone number for use as an anchor.
 *
 * This function processes a phone number string to remove unnecessary characters,
 * making it suitable for use as an anchor (e.g., in tel: links).
 * 
 * @param string $phone The phone number to be trimmed.
 * @return string The trimmed phone number prefixed with 'tel:' or an empty string if invalid.
 */
function trim_phone_number($phone): string
{
    $trimmed_phone = preg_replace('/[^0-9+]/', '', $phone);
    return $trimmed_phone ? 'tel:' . $trimmed_phone : '';
}

/**
 * Trims the E-Mail for use as an anchor.
 *
 * This function processes a E-Mail string to remove unnecessary characters,
 * making it suitable for use as an anchor (e.g., in mail: links).
 * 
 * @param string $email The E-Mail to be trimmed.
 * @return string The trimmed E-Mail prefixed with 'mail:' or an empty string if invalid.
 */
function trim_email($email): string
{
    return $email ? 'mailto:' . sanitize_email($email) : '';
}
