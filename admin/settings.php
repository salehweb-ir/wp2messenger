<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Retrieve saved token and channel ID from options
$token = get_option('token_eitaa_api');
$channel_id = get_option('eitaa_channel_id');

// Check for success message
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    echo '<div class="notice notice-success is-dismissible"><p>';
	esc_html_e('Configuration saved and form created successfully!', 'wp2messenger');
	echo '</p></div>';
}
?>

<div class="wp2messenger wrap">
    <h1><?php esc_html_e('WP2messenger Settings', 'wp2messenger'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp2messenger_options_group'); ?>
        <?php do_settings_sections('wp2messenger'); ?>
        <table class="wp2messenger form-table">
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Eitaa API Token', 'wp2messenger'); ?></th>
                <td><input type="text" name="token_eitaa_api" value="<?php echo esc_attr($token); ?>" readonly /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Eitaa Channel ID', 'wp2messenger'); ?></th>
                <td><input type="text" name="eitaa_channel_id" value="<?php echo esc_attr($channel_id); ?>" readonly /></td>
            </tr>
        </table>
    </form>
</div>
