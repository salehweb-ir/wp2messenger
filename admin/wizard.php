<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the send message functions
include_once plugin_dir_path(__FILE__) . '../includes/send-message.php';

// display wizard
function wp2messenger_display_wizard() {
    if (isset($_POST['submit'])) {
		
		if (!isset($_POST['wp2messenger_nonce']) || !wp_verify_nonce(wp_unslash($_POST['wp2messenger_nonce']), 'wp2messenger_nonce_action')) {
			wp_die(esc_html__('Security check failed.', 'wp2messenger')); // stop form processing if invalid nonce
		}
        // get form fields
		$token      = sanitize_text_field(wp_unslash($_POST['token']));
		$channel_id = sanitize_text_field(wp_unslash($_POST['channel_id']));
		$test_message = sanitize_text_field(wp_unslash($_POST['test_message']));


        // test connection to messenger
        $send_result = wp2messenger_send_text_message($token, $channel_id, $test_message);

        if ($send_result) {
            // save eitaa info in wp options
            update_option('token_eitaa_api', $token);
            update_option('eitaa_channel_id', $channel_id);

            // create new page contains template shortcode 
            $page_title = __('Anonymous message','wp2messenger');
            $page_content = '[default_template]'; // use shortcode to display default template form

            // check if page doesn't exist
            $page_check = new WP_Query(array(
				'post_type'  => 'page',
				'title'      => $page_title,
				'fields'     => 'ids', // only page ID returns
			));

			if (!$page_check->have_posts()) {
				$page_args = array(
					'post_type'    => 'page',
					'post_title'   => $page_title,
					'post_content' => $page_content,
					'post_status'  => 'publish',
					'post_author'  => get_current_user_id(),
				);

				$page_id = wp_insert_post($page_args);
			}

            // redirect to setting page with success message
            wp_safe_redirect(admin_url('admin.php?page=wp2messenger&success=true'));
            exit;
        } else {
			echo '<div class="notice notice-error is-dismissible"><p>';
			esc_html_e('Failed to send message. please try again later or contact site admin.', 'wp2messenger');
			echo '</p></div>';
		}
    }
    ?>

    <div class="wp2messenger-wrap">
        <h1><?php esc_html_e('WP2messenger Setup Wizard', 'wp2messenger'); ?></h1>

        <form method="post" class="wp2messenger-wizard-form">
			<?php wp_nonce_field('wp2messenger_nonce_action', 'wp2messenger_nonce'); ?>
            <h2><?php esc_html_e('Step 1: Eitaa API Information', 'wp2messenger'); ?></h2>
            <p><?php esc_html_e('Please follow the instructions on <a href="https://eitaayar.ir/admin/api" target="_blank">eitaa API documentation</a> to get your API token.', 'wp2messenger'); ?></p>
            <label for="token"><?php esc_html_e('Eitaa API Token', 'wp2messenger'); ?></label>
            <input type="text" name="token" id="token" required>

            <label for="channel_id"><?php esc_html_e('Eitaa Channel ID', 'wp2messenger'); ?></label>
            <input type="text" name="channel_id" id="channel_id" required>

            <h2><?php esc_html_e('Step 2: Test Connection', 'wp2messenger'); ?></h2>
            <label for="test_message"><?php esc_html_e('Test Message', 'wp2messenger'); ?></label>
            <input type="text" name="test_message" id="test_message" required>

            <input type="submit" name="submit" value="<?php esc_html_e('Test and Save', 'wp2messenger'); ?>" class="wp2messenger-button wp2messenger-button-primary">
        </form>
    </div>

    <?php
}

wp2messenger_display_wizard();
?>