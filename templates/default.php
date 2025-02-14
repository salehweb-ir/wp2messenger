<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the send message functions from the includes folder
include_once plugin_dir_path(__DIR__) . '/includes/send-message.php';

// display form
function wp2messenger_display_form() {
    if (isset($_POST['submit'])) {
		
		// check nonce before processing form
    	if (!isset($_POST['wp2messenger_nonce']) || !wp_verify_nonce(wp_unslash($_POST['wp2messenger_nonce']), 'wp2messenger_nonce_action')) {        			wp_die(esc_html__('Security check failed.', 'wp2messenger')); // 
    	}
        // get message from form
        $message = sanitize_text_field(wp_unslash($_POST['message']));

        // get token and ID from options
        $token = get_option('token_eitaa_api');
        $channel_id = get_option('eitaa_channel_id');

        // send message to Eitaa
        $send_result = wp2messenger_send_text_message($token, $channel_id, $message);

        if ($send_result) {
			echo '<div class="notice notice-success is-dismissible"><p>';
			esc_html_e('Message sent successfully.', 'wp2messenger');
			echo '</p></div>';
        } else {
			echo '<div class="notice notice-error is-dismissible"><p>';
			esc_html_e('Failed to send message. please try again later or contact site admin.', 'wp2messenger');
			echo '</p></div>';
        }
    }
    ?>

    <div class="wp2messenger-page-content">
        <h1><?php esc_html_e('Submit Your Message', 'wp2messenger'); ?></h1>
        <form method="post" class="wp2messenger-form" enctype="multipart/form-data">
			<?php wp_nonce_field('wp2messenger_nonce_action', 'wp2messenger_nonce'); ?>
            <label for="wp2messenger-message"><?php esc_html_e('Your Message', 'wp2messenger'); ?></label>
            <textarea id="wp2messenger-message" name="message" required></textarea>
            <input type="submit" name="submit" value="<?php esc_html_e('Submit', 'wp2messenger'); ?>" class="wp2messenger-button wp2messenger-button-primary">
        </form>
    </div>

    <?php
}

wp2messenger_display_form();
?>