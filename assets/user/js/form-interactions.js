// javascript interaciton to form

document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('custom-form');
    var messageField = document.getElementById('message');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // prevent send default data

        var message = messageField.value;

        if (message.trim() === '') {
            alert('please add uoure l.');
            return;
        }

        // send meesega to
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(new FormData(form)).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('message sent successfully');
                form.reset(); // reset form
            } else {
                alert('Failde to send message. please try again');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('error in server connection.');
        });
    });
});
