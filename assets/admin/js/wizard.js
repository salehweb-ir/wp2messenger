jQuery(document).ready(function($) {
    // hide success note after 5 seconds
    $('.notice-success').delay(5000).slideUp(400);

    // manage form 
    $('.wp2messenger-wizard-form').on('submit', function(e) {
        e.preventDefault();

        // get form values
        var token = $('#token').val();
        var channelId = $('#channel_id').val();
        var testMessage = $('#test_message').val();

        // form validation
        if (!token || !channelId || !testMessage) {
            alert('please fill all fields.');
            return;
        }

        // send data to server
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'wp2messenger_test_connection',
                token: token,
                channel_id: channelId,
                test_message: testMessage
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                } else {
                    alert('failed to send test message. please try again.');
                }
            },
            error: function() {
                alert('error in server connection. please try again.');
            }
        });
    });
});
